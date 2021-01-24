<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitMatch;
use App\Faceit\Domain\FaceitMatchRepository as FaceitMatchRepositoryInterface;
use App\Faceit\Domain\FaceitPlayer;
use App\Faceit\Domain\FaceitPlayerMatchResultsCollection;
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

    public function getByPlayer(FaceitPlayer $player): FaceitPlayerMatchResultsCollection
    {
        $rows = $this->connection->executeQuery("
            SELECT
                fm.id AS match_id,
                fmt.name AS team,
                fm.map AS map,
                fm.game_mode AS mode,
                fmtp.kills AS kills,
                fmtp.deaths AS deaths,
                fmtp.assists AS assists,
                fmtp.headshots AS headshots,
                fmtp.headshots_percentage AS headshots_percentage,
                fmtp.triple_kills AS triple_kills,
                fmtp.quadro_kills AS quadro_kills,
                fmtp.penta_kills AS penta_kills,
                fmtp.mvps AS mvps,
                fmtp.kd_ratio AS kd_ratio,
                fmtp.kr_ratio AS kr_ratio,
                fmt.is_win AS is_win
            FROM faceit_matches fm
            JOIN faceit_matches_teams fmt ON fmt.faceit_matches_id = fm.id
            JOIN faceit_matches_teams_players fmtp ON fmtp.faceit_matches_teams_id = fmt.id
            WHERE fmtp.nickname = :nickname
            ORDER BY fm.finished_at DESC;
        ", ['nickname' => $player->getNickname()])->fetchAllAssociative();

        $rows = array_map(function (array $row): array {
            $score = $this->connection->executeQuery("
                SELECT final_rounds FROM faceit_matches_teams WHERE faceit_matches_id = :matchId
            ", ['matchId' => $row['match_id']])->fetchAllAssociative();

            return \array_merge($row, ['score' => $score[0]['final_rounds'] . ' / ' . $score[1]['final_rounds']]);
        }, $rows);

        return FaceitPlayerMatchResultsCollection::createFromRows($rows);
    }
}
