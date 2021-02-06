<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api\Presenter\Statistics;

use App\Faceit\Domain\Statistics\Statistics;
use App\Faceit\Domain\Statistics\StatisticsCollection;

final class StatisticsPresenter
{
    public function __construct(private StatisticsSegmentPresenter $segmentPresenter){}

    public function presentCollection(StatisticsCollection $statistics): array
    {
        $data = [];

        foreach ($statistics->toArray() as $statistic) {
            $data[$statistic->getType()->getValue()] = $this->present($statistic);
        }

        return $data;
    }

    private function present(Statistics $statistics): array
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
            'segments' => $this->segmentPresenter->presentCollection($statistics->getSegments()),
        ];
    }
}
