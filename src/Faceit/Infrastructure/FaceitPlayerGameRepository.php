<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitPlayerGame;
use Doctrine\DBAL\Connection;

final class FaceitPlayerGameRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(int $faceitPlayerId, FaceitPlayerGame $game): void
    {
        $this->connection->executeQuery("
            INSERT INTO faceit_players_games(faceit_players_id, skill_level, faceit_elo, game_player_id, game_profile_id)
            VALUES (:faceitPlayerId, :skillLevel, :faceitElo, :gamePlayerId, :gameProfileId);
        ", [
            'faceitPlayerId' => $faceitPlayerId,
            'skillLevel' => $game->getSkillLevel(),
            'faceitElo' => $game->getFaceitElo(),
            'gamePlayerId' => $game->getFaceitGamePlayerId(),
            'gameProfileId' => $game->getFaceitGameProfileId(),
        ]);
    }
}
