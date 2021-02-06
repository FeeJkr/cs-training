<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api\Presenter\Match;

use App\Faceit\Domain\Match\GetByPlayer\TeamElement;
use App\Faceit\Domain\Match\GetByPlayer\TeamList;
use App\Faceit\Domain\Match\Team;
use App\Faceit\Domain\Match\TeamsCollection;

final class TeamPresenter
{
    public function __construct(private PlayerPresenter $playerPresenter){}

    public function presentCollection(TeamsCollection $teams): array
    {
        return array_map(fn(Team $team): array => $this->present($team), $teams->toArray());
    }

    private function present(Team $team): array
    {
        return [
            'name' => $team->getName(),
            'isWin' => $team->isWinner(),
            'players' => $this->playerPresenter->presentCollection($team->getPlayers()),
        ];
    }

    public function presentListCollection(TeamList $teams): array
    {
        return array_map(fn(TeamElement $team): array => $this->presentElement($team), $teams->toArray());
    }

    private function presentElement(TeamElement $team): array
    {
        return [
            'id' => $team->getId(),
            'name' => $team->getName(),
            'players' => $this->playerPresenter->presentListCollection($team->getPlayers()),
            'firstHalfRounds' => $team->getFirstHalfRounds(),
            'secondHalfRounds' => $team->getSecondHalfRounds(),
            'overtimeRounds' => $team->getOvertimeRounds(),
            'finalRounds' => $team->getFinalRounds(),
            'isWin' => $team->isWinner(),
        ];
    }
}
