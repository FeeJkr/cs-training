<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api\Presenter\Player;

use App\Faceit\Domain\Player\GetAll\PlayerElement;
use App\Faceit\Domain\Player\GetAll\PlayerList;

final class PlayerPresenter
{
    public function presentCollection(PlayerList $playerList): array
    {
        return array_map(fn(PlayerElement $element): array => $this->present($element), $playerList->toArray());
    }

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
