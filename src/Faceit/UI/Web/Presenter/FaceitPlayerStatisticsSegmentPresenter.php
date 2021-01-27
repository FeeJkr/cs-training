<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter;

use App\Faceit\Domain\FaceitPlayerStatisticsSegment;
use App\Faceit\Domain\FaceitPlayerStatisticsSegmentsCollection;

final class FaceitPlayerStatisticsSegmentPresenter
{
    public function present(
        FaceitPlayerStatisticsSegmentsCollection $segmentsCollection,
        FaceitPlayerStatisticsSegmentsCollection $monthSegments
    ): array {
        return array_map(function (FaceitPlayerStatisticsSegment $segment) use ($monthSegments): array {
            $monthSegment = null;

            foreach ($monthSegments->getSegments() as $row) {
                if ($row->getLabel() === $segment->getLabel()) {
                    $monthSegment = $row;
                }
            }

            if ($monthSegment === null) {
                return [
                    'total' => $this->getArray($segment)
                ];
            }

            return [
                'total' => $this->getArray($segment),
                'month' => $this->getArray($monthSegment),
            ];

        }, $segmentsCollection->getSegments());
    }

    private function getArray(FaceitPlayerStatisticsSegment $segment): array
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
