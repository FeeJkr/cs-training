<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\Exception\MatchException;
use App\Faceit\Domain\Contract\Faceit;
use App\Faceit\Domain\Contract\FaceitException;
use App\Faceit\Domain\Match\GetByPlayer\MatchList;
use App\Faceit\Domain\Match\GetByPlayer\Scope;
use App\Faceit\Domain\Match\MatchFactory;
use App\Faceit\Domain\Match\MatchRepository;

final class MatchService
{
    public function __construct(
        private MatchRepository $repository,
        private Faceit $faceit,
        private MatchFactory $factory
    ) {}

    /**
     * @throws ApplicationException
     */
    public function add(string $playerId, int $limit): void
    {
        try {
            $matchesCollectionResponse = $this->faceit->getPlayerMatches($playerId, $limit);
            $matches = $this->factory->createCollectionFromResponse($matchesCollectionResponse);

            foreach ($matches->toArray() as $match) {
                if (! $match->isFinished() || $this->repository->exists($match->getFaceitId())) {
                    continue;
                }

                $this->repository->add($match);
            }
        } catch (FaceitException $exception) {
            throw MatchException::createFromDomainException($exception);
        }
    }

    public function getByPlayer(string $playerId, ?Scope $scope = null): MatchList
    {
        return $this->repository->getByPlayer($playerId, $scope ?? Scope::GLOBAL());
    }
}
