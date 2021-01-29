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

    public function createCollectionFromRows(array $rows): StatisticsSegmentsCollection
    {
        $segments = [];

        foreach ($rows as $row) {
            $segments[] = $this->createFromRow($row);
        }

        return new StatisticsSegmentsCollection(...$segments);
    }

    private function createFromRow(array $row): StatisticsSegment
    {
        return new StatisticsSegment(
            Id::fromInt($row['id']),
            $row['type'],
            $row['mode'],
            $row['label'],
            $row['image'],
            (int) $row['kills'],
            (float) $row['average_kills'],
            (int) $row['assists'],
            (float) $row['average_assists'],
            (int) $row['deaths'],
            (float) $row['average_deaths'],
            (int) $row['headshots'],
            (int) $row['total_headshots'],
            (float) $row['average_headshots'],
            (float) $row['headshots_per_match'],
            (float) $row['kr_ratio'],
            (float) $row['average_kr_ratio'],
            (float) $row['kd_ratio'],
            (float) $row['average_kd_ratio'],
            (int) $row['triple_kills'],
            (int) $row['quadro_kills'],
            (int) $row['penta_kills'],
            (float) $row['average_triple_kills'],
            (float) $row['average_quadro_kills'],
            (float) $row['average_penta_kills'],
            (int) $row['mvps'],
            (float) $row['average_mvps'],
            (int) $row['matches'],
            (int) $row['rounds'],
            (int) $row['wins'],
            (float) $row['win_rate']
        );
    }
}
