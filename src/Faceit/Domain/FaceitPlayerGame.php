<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayerGame
{
    private const LEVELS = [
        1 => 1,
        2 => 801,
        3 => 951,
        4 => 1101,
        5 => 1251,
        6 => 1401,
        7 => 1551,
        8 => 1701,
        9 => 1851,
        10 => 2001,
    ];

    private Id $id;
    private string $name;
    private int $skillLevel;
    private int $faceitElo;
    private string $faceitGamePlayerId;
    private string $faceitGameProfileId;

    public function __construct(
        Id $id,
        int $skillLevel,
        int $faceitElo,
        string $faceitGamePlayerId,
        string $faceitGameProfileId
    ) {
        $this->name = 'csgo';
        $this->id = $id;
        $this->skillLevel = $skillLevel;
        $this->faceitElo = $faceitElo;
        $this->faceitGamePlayerId = $faceitGamePlayerId;
        $this->faceitGameProfileId = $faceitGameProfileId;
    }

    public static function createFromApi(array $body): self
    {
        return new self(
            Id::nullable(),
            $body['skill_level'],
            $body['faceit_elo'],
            $body['game_player_id'],
            $body['game_profile_id'],
        );
    }

    public static function createFromRow(array $row): self
    {
        return new self(
            Id::fromInt($row['fpg_id']),
            (int) $row['skill_level'],
            (int) $row['faceit_elo'],
            $row['game_player_id'],
            $row['game_profile_id']
        );
    }

    public function updateFromApi(array $body): void
    {
        $this->skillLevel = (int) $body['skill_level'];
        $this->faceitElo = (int) $body['faceit_elo'];
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSkillLevel(): int
    {
        return $this->skillLevel;
    }

    public function getFaceitElo(): int
    {
        return $this->faceitElo;
    }

    public function getFaceitGamePlayerId(): string
    {
        return $this->faceitGamePlayerId;
    }

    public function getFaceitGameProfileId(): string
    {
        return $this->faceitGameProfileId;
    }

    public function getEloToNextLevel(): int
    {
        return self::LEVELS[$this->skillLevel + 1] - $this->faceitElo;
    }

    public function getEloToPrevisionLevel(): int
    {
        return self::LEVELS[$this->skillLevel] - $this->faceitElo - 1;
    }

    public function getEloPercentageToNextLevel(): float
    {
        $eloLevelDiff = self::LEVELS[$this->skillLevel + 1] - self::LEVELS[$this->skillLevel];

        return 100 - round(($this->getEloToNextLevel() / $eloLevelDiff) * 100);
    }
}
