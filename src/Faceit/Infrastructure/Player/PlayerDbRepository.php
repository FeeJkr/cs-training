<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Player;

use App\Faceit\Domain\Player\GetAll\PlayerElement;
use App\Faceit\Domain\Player\GetAll\PlayerList;
use App\Faceit\Domain\Player\Player;
use App\Faceit\Domain\Player\PlayerRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class PlayerDbRepository implements PlayerRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function userExists(string $nickname): bool
    {
        return $this->connection->executeQuery("
            SELECT 1 FROM faceit_players WHERE nickname = :nickname LIMIT 1;
        ", ['nickname' => $nickname])->fetchOne() !== false;
    }

    /**
     * @throws Exception
     */
    public function add(Player $player): void
    {
        try {
            $this->connection->beginTransaction();

            $this->connection->executeQuery("
                INSERT INTO faceit_players 
                    (faceit_id, nickname, avatar, skill_level, faceit_elo, faceit_url)
                VALUES 
                     (:faceitId, :nickname, :avatar, :skillLevel, :faceitElo, :faceitUrl)
            ", [
                'faceitId' => $player->getFaceitId(),
                'nickname' => $player->getNickname(),
                'avatar' => $player->getAvatar(),
                'skillLevel' => $player->getSkillLevel(),
                'faceitElo' => $player->getFaceitElo(),
                'faceitUrl' => $player->getFaceitUrl(),
            ]);

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
        }
    }

    /**
     * @throws Exception
     */
    public function getAll(): PlayerList
    {
        $rows = $this->connection->executeQuery("
            SELECT
                id,
                faceit_id,
                nickname,
                avatar,
                skill_level,
                faceit_elo,
                faceit_url
            FROM faceit_players;
        ")->fetchAllAssociative();

        return new PlayerList(
            ...array_map(static fn(array $row): PlayerElement => PlayerElement::createFromRow($row), $rows)
        );
    }

    /**
     * @throws Exception
     */
    public function getByNickname(string $nickname): PlayerElement
    {
        $row = $this->connection->executeQuery("
            SELECT
                id,
                faceit_id,
                nickname,
                avatar,
                skill_level,
                faceit_elo,
                faceit_url
            FROM faceit_players;
        ")->fetchAssociative();

        return PlayerElement::createFromRow($row);
    }
}
