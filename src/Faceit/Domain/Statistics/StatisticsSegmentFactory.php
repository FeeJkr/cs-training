<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

use App\Faceit\Domain\Contract\Statistics\SegmentResponse;
use App\Faceit\Domain\Contract\Statistics\SegmentsCollectionResponse;
use App\Faceit\Domain\Id;
use App\Faceit\Domain\Match\GetByPlayer\MatchList;

final class StatisticsSegmentFactory
{
    private const IMAGE = 'https://cdn.faceit.com/static/stats_assets/csgo/maps/200x125/csgo-votable-maps-%s-200x125.jpg';

    public function createCollectionFromResponse(SegmentsCollectionResponse $response): StatisticsSegmentsCollection
    {
        $segments = [];

        foreach ($response->toArray() as $segment) {
            $segments[] = $this->createFromResponse($segment);
        }

        return new StatisticsSegmentsCollection(...$segments);
    }

    public function createFromResponse(SegmentResponse $response): StatisticsSegment
    {
        return new StatisticsSegment(
            Id::nullable(),
            $response->getType(),
            $response->getMode(),
            $response->getLabel(),
            $response->getImage(),
            $response->getKills(),
            $response->getAverageKills(),
            $response->getAssists(),
            $response->getAverageAssists(),
            $response->getDeaths(),
            $response->getAverageDeaths(),
            $response->getHeadshots(),
            $response->getTotalHeadshotsPercentage(),
            $response->getAverageHeadshotsPercentage(),
            $response->getHeadshotsPerMatch(),
            $response->getTotalKrRatio(),
            $response->getAverageKrRatio(),
            $response->getTotalKdRatio(),
            $response->getAverageKdRatio(),
            $response->getTripleKills(),
            $response->getQuadroKills(),
            $response->getPentaKills(),
            $response->getAverageTripleKills(),
            $response->getAverageQuadroKills(),
            $response->getAveragePentaKills(),
            $response->getMvps(),
            $response->getAverageMvps(),
            $response->getMatches(),
            $response->getRounds(),
            $response->getWins(),
            $response->getWinRate()
        );
    }

    public function createCollectionFromMatchList(MatchList $matches): StatisticsSegmentsCollection
    {
        $newSegments = [];
        $segments = $matches->groupByMap();

        foreach ($segments as $segment) {
            $newSegments[] = $this->createFromMatchList($segment);
        }

        return new StatisticsSegmentsCollection(...$newSegments);
    }

    private function createFromMatchList(MatchList $matchList): StatisticsSegment
    {
        return new StatisticsSegment(
            Id::nullable(),
            'Map',
            $matchList->first()->getMode(),
            $matchList->first()->getMap(),
            sprintf(self::IMAGE, $matchList->first()->getMap()),
            $matchList->getKills(),
            $matchList->getAverageKills(),
            $matchList->getAssists(),
            $matchList->getAverageAssists(),
            $matchList->getDeaths(),
            $matchList->getAverageDeaths(),
            $matchList->getTotalHeadshots(),
            (int) $matchList->getTotalHeadshotsPercentage(),
            $matchList->getAverageHeadshotsPercentage(),
            $matchList->getHeadshotsPerMatch(),
            $matchList->getTotalKrRatio(),
            $matchList->getAverageKrRatio(),
            $matchList->getTotalKdRatio(),
            $matchList->getAverageKdRatio(),
            $matchList->getTripleKills(),
            $matchList->getQuadroKills(),
            $matchList->getPentaKills(),
            $matchList->getAverageTripleKills(),
            $matchList->getAverageQuadroKills(),
            $matchList->getAveragePentaKills(),
            $matchList->getTotalMvps(),
            $matchList->getAverageMvps(),
            $matchList->getTotalMatches(),
            $matchList->getTotalRounds(),
            $matchList->getWins(),
            $matchList->getWinRate()
        );
    }
}