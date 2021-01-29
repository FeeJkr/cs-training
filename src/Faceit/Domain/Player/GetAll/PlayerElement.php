<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Player\GetAll;

use App\Faceit\Domain\Id;

final class PlayerElement
{
    private int $id;
    private string $faceitId;
    private string $nickname;
    private string $avatar;
    private int $skillLevel;
    private int $faceitElo;
    private string $faceitUrl;

    public function __construct(
        int $id,
        string $faceitId,
        string $nickname,
        string $avatar,
        int $skillLevel,
        int $faceitElo,
        string $faceitUrl
    ) {
        $this->id = $id;
        $this->faceitId = $faceitId;
        $this->nickname = $nickname;
        $this->avatar = $avatar;
        $this->skillLevel = $skillLevel;
        $this->faceitElo = $faceitElo;
        $this->faceitUrl = $faceitUrl;
    }

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
}
