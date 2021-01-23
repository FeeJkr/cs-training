<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitPlayer;
use App\Faceit\Domain\FaceitPlayerRepository as FaceitPlayerRepositoryInterface;
use App\Faceit\Domain\FaceitPlayerStatistics;
use App\Faceit\Domain\Id;
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

    public function updateStatistics(Id $playerId, FaceitPlayerStatistics $statistics): void
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
                $this->faceitPlayerStatisticsSegmentRepository->update($id, $segment);
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
}
