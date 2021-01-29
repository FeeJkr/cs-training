<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Statistics;

use App\Faceit\Domain\Statistics\StatisticsSegment;
use App\Faceit\Domain\Statistics\StatisticsSegmentFactory;
use App\Faceit\Domain\Statistics\StatisticsSegmentsCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class StatisticsSegmentDbRepository
{
    private Connection $connection;
    private StatisticsSegmentFactory $factory;

    public function __construct(Connection $connection, StatisticsSegmentFactory $factory)
    {
        $this->connection = $connection;
        $this->factory = $factory;
    }

    /**
     * @throws Exception
     */
    public function add(int $statisticsId, StatisticsSegment $segment): void
    {
        $this->connection->executeQuery("
            INSERT INTO faceit_players_statistics_segments
            (
                faceit_players_statistics_id, 
                type, 
                mode,
                label,
                image,
                kills,
                average_kills,
                assists,
                average_assists,
                deaths,
                average_deaths,
                headshots,
                total_headshots,
                average_headshots,
                headshots_per_match,
                kr_ratio,
                average_kr_ratio,
                kd_ratio,
                average_kd_ratio,
                triple_kills,
                quadro_kills,
                penta_kills,
                average_triple_kills,
                average_quadro_kills,
                average_penta_kills,
                mvps,
                average_mvps,
                matches,
                rounds,
                wins,
                win_rate
            ) VALUES (
                :statisticsId,
                :type,
                :mode,
                :label,
                :image,
                :kills,
                :averageKills,
                :assists,
                :averageAssists,
                :deaths,
                :averageDeaths,
                :headshots,
                :totalHeadshots,
                :averageHeadshots,
                :headshotsPerMatch,
                :krRatio,
                :averageKrRatio,
                :kdRatio,
                :averageKdRatio,
                :tripleKills,
                :quadroKills,
                :pentaKills,
                :averageTripleKills,
                :averageQuadroKills,
                :averagePentaKills,
                :mvps,
                :averageMvps,
                :matches,
                :rounds,
                :wins,
                :winRate
            )
            ON CONFLICT (faceit_players_statistics_id, label, mode) DO UPDATE
            SET
                type = :type,
                mode = :mode,
                label = :label,
                image = :image,
                kills = :kills,
                average_kills = :averageKills,
                assists = :assists,
                average_assists = :averageAssists,
                deaths = :deaths,
                average_deaths = :averageDeaths,
                headshots = :headshots,
                total_headshots = :totalHeadshots,
                average_headshots = :averageHeadshots,
                headshots_per_match = :headshotsPerMatch,
                kr_ratio = :krRatio,
                average_kr_ratio = :averageKrRatio,
                kd_ratio = :kdRatio,
                average_kd_ratio = :averageKdRatio,
                triple_kills = :tripleKills,
                quadro_kills = :quadroKills,
                penta_kills = :pentaKills,
                average_triple_kills = :averageTripleKills,
                average_quadro_kills = :averageQuadroKills,
                average_penta_kills = :averagePentaKills,
                mvps = :mvps,
                average_mvps = :averageMvps, 
                matches = :matches,
                rounds = :rounds,
                wins = :wins,
                win_rate = :winRate
        ", [
            'statisticsId' => $statisticsId,
            'type' => $segment->getType(),
            'mode' => $segment->getMode(),
            'label' => $segment->getLabel(),
            'image' => $segment->getImage(),
            'kills' => $segment->getKills(),
            'averageKills' => $segment->getAverageKills(),
            'assists' => $segment->getAssists(),
            'averageAssists' => $segment->getAverageAssists(),
            'deaths' => $segment->getDeaths(),
            'averageDeaths' => $segment->getAverageDeaths(),
            'headshots' => $segment->getHeadshots(),
            'totalHeadshots' => $segment->getTotalHeadshots(),
            'averageHeadshots' => $segment->getAverageHeadshots(),
            'headshotsPerMatch' => $segment->getHeadshotsPerMatch(),
            'krRatio' => $segment->getKrRatio(),
            'averageKrRatio' => $segment->getAverageKrRatio(),
            'kdRatio' => $segment->getKdRatio(),
            'averageKdRatio' => $segment->getAverageKdRatio(),
            'tripleKills' => $segment->getTripleKills(),
            'quadroKills' => $segment->getQuadroKills(),
            'pentaKills' => $segment->getPentaKills(),
            'averageTripleKills' => $segment->getAverageTripleKills(),
            'averageQuadroKills' => $segment->getAverageQuadroKills(),
            'averagePentaKills' => $segment->getAveragePentaKills(),
            'mvps' => $segment->getMvps(),
            'averageMvps' => $segment->getAverageMvps(),
            'matches' => $segment->getMatches(),
            'rounds' => $segment->getRounds(),
            'wins' => $segment->getWins(),
            'winRate' => $segment->getWinRate(),
        ]);
    }

    /**
     * @throws Exception
     */
    public function getByStatisticsId(int $statisticsId): StatisticsSegmentsCollection
    {
        $rows = $this->connection->executeQuery("
            SELECT * FROM faceit_players_statistics_segments WHERE faceit_players_statistics_id = :statisticsId
        ", ['statisticsId' => $statisticsId])->fetchAllAssociative();

        return $this->factory->createCollectionFromRows($rows);
    }
}
