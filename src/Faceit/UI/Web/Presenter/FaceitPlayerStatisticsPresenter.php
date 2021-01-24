<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter;

use App\Faceit\Domain\FaceitPlayerStatistics;

final class FaceitPlayerStatisticsPresenter
{
    private FaceitPlayerStatisticsSegmentPresenter $segmentPresenter;

    public function __construct(FaceitPlayerStatisticsSegmentPresenter $segmentPresenter)
    {
        $this->segmentPresenter = $segmentPresenter;
    }

    public function present(FaceitPlayerStatistics $statistics): array
    {
        return [
            'id' => $statistics->getId()->toInt(),
            'matches' => $statistics->getMatches(),
            'wins' => $statistics->getWins(),
            'winRate' => $statistics->getWinRate(),
            'isGoodWinRate' => $statistics->isGoodWinRate(),
            'kdRatio' => $statistics->getKdRatio(),
            'averageKdRatio' => $statistics->getAverageKdRatio(),
            'isGoodAverageKdRatio' => $statistics->isGoodAverageKdRatio(),
            'headshots' => $statistics->getHeadshots(),
            'averageHeadshots' => $statistics->getAverageHeadshots(),
            'isGoodAverageHeadshots' => $statistics->isGoodAverageHeadshots(),
            'segments' => $this->segmentPresenter->present($statistics->getSegmentsCollection()),
        ];
    }
}
