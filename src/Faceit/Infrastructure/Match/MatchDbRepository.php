<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Match;

use App\Faceit\Domain\Match\GetByPlayer\MatchElement;
use App\Faceit\Domain\Match\GetByPlayer\MatchList;
use App\Faceit\Domain\Match\GetByPlayer\Scope;
use App\Faceit\Domain\Match\FaceitMatch;
use App\Faceit\Domain\Match\MatchRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class MatchDbRepository implements MatchRepository
{
    public function __construct(private Connection $connection, private TeamDbRepository $teamRepository){}

    /**
     * @throws Exception
     */
    public function add(FaceitMatch $match): void
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
                fm.id AS match_id,
                fm.faceit_id AS match_faceit_id,
                fm.game_mode AS match_game_mode,
                fm.map AS match_map,
                fm.score AS match_score,
                fm.faceit_url AS match_url,
                fm.finished_at AS match_finished_at,
                fmt.id AS team_id,
                fmt.name AS team_name,
                fmt.is_win AS team_is_win,
                fmt.first_half_rounds AS team_first_half_rounds,
                fmt.second_half_rounds AS team_second_half_rounds,
                fmt.overtime_rounds AS team_overtime_rounds,
                fmt.final_rounds AS team_final_rounds,
                fmtp.faceit_player_id AS player_faceit_id,
                fmtp.nickname AS player_nickname,
                fmtp.kills AS player_kills,
                fmtp.assists AS player_assists,
                fmtp.deaths AS player_deaths,
                fmtp.headshots AS player_headshots,
                fmtp.headshots_percentage AS player_headshots_percentage,
                fmtp.triple_kills AS player_triple_kills,
                fmtp.quadro_kills AS player_quadro_kills,
                fmtp.penta_kills AS player_penta_kills,
                fmtp.mvps AS player_mvps,
                fmtp.kd_ratio AS player_kd_ratio,
                fmtp.kr_ratio AS player_kr_ratio
            FROM faceit_matches fm
            JOIN faceit_matches_teams fmt ON fmt.faceit_matches_id = fm.id
            JOIN faceit_matches_teams_players fmtp ON fmtp.faceit_matches_teams_id = fmt.id
            WHERE fm.id IN (
                SELECT faceit_matches.id FROM faceit_matches
                JOIN faceit_matches_teams ON faceit_matches_teams.faceit_matches_id = faceit_matches.id
                JOIN faceit_matches_teams_players ON faceit_matches_teams_players.faceit_matches_teams_id = faceit_matches_teams.id
                WHERE faceit_matches_teams_players.faceit_player_id = :playerId
            )
        " . $where . ' ORDER BY fm.finished_at DESC', ['playerId' => $playerId])->fetchAllAssociative();

        $matches = [];

        foreach ($rows as $row) {
            $matches[$row['match_id']][] = $row;
        }

        foreach ($matches as $match) {
            foreach ($match as $key => $player) {
                $matches[$player['match_id']]['teams'][$player['team_id']][] = $player;
                unset($matches[$player['match_id']][$key]);
            }
        }

        return new MatchList(
            ...array_map(static fn(array $row): MatchElement => MatchElement::createFromRow($row), $matches)
        );
    }
}
