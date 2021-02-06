<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use App\Faceit\Domain\Contract\Match\MatchesCollectionResponse;
use App\Faceit\Domain\Contract\Match\MatchResponse;
use App\Faceit\Domain\Id;
use DateTime;

final class MatchFactory
{
    private TeamFactory $teamFactory;

    public function __construct(TeamFactory $teamFactory)
    {
        $this->teamFactory = $teamFactory;
    }

    public function createCollectionFromResponse(MatchesCollectionResponse $response): MatchesCollection
    {
        $matches = [];

        foreach ($response->toArray() as $match) {
            $matches[] = $this->createFromResponse($match);
        }

        return new MatchesCollection(...$matches);
    }

    public function createFromResponse(MatchResponse $response): FaceitMatch
    {
        return new FaceitMatch(
            Id::nullable(),
            $this->teamFactory->createCollectionFromResponse($response->getTeams()),
            $response->getMatchId(),
            $response->getGameMode(),
            $response->getCompetitionType(),
            $response->getStatus(),
            $response->getMap(),
            $response->getRounds(),
            $response->getScore(),
            $response->getFaceitUrl(),
            $response->getStartedAt(),
            $response->getFinishedAt()
        );
    }

    public function createFromRow(array $row): FaceitMatch
    {
        return new FaceitMatch(
            Id::fromInt($row['id']),
            new TeamsCollection(
                ...array_map(fn(array $team): Team => $this->teamFactory->createFromRow($team), $row['teams'])
            ),
            $row['match_id'],
            $row['game_mode'],
            $row['competition_type'],
            $row['status'],
            $row['map'],
            $row['rounds'],
            $row['score'],
            $row['faceit_url'],
            DateTime::createFromFormat('Y-m-d H:i:s', $row['started_at']),
            DateTime::createFromFormat('Y-m-d H:i:s', $row['finished_at'])
        );
    }
}
