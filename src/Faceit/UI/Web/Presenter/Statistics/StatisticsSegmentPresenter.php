<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter\Statistics;

use App\Faceit\Domain\Statistics\StatisticsSegment;
use App\Faceit\Domain\Statistics\StatisticsSegmentsCollection;

final class StatisticsSegmentPresenter
{
    public function presentCollection(StatisticsSegmentsCollection $segments): array
    {
        return array_map(fn (StatisticsSegment $segment): array => $this->present($segment), $segments->toArray());
    }

    private function present(StatisticsSegment $segment): array
    {
        return [
            'id' => $segment->getId()->toInt(),
            'type' => $segment->getType(),
            'mode' => $segment->getMode(),
            'label' => $segment->getLabel(),
            'image' => $segment->getImage(),
            'kills' => $segment->getKills(),
            'averageKills' => $segment->getAverageKills(),
            'isGoodAverageKills' => $segment->isGoodAverageKills(),
            'assists' => $segment->getAssists(),
            'averageAssists' => $segment->getAverageAssists(),
            'isGoodAverageAssists' => $segment->isGoodAverageAssists(),
            'deaths' => $segment->getDeaths(),
            'averageDeaths' => $segment->getAverageDeaths(),
            'isGoodAverageDeaths' => $segment->isGoodAverageDeaths(),
            'headshots' => $segment->getHeadshots(),
            'totalHeadshots'=> $segment->getTotalHeadshots(),
            'averageHeadshots' => $segment->getAverageHeadshots(),
            'isGoodAverageHeadshots' => $segment->isGoodAverageHeadshots(),
            'headshotsPerMatch' => $segment->getHeadshotsPerMatch(),
            'krRatio' => $segment->getKrRatio(),
            'averageKrRatio' => $segment->getAverageKrRatio(),
            'isGoodAverageKrRatio' => $segment->isGoodAverageKrRatio(),
            'kdRatio' => $segment->getKdRatio(),
            'averageKdRatio' => $segment->getAverageKdRatio(),
            'isGoodAverageKdRatio' => $segment->isGoodAverageKdRatio(),
            'tripleKills' => $segment->getTripleKills(),
            'quadroKills' => $segment->getQuadroKills(),
            'pentaKills' => $segment->getPentaKills(),
            'averageTripleKills' => $segment->getAverageTripleKills(),
            'averageQuadroKills' => $segment->getAverageQuadroKills(),
            'averagePentaKills' => $segment->getAveragePentaKills(),
            'mvps' => $segment->getMvps(),
            'averageMvps' => $segment->getAverageMvps(),
            'isGoodAverageMvps' => $segment->isGoodAverageMvps(),
            'matches' => $segment->getMatches(),
            'rounds' => $segment->getRounds(),
            'wins' => $segment->getWins(),
            'winRate' => $segment->getWinRate(),
            'isGoodWinRate' => $segment->isGoodWinRate(),
        ];
    }
}
