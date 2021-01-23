<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitMatchTeam
{
    private Id $id;
    private FaceitMatchTeamPlayersCollection $players;
    private string $name;
    private bool $isWinner;
    private int $firstHalfScore;
    private int $secondHalfScore;
    private int $overtimeScore;
    private int $finalRounds;
    private float $headshotsPercentage;

    public function __construct(
        Id $id,
        FaceitMatchTeamPlayersCollection $players,
        string $name,
        bool $isWinner,
        int $firstHalfScore,
        int $secondHalfScore,
        int $overtimeScore,
        int $finalRounds,
        float $headshotsPercentage
    ) {
        $this->id = $id;
        $this->players = $players;
        $this->name = $name;
        $this->isWinner = $isWinner;
        $this->firstHalfScore = $firstHalfScore;
        $this->secondHalfScore = $secondHalfScore;
        $this->overtimeScore = $overtimeScore;
        $this->finalRounds = $finalRounds;
        $this->headshotsPercentage = $headshotsPercentage;
    }

    public static function createFromApi(array $body): self
    {
        return new self(
            Id::nullable(),
            FaceitMatchTeamPlayersCollection::createFromApi($body['players']),
            $body['team_stats']['Team'],
            $body['team_stats']['Team Win'] === "1",
            (int) $body['team_stats']['First Half Score'],
            (int) $body['team_stats']['Second Half Score'],
            (int) $body['team_stats']['Overtime score'],
            (int) $body['team_stats']['Final Score'],
            (float) $body['team_stats']['Team Headshot']
        );
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPlayers(): FaceitMatchTeamPlayersCollection
    {
        return $this->players;
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

    public function getFinalRounds(): int
    {
        return $this->finalRounds;
    }

    public function getHeadshotsPercentage(): float
    {
        return $this->headshotsPercentage;
    }
}
