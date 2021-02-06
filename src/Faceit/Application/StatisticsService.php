<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\Exception\StatisticsException;
use App\Faceit\Domain\Contract\Faceit;
use App\Faceit\Domain\Contract\FaceitException;
use App\Faceit\Domain\Match\GetByPlayer\Scope;
use App\Faceit\Domain\Statistics\StatisticsCollection;
use App\Faceit\Domain\Statistics\StatisticsFactory;
use App\Faceit\Domain\Statistics\StatisticsRepository;
use App\Faceit\Domain\Statistics\StatisticsType;

final class StatisticsService
{
    public function __construct(
        private StatisticsRepository $repository,
        private StatisticsFactory $factory,
        private Faceit $faceit
    ){}

    /**
     * @throws ApplicationException
     */
    public function add(string $playerId): void
    {
        try {
            $this->recalculateGlobalStatistics($playerId);
            $this->recalculateMonthStatistics($playerId);
        } catch (FaceitException $exception) {
            throw StatisticsException::createFromDomainException($exception);
        }
    }

    /**
     * @throws FaceitException
     */
    private function recalculateGlobalStatistics(string $playerId): void
    {
        $statisticsResponse = $this->faceit->getPlayerStatistics($playerId);
        $statistics = $this->factory->createFromResponse($statisticsResponse);

        $this->repository->save($statistics);
    }

    private function recalculateMonthStatistics(string $playerId): void
    {
        $matches = $this->repository->getPlayerMatchesByMonth($playerId);
        $statistics = $this->factory->createFromMatchList($playerId, $matches, StatisticsType::MONTH());

        $this->repository->save($statistics);
    }

    public function getByPlayer(string $playerId): StatisticsCollection
    {
        return $this->repository->getAllByPlayer($playerId);
    }
}
