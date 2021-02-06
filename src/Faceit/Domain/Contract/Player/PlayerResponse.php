<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Player;

final class PlayerResponse
{
    public function __construct(
        private string $playerId,
        private string $nickname,
        private string $avatar,
        private string $faceitUrl,
        private int $skillLevel,
        private int $faceitElo,
        private string $gamePlayerId,
        private string $gameProfileId
    ){}

    public static function createFromResponse(array $response): self
    {
        return new self(
            $response['player_id'],
            $response['nickname'],
            $response['avatar'],
            str_replace('{lang}', 'ru', $response['faceit_url']),
            $response['games']['csgo']['skill_level'],
            $response['games']['csgo']['faceit_elo'],
            $response['games']['csgo']['game_player_id'],
            $response['games']['csgo']['game_profile_id'],
        );
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
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

    public function getGamePlayerId(): string
    {
        return $this->gamePlayerId;
    }

    public function getGameProfileId(): string
    {
        return $this->gameProfileId;
    }
}
