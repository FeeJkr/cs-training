<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Player;

use App\Faceit\Domain\Id;

final class Player
{
    public function __construct(
        private Id $id,
        private string $faceitId,
        private string $nickname,
        private string $avatar,
        private string $faceitUrl,
        private int $skillLevel,
        private int $faceitElo
    ){}

    public function update(int $faceitElo, int $skillLevel, string $avatar): void
    {
        $this->faceitElo = $faceitElo;
        $this->skillLevel = $skillLevel;
        $this->avatar = $avatar;
    }

    public function getId(): Id
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

    public function getFaceitUrl(): string
    {
        return $this->faceitUrl;
    }

    public function getSkillLevel(): int
    {
        return $this->skillLevel;
    }

    public function getFaceitElo(): int
    {
        return $this->faceitElo;
    }
}
