<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter\Player;

use App\Faceit\Domain\Player\Player;

final class PlayerPresenter
{
    public function present(Player $player): array
    {
        return [
            'id' => $player->getId()->toInt(),
            'skill' => [
                'level' => $player->getSkillLevel(),
                'elo' => $player->getFaceitElo(),
                'eloPercentageToNextLevel' => $player->getEloPercentageToNextLevel(),
                'eloToNextLevel' => $player->getEloToNextLevel(),
                'eloToPrevisionLevel' => $player->getEloToPrevisionLevel(),
            ],
            'faceitId' => $player->getFaceitId(),
            'nickname' => $player->getNickname(),
            'avatar' => $player->getAvatar(),
            'faceitUrl' => $player->getFaceitUrl(),
        ];
    }
}
