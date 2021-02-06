<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Statistics;

use App\Faceit\Domain\Match\GetByPlayer\Scope;
use App\Faceit\Domain\Statistics\Statistics;
use App\Faceit\Domain\Statistics\StatisticsCollection;
use App\Faceit\Domain\Statistics\StatisticsFactory;
use App\Faceit\Domain\Statistics\StatisticsRepository;
use App\Faceit\Domain\Statistics\StatisticsType;
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
}
