<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitPlayer;
use App\Faceit\Domain\FaceitPlayerGame;
use App\Faceit\Domain\FaceitPlayerRepository as FaceitPlayerRepositoryInterface;
use App\Faceit\Domain\FaceitPlayerStatistics;
use App\Faceit\Domain\FaceitPlayerStatisticsSegment;
use App\Faceit\Domain\FaceitPlayerStatisticsSegmentsCollection;
use App\Faceit\Domain\Id;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class FaceitPlayerRepository implements FaceitPlayerRepositoryInterface
{
    private Connection $connection;
    private FaceitPlayerGameRepository $gameRepository;
    private FaceitPlayerStatisticsSegmentRepository $faceitPlayerStatisticsSegmentRepository;

    public function __construct(
        Connection $connection,
        FaceitPlayerGameRepository $gameRepository,
        FaceitPlayerStatisticsSegmentRepository $faceitPlayerStatisticsSegmentRepository
    ) {
        $this->connection = $connection;
        $this->gameRepository = $gameRepository;
        $this->faceitPlayerStatisticsSegmentRepository = $faceitPlayerStatisticsSegmentRepository;
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
                fpg.game_profile_id AS game_profile_id,
                fps.id AS statistics_id,
                fps.matches AS statistics_matches,
                fps.wins AS statistics_wins,
                fps.win_rate AS statistics_win_rate,
                fps.kd_ratio AS statistics_kd_ratio,
                fps.average_kd_ratio AS statistics_average_kd_ratio,
                fps.headshots AS statistics_headshots,
                fps.average_headshots AS statistics_average_headshots
            FROM faceit_players AS fp
            LEFT JOIN faceit_players_games fpg ON fpg.faceit_players_id = fp.id
            LEFT JOIN faceit_players_statistics fps ON fps.faceit_players_id = fp.id
            WHERE fp.nickname = :nickname
        ", [
            'nickname' => $nickname
        ])->fetchAssociative();

        if ($result['statistics_id'] !== null) {
            $result['segments'] = $this->faceitPlayerStatisticsSegmentRepository->getByStatisticsId(
                $result['statistics_id']
            );
        }

        return FaceitPlayer::createFromRow($result);
    }

    public function addStatistics(Id $playerId, FaceitPlayerStatistics $statistics): void
    {
        try {
            $this->connection->beginTransaction();

            $this->connection->executeQuery("
                DELETE FROM faceit_players_statistics WHERE faceit_players_id = :faceitPlayerId
            ", ['faceitPlayerId' => $playerId->toInt()]);

            $id = $this->connection->executeQuery("
                INSERT INTO faceit_players_statistics
                (
                    matches, 
                    wins, 
                    win_rate, 
                    kd_ratio, 
                    average_kd_ratio, 
                    headshots, 
                    average_headshots, 
                    faceit_players_id
                ) VALUES (
                    :matches,
                    :wins,
                    :winRate,
                    :kdRatio,
                    :averageKdRatio,
                    :headshots,
                    :averageHeadshots,
                    :faceitPlayerId
                ) RETURNING id; 
            ", [
                'matches' => $statistics->getMatches(),
                'wins' => $statistics->getWins(),
                'winRate' => $statistics->getWinRate(),
                'kdRatio' => $statistics->getKdRatio(),
                'averageKdRatio' => $statistics->getAverageKdRatio(),
                'headshots' => $statistics->getHeadshots(),
                'averageHeadshots' => $statistics->getAverageHeadshots(),
                'faceitPlayerId' => $playerId->toInt(),
            ])->fetchOne();

            foreach ($statistics->getSegmentsCollection()->getSegments() as $segment) {
                $this->faceitPlayerStatisticsSegmentRepository->add($id, $segment);
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
        }
    }

    public function getAllPlayersNicknames(): array
    {
        return $this->connection->executeQuery("
            SELECT nickname FROM faceit_players;
        ")->fetchAllAssociative();
    }

    public function addGameInformation(Id $playerId, FaceitPlayerGame $game): void
    {
        $this->gameRepository->add($playerId->toInt(), $game);
    }

    public function updateGameInformation(FaceitPlayer $player): void
    {
        $this->connection->executeQuery("
            UPDATE faceit_players_games SET skill_level = :skillLevel, faceit_elo = :faceitElo WHERE id = :id
        ", [
            'id' => $player->getGame()->getId()->toInt(),
            'skillLevel' => $player->getGame()->getSkillLevel(),
            'faceitElo' => $player->getGame()->getFaceitElo(),
        ]);
    }

    public function updateStatistics(FaceitPlayer $player): void
    {
        try {
            $this->connection->beginTransaction();

            $statisticsId = $player->getStatistics()->getId()->toInt();
            $this->connection->executeQuery("
                UPDATE faceit_players_statistics
                SET 
                    matches = :matches,
                    wins = :wins,
                    win_rate = :winRate,
                    kd_ratio = :kdRatio,
                    average_kd_ratio = :averageKdRatio,
                    headshots = :headshots,
                    average_headshots = :averageHeadshots
                WHERE id = :id; 
            ", [
                'matches' => $player->getStatistics()->getMatches(),
                'wins' => $player->getStatistics()->getWins(),
                'winRate' => $player->getStatistics()->getWinRate(),
                'kdRatio' => $player->getStatistics()->getKdRatio(),
                'averageKdRatio' => $player->getStatistics()->getAverageKdRatio(),
                'headshots' => $player->getStatistics()->getHeadshots(),
                'averageHeadshots' => $player->getStatistics()->getAverageHeadshots(),
                'id' => $statisticsId,
            ]);

            foreach ($player->getStatistics()->getSegmentsCollection()->getSegments() as $segment) {
                $segment->getId()->isNull()
                    ? $this->faceitPlayerStatisticsSegmentRepository->add($statisticsId, $segment)
                    : $this->faceitPlayerStatisticsSegmentRepository->update($segment);
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
        }
    }

    public function getMonthStatistics(FaceitPlayer $player): FaceitPlayerStatistics
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
                fmt.is_win AS is_win,
                fm.finished_at AS finished_at,
                fm.faceit_url AS faceit_url,
                fm.rounds AS rounds
            FROM faceit_matches fm
            JOIN faceit_matches_teams fmt ON fmt.faceit_matches_id = fm.id
            JOIN faceit_matches_teams_players fmtp ON fmtp.faceit_matches_teams_id = fmt.id
            WHERE fmtp.nickname = :nickname AND fm.finished_at >= :date
        ", [
            'nickname' => $player->getNickname(),
            'date' => (new DateTime())->format('Y-m-01 00:00:00'),
        ])->fetchAllAssociative();

        return new FaceitPlayerStatistics(
            Id::nullable(),
            count($rows),
            $this->getWins($rows),
            $this->getWinRate($rows),
            $this->getKdRatio($rows),
            $this->getAverageKdRatio($rows),
            $this->getHeadshots($rows),
            $this->getAverageHeadshots($rows),
            $this->getSegments($rows, $player->getStatistics()->getSegmentsCollection())
        );
    }

    public function getWins(array $rows): int
    {
        $wins = 0;

        foreach ($rows as $match) {
            if ($match['is_win']) {
                $wins++;
            }
        }

        return $wins;
    }

    public function getWinRate(array $rows): float
    {
        return round(($this->getWins($rows) / count($rows)) * 100);
    }

    public function getKdRatio(array $rows): float
    {
        $kdRatio = 0;

        foreach ($rows as $match) {
            $kdRatio += $match['kd_ratio'];
        }

        return $kdRatio;
    }

    public function getAverageKdRatio(array $rows): float
    {
        return round($this->getKdRatio($rows) / count($rows), 2);
    }

    public function getHeadshots(array $rows): int
    {
        $headshots = 0;

        foreach ($rows as $match) {
            $headshots += $match['headshots'];
        }

        return $headshots;
    }

    public function getAverageHeadshots(array $rows): float
    {
        return round(($this->getHeadshots($rows) / $this->getKills($rows)) * 100);
    }

    private function getSegments(
        array $rows,
        FaceitPlayerStatisticsSegmentsCollection $collection
    ): FaceitPlayerStatisticsSegmentsCollection {
        $grouped = [];

        foreach ($collection->getSegments() as $segment) {
            foreach ($rows as $row) {
                if ($row['map'] === $segment->getLabel()) {
                    $grouped[$row['map']][] = $row;
                }
            }
        }

        $segments = [];

        foreach ($grouped as $segment) {
            $segments[] = $this->getSegment($segment);
        }

        return new FaceitPlayerStatisticsSegmentsCollection(...$segments);
    }

    private function getSegment(array $rows): FaceitPlayerStatisticsSegment
    {
        return new FaceitPlayerStatisticsSegment(
            Id::nullable(),
            'Map',
            '5v5',
            $rows[0]['map'],
            'null',
            $this->getKills($rows),
            $this->getAverageKills($rows),
            $this->getAssists($rows),
            $this->getAverageAssists($rows),
            $this->getDeaths($rows),
            $this->averageDeaths($rows),
            $this->getHeadshots($rows),
            0,
            $this->getAverageHeadshots($rows),
            $this->getHeadshotsPerMatch($rows),
            $this->getKrRatio($rows),
            $this->averageKrRatio($rows),
            $this->getKdRatio($rows),
            $this->getAverageKdRatio($rows),
            $this->getTripleKills($rows),
            $this->getQuadroKills($rows),
            $this->getPentaKills($rows),
            $this->getAverageTripleKills($rows),
            $this->getAverageQuadroKills($rows),
            $this->getAveragePentaKills($rows),
            $this->getMvps($rows),
            $this->getAverageMvps($rows),
            count($rows),
            $this->getRounds($rows),
            $this->getWins($rows),
            $this->getWinRate($rows)
        );
    }

    private function getRounds(array $rows): int
    {
        $rounds = 0;

        foreach ($rows as $match) {
            $rounds += $match['rounds'];
        }

        return $rounds;
    }

    private function getKills(array $rows): int
    {
        $kills = 0;

        foreach ($rows as $match) {
            $kills += $match['kills'];
        }

        return $kills;
    }

    private function getAverageKills(array $rows): float
    {
        return round($this->getKills($rows) / count($rows), 2);
    }

    private function getAssists(array $rows): int
    {
        $assists = 0;

        foreach ($rows as $match) {
            $assists += $match['assists'];
        }

        return $assists;
    }

    private function getAverageAssists(array $rows): float
    {
        return round($this->getAssists($rows) / count($rows), 2);
    }

    private function getDeaths(array $rows): int
    {
        $deaths = 0;

        foreach ($rows as $match) {
            $deaths += $match['deaths'];
        }

        return $deaths;
    }

    private function averageDeaths(array $rows): float
    {
        return round($this->getDeaths($rows) / count($rows), 2);
    }

    private function getHeadshotsPerMatch(array $rows): int
    {
        return (int) round($this->getHeadshots($rows) / count($rows));
    }

    private function getKrRatio(array $rows): float
    {
        $krRatio = 0;

        foreach ($rows as $match) {
            $krRatio += $match['kr_ratio'];
        }

        return $krRatio;
    }

    private function averageKrRatio(array $rows): float
    {
        return round($this->getKrRatio($rows) / count($rows), 2);
    }

    private function getTripleKills(array $rows): int
    {
        $counter = 0;

        foreach ($rows as $match) {
            $counter += $match['triple_kills'];
        }

        return $counter;
    }

    private function getQuadroKills(array $rows): int
    {
        $counter = 0;

        foreach ($rows as $match) {
            $counter += $match['quadro_kills'];
        }

        return $counter;
    }

    private function getPentaKills(array $rows): int
    {
        $counter = 0;

        foreach ($rows as $match) {
            $counter += $match['penta_kills'];
        }

        return $counter;
    }

    private function getAverageTripleKills(array $rows): float
    {
        return round($this->getTripleKills($rows) / count($rows), 2);
    }

    private function getAverageQuadroKills(array $rows): float
    {
        return round($this->getQuadroKills($rows) / count($rows), 2);
    }

    private function getAveragePentaKills(array $rows): float
    {
        return round($this->getPentaKills($rows) / count($rows), 2);
    }

    private function getMvps(array $rows): int
    {
        $counter = 0;

        foreach ($rows as $match) {
            $counter += $match['mvps'];
        }

        return $counter;
    }

    private function getAverageMvps(array $rows): float
    {
        return round($this->getMvps($rows) / count($rows), 2);
    }
}
