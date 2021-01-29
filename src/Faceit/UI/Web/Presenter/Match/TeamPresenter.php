<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter\Match;

use App\Faceit\Domain\Match\Team;
use App\Faceit\Domain\Match\TeamsCollection;

final class TeamPresenter
{
    private PlayerPresenter $playerPresenter;

    public function __construct(PlayerPresenter $playerPresenter)
    {
        $this->playerPresenter = $playerPresenter;
    }

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
}
