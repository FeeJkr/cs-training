<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\Exception\PlayerException;
use App\Faceit\Domain\Contract\Faceit;
use App\Faceit\Domain\Contract\FaceitException;
use App\Faceit\Domain\Player\GetAll\PlayerElement;
use App\Faceit\Domain\Player\GetAll\PlayerList;
use App\Faceit\Domain\Player\PlayerFactory;
use App\Faceit\Domain\Player\PlayerRepository;

final class PlayerService
{
    public function __construct(
        private Faceit $faceit,
        private PlayerRepository $repository,
        private PlayerFactory $factory
    ){}

    /**
     * @throws ApplicationException
     */
    public function add(string $nickname): void
    {
        try {
            if ($this->repository->userExists($nickname)) {
                throw PlayerException::playerAlreadyExists($nickname);
            }

            $playerResponse = $this->faceit->getPlayerByNickname($nickname);
            $player = $this->factory->createFromResponse($playerResponse);

            $this->repository->add($player);
        } catch (FaceitException $exception) {
            throw PlayerException::createFromDomainException($exception);
        }
    }

    public function getAll(): PlayerList
    {
        return $this->repository->getAll();
    }

    public function getByNickname(string $nickname): PlayerElement
    {
        return $this->repository->getByNickname($nickname);
    }

    /**
     * @throws ApplicationException
     */
    public function update(string $playerId): void
    {
        try {
            $player = $this->repository->getByPlayerId($playerId);
            $playerResponse = $this->faceit->getPlayerByNickname($player->getNickname());

            $player->update(
                $playerResponse->getFaceitElo(),
                $playerResponse->getSkillLevel(),
                $playerResponse->getAvatar()
            );

            $this->repository->save($player);
        } catch (FaceitException $exception) {
            throw PlayerException::createFromDomainException($exception);
        }

    }
}
