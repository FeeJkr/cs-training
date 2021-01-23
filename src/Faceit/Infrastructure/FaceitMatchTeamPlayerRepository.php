<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitMatchTeamPlayer;
use Doctrine\DBAL\Connection;

final class FaceitMatchTeamPlayerRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(int $faceitMatchTeamId, FaceitMatchTeamPlayer $player): void
    {
        $this->connection->executeQuery("
            INSERT INTO faceit_matches_teams_players
            (
                faceit_matches_teams_id,
                faceit_player_id,
                nickname,
                kills,
                deaths, 
                assists,
                headshots,
                headshots_percentage,
                triple_kills,
                quadro_kills,
                penta_kills,
                mvps,   
                kd_ratio,
                kr_ratio
            ) VALUES (
                :faceitMatchTeamId,
                :faceitPlayerId,
                :nickname,
                :kills,
                :deaths,
                :assists,
                :headshots,
                :headshotsPercentage,
                :tripleKills,
                :quadroKills,
                :pentaKills,
                :mvps,
                :kdRatio,
                :krRatio
            )
        ", [
            'faceitMatchTeamId' => $faceitMatchTeamId,
            'faceitPlayerId' => $player->getFaceitId(),
            'nickname' => $player->getNickname(),
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
        ]);
    }
}
