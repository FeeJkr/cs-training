<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitMatchTeam;
use Doctrine\DBAL\Connection;

final class FaceitMatchTeamRepository
{
    private Connection $connection;
    private FaceitMatchTeamPlayerRepository $faceitMatchTeamPlayerRepository;

    public function __construct(Connection $connection, FaceitMatchTeamPlayerRepository $faceitMatchTeamPlayerRepository)
    {
        $this->connection = $connection;
        $this->faceitMatchTeamPlayerRepository = $faceitMatchTeamPlayerRepository;
    }

    public function add(int $faceitMatchId, FaceitMatchTeam $team): void
    {
        $id = $this->connection->executeQuery("
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

        foreach ($team->getPlayers()->getPlayers() as $player) {
            $this->faceitMatchTeamPlayerRepository->add($id, $player);
        }
    }
}
