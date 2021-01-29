<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\Exception\PlayerException;
use App\Faceit\Domain\Contract\Faceit;
use App\Faceit\Domain\Contract\FaceitException;
use App\Faceit\Domain\Player\GetAll\PlayerElement;
use App\Faceit\Domain\Player\GetAll\PlayerList;
use App\Faceit\Domain\Player\Player;
use App\Faceit\Domain\Player\PlayerFactory;
use App\Faceit\Domain\Player\PlayerRepository;

final class PlayerService
{
    private Faceit $faceit;
    private PlayerRepository $repository;
    private PlayerFactory $factory;

    public function __construct(Faceit $faceit, PlayerRepository $repository, PlayerFactory $factory)
    {
        $this->faceit = $faceit;
        $this->repository = $repository;
        $this->factory = $factory;
    }

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
}
