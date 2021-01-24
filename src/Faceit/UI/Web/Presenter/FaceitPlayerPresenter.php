<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter;

use App\Faceit\Domain\FaceitPlayer;

final class FaceitPlayerPresenter
{
    private FaceitPlayerStatisticsPresenter $statisticsPresenter;

    public function __construct(FaceitPlayerStatisticsPresenter $statisticsPresenter)
    {
        $this->statisticsPresenter = $statisticsPresenter;
    }

    public function present(FaceitPlayer $player): array
    {
        return [
            'id' => $player->getId()->toInt(),
            'game' => [
                'skillLevel' => $player->getGame()->getSkillLevel(),
                'elo' => $player->getGame()->getFaceitElo(),
            ],
            'faceitId' => $player->getFaceitId(),
            'nickname' => $player->getNickname(),
            'avatar' => $player->getAvatar(),
            'faceitUrl' => $player->getFaceitUrl(),
            'statistics' => $this->statisticsPresenter->present($player->getStatistics()),
        ];
    }
}
