<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

use App\Faceit\Domain\Contract\Statistics\StatisticsResponse;
use App\Faceit\Domain\Id;
use App\Faceit\Domain\Match\GetByPlayer\MatchList;

final class StatisticsFactory
{
    private StatisticsSegmentFactory $statisticsSegmentFactory;

    public function __construct(StatisticsSegmentFactory $statisticsSegmentFactory)
    {
        $this->statisticsSegmentFactory = $statisticsSegmentFactory;
    }

    public function createFromResponse(StatisticsResponse $response): Statistics
    {
        return new Statistics(
            Id::nullable(),
            StatisticsType::GLOBAL(),
            $response->getPlayerId(),
            $response->getMatches(),
            $response->getWins(),
            $response->getWinRate(),
            $response->getTotalKdRatio(),
            $response->getAverageKdRatio(),
            $response->getTotalHeadshotsPercentage(),
            $response->getAverageHeadshotsPercentage(),
            $this->statisticsSegmentFactory->createCollectionFromResponse($response->getSegments())
        );
    }

    public function createFromMatchList(string $playerId, MatchList $matches, StatisticsType $type): Statistics
    {
        if ($matches->isEmpty()) {
            return $this->createEmpty($playerId, $type);
        }

        return new Statistics(
            Id::nullable(),
            $type,
            $playerId,
            $matches->getTotalMatches(),
            $matches->getWins(),
            $matches->getWinRate(),
            $matches->getTotalKdRatio(),
            $matches->getAverageKdRatio(),
            $matches->getTotalHeadshotsPercentage(),
            $matches->getAverageHeadshotsPercentage(),
            $this->statisticsSegmentFactory->createCollectionFromMatchList($matches)
        );
    }

    private function createEmpty(string $playerId, StatisticsType $type): Statistics
    {
        return new Statistics(
            Id::nullable(),
            $type,
            $playerId,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            new StatisticsSegmentsCollection(...[]),
        );
    }
}
