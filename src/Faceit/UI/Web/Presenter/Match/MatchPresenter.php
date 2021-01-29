<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter\Match;

use App\Faceit\Domain\Match\Match;
use App\Faceit\Domain\Match\MatchesCollection;

final class MatchPresenter
{
    private TeamPresenter $teamPresenter;

    public function __construct(TeamPresenter $teamPresenter)
    {
        $this->teamPresenter = $teamPresenter;
    }

    public function presentCollection(MatchesCollection $matches): array
    {
        return [
            'today' => $this->presentPeriodCollection($matches->getTodayMatches()),
            'yesterday' => $this->presentPeriodCollection($matches->getYesterdayMatches()),
            'month' => $this->presentPeriodCollection($matches->getMonthMatches()),
            'matches' => array_map(fn(Match $match): array => $this->present($match), $matches->toArray()),
        ];
    }

    private function present(Match $match): array
    {
        return [
            'map' => $match->getMap(),
            'mode' => $match->getGameMode(),
            'score' => $match->getScore(),
            'finishedAt' => $match->getFinishedAt()->format('d-m-Y H:i'),
            'faceitUrl' => $match->getFaceitUrl(),
            'teams' => $this->teamPresenter->presentCollection($match->getTeams()),
        ];
    }

    private function presentPeriodCollection(MatchesCollection $matches): array
    {
        return [
            'total' => $matches->getTotalMatches(),
            'wins' => $matches->getWins(),
            'loses' => $matches->getLoses(),
            'averageKd' => $matches->getAverageKd(),
        ];
    }
}
