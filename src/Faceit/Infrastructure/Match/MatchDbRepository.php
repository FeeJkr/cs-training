<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Match;

use App\Faceit\Domain\Match\GetByPlayer\MatchElement;
use App\Faceit\Domain\Match\GetByPlayer\MatchList;
use App\Faceit\Domain\Match\GetByPlayer\Scope;
use App\Faceit\Domain\Match\Match;
use App\Faceit\Domain\Match\MatchRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class MatchDbRepository implements MatchRepository
{
    private Connection $connection;
    private TeamDbRepository $teamRepository;

    public function __construct(Connection $connection, TeamDbRepository $teamRepository)
    {
        $this->connection = $connection;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @throws Exception
     */
    public function add(Match $match): void
    {
        try {
            $this->connection->beginTransaction();

            $matchId = $this->connection->executeQuery("
                INSERT INTO faceit_matches
                    (
                        faceit_id, 
                        game_mode, 
                        competition_type, 
                        status, 
                        map, 
                        rounds, 
                        score, 
                        faceit_url, 
                        started_at,
                        finished_at
                    )
                VALUES
                    (
                        :faceitId, 
                        :gameMode, 
                        :competitionType, 
                        :status, 
                        :map, 
                        :rounds, 
                        :score, 
                        :faceitUrl, 
                        :startedAt, 
                        :finishedAt
                    )
                RETURNING id;
            ", [
                'faceitId' => $match->getFaceitId(),
                'gameMode' => $match->getGameMode(),
                'competitionType' => $match->getCompetitionType(),
                'status' => $match->getStatus(),
                'map' => $match->getMap(),
                'rounds' => $match->getRounds(),
                'score' => $match->getScore(),
                'faceitUrl' => $match->getFaceitUrl(),
                'startedAt' => $match->getStartedAt()->format('Y-m-d H:i:s'),
                'finishedAt' => $match->getFinishedAt()->format('Y-m-d H:i:s'),
            ])->fetchOne();

            foreach ($match->getTeams()->toArray() as $team) {
                $this->teamRepository->add($matchId, $team);
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
        }
    }

    /**
     * @throws Exception
     */
    public function exists(string $faceitId): bool
    {
        return $this->connection->executeQuery("
            SELECT 1 FROM faceit_matches WHERE faceit_id = :faceitId LIMIT 1;
        ", ['faceitId' => $faceitId])->fetchOne() !== false;
    }

    /**
     * @throws Exception
     */
    public function getByPlayer(string $playerId, Scope $scope): MatchList
    {
        $where = '';

        if (! $scope->equals(Scope::GLOBAL())) {
            $where = sprintf(" AND fm.finished_at >= '%s'", $scope->getDateByScope()->format('Y-m-d 00:00:00'));
        }

        $rows = $this->connection->executeQuery("
            SELECT
                fmtp.faceit_player_id AS player_id,
                fm.game_mode AS mode,
                fm.map AS map,
                fm.score AS score,
                fmtp.kills AS kills,
                fmtp.assists AS assists,
                fmtp.deaths AS deaths,
                fmtp.headshots AS headshots,
                fmtp.headshots_percentage AS headshots_percentage,
                fmtp.kr_ratio AS kr_ratio,
                fmtp.kd_ratio AS kd_ratio,
                fmtp.triple_kills AS triple_kills,
                fmtp.quadro_kills AS quadro_kills,
                fmtp.penta_kills AS penta_kills,
                fmtp.mvps AS mvps,
                fmt.final_rounds AS rounds,
                fm.faceit_url AS faceit_url,
                fmt.is_win AS is_win,
                fm.finished_at AS finished_at
            FROM faceit_matches fm
            JOIN faceit_matches_teams fmt ON fmt.faceit_matches_id = fm.id
            JOIN faceit_matches_teams_players fmtp ON fmtp.faceit_matches_teams_id = fmt.id
            WHERE fmtp.faceit_player_id = :playerId
        " . $where . ' ORDER BY fm.finished_at DESC', ['playerId' => $playerId])->fetchAllAssociative();

        return new MatchList(
            ...array_map(static fn(array $row): MatchElement => MatchElement::createFromRow($row), $rows)
        );
    }
}
