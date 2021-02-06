<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match\GetByPlayer;

final class TeamElement
{
    public function __construct(
        private int $id,
        private string $name,
        private PlayerList $players,
        private int $firstHalfRounds,
        private int $secondHalfRounds,
        private int $overtimeRounds,
        private int $finalRounds,
        private bool $isWinner
    ){}

    public static function createFromRow(array $row): self
    {
        $teamInformation = $row[0];

        return new self(
            $teamInformation['team_id'],
            $teamInformation['team_name'],
            PlayerList::createFromRows($row),
            $teamInformation['team_first_half_rounds'],
            $teamInformation['team_second_half_rounds'],
            $teamInformation['team_overtime_rounds'],
            $teamInformation['team_final_rounds'],
            $teamInformation['team_is_win']
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayers(): PlayerList
    {
        return $this->players;
    }

    public function getFirstHalfRounds(): int
    {
        return $this->firstHalfRounds;
    }

    public function getSecondHalfRounds(): int
    {
        return $this->secondHalfRounds;
    }

    public function getOvertimeRounds(): int
    {
        return $this->overtimeRounds;
    }

    public function getFinalRounds(): int
    {
        return $this->finalRounds;
    }

    public function isWinner(): bool
    {
        return $this->isWinner;
    }
}
