<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use App\Faceit\Domain\Contract\Match\MatchTeamResponse;
use App\Faceit\Domain\Contract\Match\MatchTeamsCollectionResponse;
use App\Faceit\Domain\Id;

final class TeamFactory
{
    private PlayerFactory $playerFactory;

    public function __construct(PlayerFactory $playerFactory)
    {
        $this->playerFactory = $playerFactory;
    }

    public function createCollectionFromResponse(MatchTeamsCollectionResponse $response): TeamsCollection
    {
        $teams = [];

        foreach ($response->toArray() as $team) {
            $teams[] = $this->createFromResponse($team);
        }

        return new TeamsCollection(...$teams);
    }

    public function createFromResponse(MatchTeamResponse $response): Team
    {
        return new Team(
            Id::nullable(),
            $this->playerFactory->createCollectionFromResponse($response->getPlayers()),
            $response->getName(),
            $response->isWinner(),
            $response->getFirstHalfScore(),
            $response->getSecondHalfScore(),
            $response->getOvertimeScore(),
            $response->getFinalScore(),
            $response->getTeamHeadshots()
        );
    }

    public function createFromRow(array $row): Team
    {
        return new Team(
            Id::fromInt($row['team_id']),
            new PlayersCollection(
                ...array_map(fn(array $player): Player => $this->playerFactory->createFromRow($player), $row['players'])
            ),
            $row['name'],
            $row['is_winner'] === 1,
            $row['first_half_score'],
            $row['second_half_score'],
            $row['overtime_score'],
            $row['final_score'],
            $row['headshots_percentage']
        );
    }
}
