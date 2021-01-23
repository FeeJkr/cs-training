<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitPlayerStatisticsSegment;
use Doctrine\DBAL\Connection;

final class FaceitPlayerStatisticsSegmentRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function update(int $statisticsId, FaceitPlayerStatisticsSegment $segment): void
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
                :faceitPlayersStatisticsId, 
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
        ", [
            'faceitPlayersStatisticsId' => $statisticsId,
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
            'averageKdRatio' => $segment->getKdRatio(),
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

    public function getByStatisticsId(int $statisticsId): array
    {
        return $this->connection->executeQuery("
            SELECT 
                id,
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
            FROM faceit_players_statistics_segments 
            WHERE faceit_players_statistics_id = :statisticsId
        ", ['statisticsId' => $statisticsId])->fetchAllAssociative();
    }
}
