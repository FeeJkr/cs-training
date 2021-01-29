<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Player;

use App\Faceit\Domain\Id;

final class Player
{
    private Id $id;
    private string $faceitId;
    private string $nickname;
    private string $avatar;
    private string $faceitUrl;
    private int $skillLevel;
    private int $faceitElo;

    public function __construct(
        Id $id,
        string $faceitId,
        string $nickname,
        string $avatar,
        string $faceitUrl,
        int $skillLevel,
        int $faceitElo
    ) {
        $this->id = $id;
        $this->faceitId = $faceitId;
        $this->nickname = $nickname;
        $this->avatar = $avatar;
        $this->faceitUrl = $faceitUrl;
        $this->skillLevel = $skillLevel;
        $this->faceitElo = $faceitElo;
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
