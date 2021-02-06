<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use App\Faceit\Domain\Id;

final class Team
{
    public function __construct(
        private Id $id,
        private PlayersCollection $players,
        private string $name,
        private bool $isWinner,
        private int $firstHalfScore,
        private int $secondHalfScore,
        private int $overtimeScore,
        private int $finalRounds,
        private float $headshotsPercentage
    ){}

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPlayers(): PlayersCollection
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
