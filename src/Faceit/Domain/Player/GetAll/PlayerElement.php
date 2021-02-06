<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Player\GetAll;

final class PlayerElement
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

    public function __construct(
        private int $id,
        private string $faceitId,
        private string $nickname,
        private string $avatar,
        private int $skillLevel,
        private int $faceitElo,
        private string $faceitUrl
    ){}

    public static function createFromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            $row['faceit_id'],
            $row['nickname'],
            $row['avatar'],
            (int) $row['skill_level'],
            (int) $row['faceit_elo'],
            $row['faceit_url']
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFaceitId(): string
    {
        return $this->faceitId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getSkillLevel(): int
    {
        return $this->skillLevel;
    }

    public function getFaceitElo(): int
    {
        return $this->faceitElo;
    }

    public function getFaceitUrl(): string
    {
        return $this->faceitUrl;
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
