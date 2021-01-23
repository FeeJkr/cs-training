<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitMatch;
use App\Faceit\Domain\FaceitMatchRepository as FaceitMatchRepositoryInterface;
use App\Faceit\Domain\FaceitPlayer;
use App\Faceit\Domain\Id;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class FaceitMatchRepository implements FaceitMatchRepositoryInterface
{
    private Connection $connection;
    private FaceitMatchTeamRepository $faceitMatchTeamRepository;

    public function __construct(Connection $connection, FaceitMatchTeamRepository $faceitMatchTeamRepository)
    {
        $this->connection = $connection;
        $this->faceitMatchTeamRepository = $faceitMatchTeamRepository;
    }

    public function add(FaceitMatch $match): void
    {
        try {
            $this->connection->beginTransaction();
            $id = $this->connection->executeQuery("
                INSERT INTO faceit_matches
                (faceit_id, game_mode, competition_type, status, started_at, finished_at, faceit_url, map, rounds)
                VALUES
                (:faceitId, :gameMode, :competitionType, :status, :startedAt, :finishedAt, :faceitUrl, :map, :rounds)
                RETURNING id;
            ", [
                'faceitId' => $match->getFaceitId(),
                'gameMode' => $match->getGameMode(),
                'competitionType' => $match->getCompetitionType(),
                'status' => $match->getStatus(),
                'startedAt' => $match->getStartedAt()->format('Y-m-d H:i:s'),
                'finishedAt' => $match->getFinishedAt()->format('Y-m-d H:i:s'),
                'faceitUrl' => $match->getFaceitUrl(),
                'map' => $match->getMap(),
                'rounds' => $match->getRounds(),
            ])->fetchOne();

            $this->faceitMatchTeamRepository->add($id, $match->getFirstTeam());
            $this->faceitMatchTeamRepository->add($id, $match->getSecondTeam());

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
        }
    }

    public function countByPlayer(FaceitPlayer $player): int
    {
        return $this->connection->executeQuery("
            SELECT COUNT(1) FROM faceit_matches fm 
                JOIN faceit_matches_teams fmt ON fm.id = fmt.faceit_matches_id
                JOIN faceit_matches_teams_players fmtp ON fmtp.faceit_matches_teams_id = fmt.id
            WHERE fmtp.faceit_player_id = :faceitPlayerId
        ", [
            'faceitPlayerId' => $player->getFaceitId(),
        ])->fetchOne();
    }

    public function matchExists(string $faceitId): bool
    {
        return $this->connection->executeQuery("
            SELECT 1 FROM faceit_matches WHERE faceit_id = :faceitId LIMIT 1;
        ", ['faceitId' => $faceitId])->fetchOne() === 1;
    }
}
