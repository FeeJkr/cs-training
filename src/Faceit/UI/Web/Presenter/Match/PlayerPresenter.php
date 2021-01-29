<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter\Match;

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
}
