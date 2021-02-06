<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Statistics;

use App\Faceit\Domain\Match\GetByPlayer\Scope;
use App\Faceit\Domain\Statistics\PlayerMatch;
use App\Faceit\Domain\Statistics\PlayerMatches;
use App\Faceit\Domain\Statistics\Statistics;
use App\Faceit\Domain\Statistics\StatisticsCollection;
use App\Faceit\Domain\Statistics\StatisticsFactory;
use App\Faceit\Domain\Statistics\StatisticsRepository;
use App\Faceit\Domain\Statistics\StatisticsType;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class StatisticsDbRepository implements StatisticsRepository
{
    public function __construct(
        private Connection $connection,
        private StatisticsSegmentDbRepository $statisticsSegmentRepository,
        private StatisticsFactory $factory
    ){}

    /**
     * @throws Exception
     */
    public function save(Statistics $statistics): void
    {
        try {
            $this->connection->beginTransaction();

            $id = $this->connection->executeQuery("
                INSERT INTO faceit_players_statistics
                (
                    faceit_player_id, 
                    type, 
                    matches,
                    wins,
                    win_rate,
                    kd_ratio,
                    average_kd_ratio,
                    headshots,
                    average_headshots
                ) VALUES (
                    :playerId,
                    :type,
                    :matches,
                    :wins,
                    :winRate,
                    :kdRatio,
                    :averageKdRatio,
                    :headshots,
                    :averageHeadshots
                ) 
                ON CONFLICT (faceit_player_id, type) DO UPDATE
                SET
                    matches = :matches,
                    wins = :wins,
                    win_rate = :winRate,
                    kd_ratio = :kdRatio,
                    average_kd_ratio = :averageKdRatio,
                    headshots = :headshots,
                    average_headshots = :averageHeadshots
                RETURNING id;
            ", [
                'playerId' => $statistics->getPlayerId(),
                'type' => $statistics->getType()->getValue(),
                'matches' => $statistics->getMatches(),
                'wins' => $statistics->getWins(),
                'winRate' => $statistics->getWinRate(),
                'kdRatio' => $statistics->getKdRatio(),
                'averageKdRatio' => $statistics->getAverageKdRatio(),
                'headshots' => $statistics->getHeadshots(),
                'averageHeadshots' => $statistics->getAverageHeadshots(),
            ])->fetchOne();

            foreach ($statistics->getSegments()->toArray() as $segment) {
                $this->statisticsSegmentRepository->add($id, $segment);
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
        }
    }

    /**
     * @throws Exception
     */
    public function getAllByPlayer(string $playerId): StatisticsCollection
    {
        $result = [];
        $rows = $this->connection->executeQuery("
            SELECT * FROM faceit_players_statistics WHERE faceit_player_id = :playerId
        ", ['playerId' => $playerId])->fetchAllAssociative();

        foreach ($rows as $row) {
            $result[] = $this->factory->createFromRow(
                $row,
                $this->statisticsSegmentRepository->getByStatisticsId($row['id'])
            );
        }

        return new StatisticsCollection(...$result);
    }

    public function getPlayerMatchesByMonth(string $playerId): PlayerMatches
    {
        $today = new DateTime();
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
            WHERE fmtp.faceit_player_id = :playerId AND fm.finished_at >= :startMonthDate
            ORDER BY fm.finished_at DESC
        ", [
            'playerId' => $playerId,
            'startMonthDate' => $today
                ->setDate((int) $today->format('Y'), (int) $today->format('m'), 1)
                ->format('Y-m-d 00:00:00'),
        ])->fetchAllAssociative();

        return new PlayerMatches(
            ...array_map(static fn(array $row): PlayerMatch => PlayerMatch::createFromRow($row), $rows)
        );
    }
}
