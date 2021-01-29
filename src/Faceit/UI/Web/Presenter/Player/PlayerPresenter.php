<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter\Player;

use App\Faceit\Domain\Player\GetAll\PlayerElement;

final class PlayerPresenter
{
    public function present(PlayerElement $player): array
    {
        return [
            'id' => $player->getId(),
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
