<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use App\Faceit\Domain\Contract\Match\MatchPlayerResponse;
use App\Faceit\Domain\Contract\Match\MatchPlayersCollectionResponse;
use App\Faceit\Domain\Id;

final class PlayerFactory
{
    public function createCollectionFromResponse(MatchPlayersCollectionResponse $response): PlayersCollection
    {
        $players = [];

        foreach ($response->toArray() as $player) {
            $players[] = $this->createFromResponse($player);
        }

        return new PlayersCollection(...$players);
    }

    public function createFromResponse(MatchPlayerResponse $response): Player
    {
        return new Player(
            Id::nullable(),
            $response->getPlayerId(),
            $response->getNickname(),
            $response->getKills(),
            $response->getDeaths(),
            $response->getAssists(),
            $response->getHeadshots(),
            $response->getHeadshotsPercentage(),
            $response->getTripleKills(),
            $response->getQuadroKills(),
            $response->getPentaKills(),
            $response->getMvps(),
            $response->getKdRatio(),
            $response->getKrRatio()
        );
    }

    public function createFromRow(array $row): Player
    {
        return new Player(
            Id::fromInt($row['player_id']),
            $row['faceit_id'],
            $row['nickname'],
            $row['kills'],
            $row['deaths'],
            $row['assists'],
            $row['headshots'],
            $row['player_headshots_percentage'],
            $row['triple_kills'],
            $row['quadro_kills'],
            $row['penta_kills'],
            $row['mvps'],
            $row['kd_ratio'],
            $row['kr_ratio']
        );
    }
}
