<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api\Presenter\Match;

use App\Faceit\Domain\Match\GetByPlayer\MatchElement;
use App\Faceit\Domain\Match\GetByPlayer\MatchList;

final class MatchPresenter
{
    public function __construct(private TeamPresenter $teamPresenter){}

    public function presentCollection(MatchList $matches): array
    {
        return [
            'today' => $this->presentPeriodCollection($matches->getTodayMatches()),
            'yesterday' => $this->presentPeriodCollection($matches->getYesterdayMatches()),
            'month' => $this->presentPeriodCollection($matches->getMonthMatches()),
            'matches' => array_map(fn(MatchElement $match): array => $this->present($match), $matches->toArray()),
        ];
    }

    private function present(MatchElement $match): array
    {
        return [
            'map' => $match->getMap(),
            'mode' => $match->getMode(),
            'score' => $match->getScore(),
            'finishedAt' => $match->getFinishedAt()->format('d-m-Y H:i'),
            'faceitUrl' => $match->getFaceitUrl(),
            'kills' => $match->getKills(),
            'assists' => $match->getAssists(),
            'deaths' => $match->getDeaths(),
            'headshots' => $match->getHeadshots(),
            'headshotsPercentage' => $match->getHeadshotsPercentage(),
            'tripleKills' => $match->getTripleKills(),
            'quadroKills' => $match->getQuadroKills(),
            'pentaKills' => $match->getPentaKills(),
            'mvps' => $match->getMvps(),
            'kdRatio' => $match->getKdRatio(),
            'krRatio' => $match->getKrRatio(),
            'isWin' => $match->isWin(),
            'isGoodKdRatio' => $match->isGoodKdRatio(),
            'isGoodKrRatio' => $match->isGoodKrRatio(),
        ];
    }

    private function presentPeriodCollection(MatchList $matches): array
    {
        return [
            'total' => $matches->getTotalMatches(),
            'wins' => $matches->getWins(),
            'loses' => $matches->getLoses(),
            'averageKd' => $matches->getAverageKdRatio(),
        ];
    }
}
