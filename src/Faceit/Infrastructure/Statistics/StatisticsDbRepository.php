<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Statistics;

use App\Faceit\Domain\Statistics\Statistics;
use App\Faceit\Domain\Statistics\StatisticsRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class StatisticsDbRepository implements StatisticsRepository
{
    private Connection $connection;
    private StatisticsSegmentDbRepository $statisticsSegmentRepository;

    public function __construct(Connection $connection, StatisticsSegmentDbRepository $statisticsSegmentRepository)
    {
        $this->connection = $connection;
        $this->statisticsSegmentRepository = $statisticsSegmentRepository;
    }

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
            dd($exception);
            $this->connection->rollBack();
        }
    }
}
