<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter;

use App\Faceit\Domain\Match\GetByPlayer\MatchList;
use App\Faceit\Domain\Match\MatchesCollection;
use App\Faceit\Domain\Player\GetAll\PlayerElement;
use App\Faceit\Domain\Player\Player;
use App\Faceit\Domain\Statistics\StatisticsCollection;
use App\Faceit\UI\Web\Presenter\Match\MatchPresenter;
use App\Faceit\UI\Web\Presenter\Player\PlayerPresenter;
use App\Faceit\UI\Web\Presenter\Statistics\StatisticsPresenter;

final class DashboardPresenter
{
    private PlayerPresenter $playerPresenter;
    private MatchPresenter $matchPresenter;
    private StatisticsPresenter $statisticsPresenter;

    public function __construct(
        PlayerPresenter $playerPresenter,
        MatchPresenter $matchPresenter,
        StatisticsPresenter $statisticsPresenter
    ) {
        $this->playerPresenter = $playerPresenter;
        $this->matchPresenter = $matchPresenter;
        $this->statisticsPresenter = $statisticsPresenter;
    }

    public function present(
        PlayerElement $player,
        MatchList $matches,
        StatisticsCollection $statistics
    ): array {
        return [
            'player' => $this->playerPresenter->present($player),
            'matches' => $this->matchPresenter->presentCollection($matches),
            'statistics' => $this->statisticsPresenter->presentCollection($statistics),
        ];
    }
}
