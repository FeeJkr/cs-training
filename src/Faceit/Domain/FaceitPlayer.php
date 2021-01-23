<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayer
{
    private Id $id;
    private FaceitPlayerGame $game;
    private string $faceitId;
    private string $nickname;
    private string $avatar;
    private string $faceitUrl;
    private ?FaceitPlayerStatistics $statistics;

    public function __construct(
        Id $id,
        FaceitPlayerGame $game,
        string $faceitId,
        string $nickname,
        string $avatar,
        string $faceitUrl,
        ?FaceitPlayerStatistics $statistics = null
    ) {
        $this->id = $id;
        $this->game = $game;
        $this->faceitId = $faceitId;
        $this->nickname = $nickname;
        $this->avatar = $avatar;
        $this->faceitUrl = $faceitUrl;
        $this->statistics = $statistics;
    }

    public static function createFromApi(array $body): self
    {
        return new self(
            Id::nullable(),
            FaceitPlayerGame::createFromApi($body['games']['csgo']),
            $body['player_id'],
            $body['nickname'],
            $body['avatar'],
            str_replace('{lang}', 'ru', $body['faceit_url'])
        );
    }

    public static function createFromRow(array $row): self
    {
        $statistics = null;

        if (isset($row['statistics_id']) && $row['statistics_id'] !== null) {
            $statistics = FaceitPlayerStatistics::createFromRow($row);
        }

        return new self(
            Id::fromInt($row['id']),
            FaceitPlayerGame::createFromRow($row),
            $row['faceit_id'],
            $row['nickname'],
            $row['avatar'],
            $row['faceit_url'],
            $statistics
        );
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getGame(): FaceitPlayerGame
    {
        return $this->game;
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

    public function getStatistics(): ?FaceitPlayerStatistics
    {
        return $this->statistics;
    }
}
