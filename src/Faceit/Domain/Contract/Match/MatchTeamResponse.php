<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Match;

final class MatchTeamResponse
{
    private string $name;
    private bool $isWinner;
    private int $firstHalfScore;
    private int $secondHalfScore;
    private int $overtimeScore;
    private int $finalScore;
    private float $teamHeadshots;
    private MatchPlayersCollectionResponse $players;

    public function __construct(
        string $name,
        bool $isWinner,
        int $firstHalfScore,
        int $secondHalfScore,
        int $overtimeScore,
        int $finalScore,
        float $teamHeadshots,
        MatchPlayersCollectionResponse $players
    ) {
        $this->name = $name;
        $this->isWinner = $isWinner;
        $this->firstHalfScore = $firstHalfScore;
        $this->secondHalfScore = $secondHalfScore;
        $this->overtimeScore = $overtimeScore;
        $this->finalScore = $finalScore;
        $this->teamHeadshots = $teamHeadshots;
        $this->players = $players;
    }

    public static function createFromResponse(array $response): self
    {
        return new self(
            $response['team_stats']['Team'],
            $response['team_stats']['Team Win'] === '1',
            (int) $response['team_stats']['First Half Score'],
            (int) $response['team_stats']['Second Half Score'],
            (int) $response['team_stats']['Overtime score'],
            (int) $response['team_stats']['Final Score'],
            (float) $response['team_stats']['Team Headshot'],
            MatchPlayersCollectionResponse::createFromResponse($response['players']),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isWinner(): bool
    {
        return $this->isWinner;
    }

    public function getFirstHalfScore(): int
    {
        return $this->firstHalfScore;
    }

    public function getSecondHalfScore(): int
    {
        return $this->secondHalfScore;
    }

    public function getOvertimeScore(): int
    {
        return $this->overtimeScore;
    }

    public function getFinalScore(): int
    {
        return $this->finalScore;
    }

    public function getTeamHeadshots(): float
    {
        return $this->teamHeadshots;
    }

    public function getPlayers(): MatchPlayersCollectionResponse
    {
        return $this->players;
    }
}
