<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api\Presenter\Match;

use App\Faceit\Domain\Match\GetByPlayer\MatchElement;
use App\Faceit\Domain\Match\GetByPlayer\MatchList;
use Exception;

final class MatchPresenter
{
    public function __construct(private TeamPresenter $teamPresenter, private PlayerPresenter $playerPresenter){}

    public function presentCollection(MatchList $matches, string $faceitId): array
    {
        return [
            'today' => $this->presentPeriodCollection($matches->getTodayMatches(), $faceitId),
            'yesterday' => $this->presentPeriodCollection($matches->getYesterdayMatches(), $faceitId),
            'month' => $this->presentPeriodCollection($matches->getMonthMatches(), $faceitId),
            'matches' => array_map(
                fn(MatchElement $match): array => $this->present($match, $faceitId), $matches->toArray()
            ),
        ];
    }

    /**
     * @throws Exception
     */
    private function present(MatchElement $match, string $faceitId): array
    {
        return [
            'id' => $match->getId(),
            'faceitId' => $match->getFaceitId(),
            'requestedPlayer' => $this->playerPresenter->presentElement($match->getRequestedPlayer($faceitId)),
            'teams' => $this->teamPresenter->presentListCollection($match->getTeams()),
            'mode' => $match->getMode(),
            'map' => $match->getMap(),
            'score' => $match->getScore(),
            'faceitUrl' => $match->getFaceitUrl(),
            'finishedAt' => $match->getFinishedAt()->format('d-m-Y H:i'),
            'isWin' => $match->isWin($faceitId),
        ];
    }

    private function presentPeriodCollection(MatchList $matches, string $faceitId): array
    {
        return [
            'total' => $matches->getTotalMatches(),
            'wins' => $matches->getWins($faceitId),
            'loses' => $matches->getLoses($faceitId),
            'averageKd' => $matches->getAverageKdRatio($faceitId),
        ];
    }
}
