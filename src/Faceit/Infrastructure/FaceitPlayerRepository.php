<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitPlayer;
use App\Faceit\Domain\FaceitPlayerRepository as FaceitPlayerRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class FaceitPlayerRepository implements FaceitPlayerRepositoryInterface
{
    private Connection $connection;
    private FaceitPlayerGameRepository $gameRepository;

    public function __construct(Connection $connection, FaceitPlayerGameRepository $gameRepository)
    {
        $this->connection = $connection;
        $this->gameRepository = $gameRepository;
    }

    public function userExists(string $nickname): bool
    {
        return $this->connection->executeQuery("
            SELECT 1 FROM faceit_players WHERE nickname = :nickname LIMIT 1;
        ", ['nickname' => $nickname])->fetchOne() !== false;
    }

    public function add(FaceitPlayer $player): void
    {
        try {
            $this->connection->beginTransaction();

            $faceitPlayerId = $this->connection->executeQuery("
                INSERT INTO faceit_players(faceit_id, nickname, avatar, faceit_url)
                VALUES (:faceitId, :nickname, :avatar, :faceitUrl)
                RETURNING id;
            ", [
                'faceitId' => $player->getFaceitId(),
                'nickname' => $player->getNickname(),
                'avatar' => $player->getAvatar(),
                'faceitUrl' => $player->getFaceitUrl(),
            ])->fetchOne();

            $this->gameRepository->add($faceitPlayerId, $player->getGame());

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
        }
    }

    public function getByNickname(string $nickname): FaceitPlayer
    {
        $result = $this->connection->executeQuery("
            SELECT
                fp.id AS id,
                fp.faceit_id AS faceit_id,
                fp.nickname AS nickname,
                fp.avatar AS avatar,
                fp.faceit_url AS faceit_url,
                fpg.id AS fpg_id,
                fpg.skill_level AS skill_level,
                fpg.faceit_elo AS faceit_elo,
                fpg.game_player_id AS game_player_id,
                fpg.game_profile_id AS game_profile_id
            FROM faceit_players AS fp
            JOIN faceit_players_games fpg ON fpg.faceit_players_id = fp.id
            WHERE fp.nickname = :nickname
        ", [
            'nickname' => $nickname
        ])->fetchAssociative();

        return FaceitPlayer::createFromRow($result);
    }
}
