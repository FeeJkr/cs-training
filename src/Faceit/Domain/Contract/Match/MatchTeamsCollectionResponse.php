<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Match;

final class MatchTeamsCollectionResponse
{
    private array $matchTeams;

    public function __construct(MatchTeamResponse ...$matchTeams)
    {
        $this->matchTeams = $matchTeams;
    }

    public static function createFromResponse(array $response): self
    {
        $teams = [];

        foreach ($response as $team) {
            $teams[] = MatchTeamResponse::createFromResponse($team);
        }

        return new self(...$teams);
    }

    public function toArray(): array
    {
        return $this->matchTeams;
    }
}
