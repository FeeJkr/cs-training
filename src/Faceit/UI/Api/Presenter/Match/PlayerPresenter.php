<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api\Presenter\Match;

use App\Faceit\Domain\Match\GetByPlayer\PlayerElement;
use App\Faceit\Domain\Match\GetByPlayer\PlayerList;
use App\Faceit\Domain\Match\Player;
use App\Faceit\Domain\Match\PlayersCollection;

final class PlayerPresenter
{
    public function presentCollection(PlayersCollection $players): array
    {
        return array_map(fn(Player $player): array => $this->present($player), $players->toArray());
    }

    private function present(Player $player): array
    {
        return [
            'kills' => $player->getKills(),
            'deaths' => $player->getDeaths(),
            'assists' => $player->getAssists(),
            'headshots' => $player->getHeadshots(),
            'headshotsPercentage' => $player->getHeadshotsPercentage(),
            'tripleKills' => $player->getTripleKills(),
            'quadroKills' => $player->getQuadroKills(),
            'pentaKills' => $player->getPentaKills(),
            'mvps' => $player->getMvps(),
            'kdRatio' => $player->getKdRatio(),
            'krRatio' => $player->getKrRatio(),
            'isGoodKrRatio' => $player->isGoodKrRatio(),
            'isGoodKdRatio' => $player->isGoodKdRatio(),
        ];
    }

    public function presentListCollection(PlayerList $players): array
    {
        return array_map(fn(PlayerElement $player): array => $this->presentElement($player), $players->toArray());
    }

    public function presentElement(PlayerElement $player): array
    {
        return [
            'faceitId' => $player->getFaceitId(),
            'nickname' => $player->getNickname(),
            'kills' => $player->getKills(),
            'assists' => $player->getAssists(),
            'deaths' => $player->getDeaths(),
            'headshots' => $player->getHeadshots(),
            'headshotsPercentage' => $player->getHeadshotsPercentage(),
            'tripleKills' => $player->getTripleKills(),
            'quadroKills' => $player->getQuadroKills(),
            'pentaKills' => $player->getPentaKills(),
            'mvps' => $player->getMvps(),
            'kdRatio' => $player->getKdRatio(),
            'krRatio' => $player->getKrRatio(),
            'isGoodKdRatio' => $player->isGoodKdRatio(),
            'isGoodKrRatio' => $player->isGoodKrRatio(),
        ];
    }
}
