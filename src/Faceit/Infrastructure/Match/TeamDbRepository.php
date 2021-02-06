<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Match;

use App\Faceit\Domain\Match\Team;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class TeamDbRepository
{
    public function __construct(private Connection $connection, private PlayerDbRepository $playerRepository){}

    /**
     * @throws Exception
     */
    public function add(int $faceitMatchId, Team $team): void
    {
        $teamId = $this->connection->executeQuery("
            INSERT INTO faceit_matches_teams
            (
                faceit_matches_id, 
                name, 
                is_win, 
                first_half_rounds, 
                second_half_rounds, 
                overtime_rounds, 
                final_rounds, 
                headshots_percentage
            ) VALUES (
                :faceitMatchId,
                :name,
                :isWin,
                :firstHalfRounds,
                :secondHalfRounds,
                :overtimeRounds,
                :finalRounds,
                :headshotsPercentage
            ) RETURNING id;
        ", [
                'faceitMatchId' => $faceitMatchId,
                'name' => $team->getName(),
                'isWin' => $team->isWinner() ? 1 : 0,
                'firstHalfRounds' => $team->getFirstHalfScore(),
                'secondHalfRounds' => $team->getSecondHalfScore(),
                'overtimeRounds' => $team->getOvertimeScore(),
                'finalRounds' => $team->getFinalRounds(),
                'headshotsPercentage' => $team->getHeadshotsPercentage(),
            ])->fetchOne();

        foreach ($team->getPlayers()->toArray() as $player) {
            $this->playerRepository->add($teamId, $player);
        }
    }
}
