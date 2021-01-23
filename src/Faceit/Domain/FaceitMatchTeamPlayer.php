<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitMatchTeamPlayer
{
    private Id $id;
    private string $faceitId;
    private string $nickname;
    private int $kills;
    private int $deaths;
    private int $assists;
    private int $headshots;
    private float $headshotsPercentage;
    private int $tripleKills;
    private int $quadroKills;
    private int $pentaKills;
    private int $mvps;
    private float $kdRatio;
    private float $krRatio;

    public function __construct(
        Id $id,
        string $faceitId,
        string $nickname,
        int $kills,
        int $deaths,
        int $assists,
        int $headshots,
        float $headshotsPercentage,
        int $tripleKills,
        int $quadroKills,
        int $pentaKills,
        int $mvps,
        float $kdRatio,
        float $krRatio
    ) {
        $this->id = $id;
        $this->faceitId = $faceitId;
        $this->nickname = $nickname;
        $this->kills = $kills;
        $this->deaths = $deaths;
        $this->assists = $assists;
        $this->headshots = $headshots;
        $this->headshotsPercentage = $headshotsPercentage;
        $this->tripleKills = $tripleKills;
        $this->quadroKills = $quadroKills;
        $this->pentaKills = $pentaKills;
        $this->mvps = $mvps;
        $this->kdRatio = $kdRatio;
        $this->krRatio = $krRatio;
    }

    public static function createFromApi(array $body): self
    {
        return new self(
            Id::nullable(),
            $body['player_id'],
            $body['nickname'],
            (int) $body['player_stats']['Kills'],
            (int) $body['player_stats']['Deaths'],
            (int) $body['player_stats']['Assists'],
            (int) $body['player_stats']['Headshot'],
            (float) $body['player_stats']['Headshots %'],
            (int) $body['player_stats']['Triple Kills'],
            (int) $body['player_stats']['Quadro Kills'],
            (int) $body['player_stats']['Penta Kills'],
            (int) $body['player_stats']['MVPs'],
            (float) $body['player_stats']['K/D Ratio'],
            (float) $body['player_stats']['K/R Ratio']
        );
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

    public function getKills(): int
    {
        return $this->kills;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getAssists(): int
    {
        return $this->assists;
    }

    public function getHeadshots(): int
    {
        return $this->headshots;
    }

    public function getHeadshotsPercentage(): float
    {
        return $this->headshotsPercentage;
    }

    public function getTripleKills(): int
    {
        return $this->tripleKills;
    }

    public function getQuadroKills(): int
    {
        return $this->quadroKills;
    }

    public function getPentaKills(): int
    {
        return $this->pentaKills;
    }

    public function getMvps(): int
    {
        return $this->mvps;
    }

    public function getKdRatio(): float
    {
        return $this->kdRatio;
    }

    public function getKrRatio(): float
    {
        return $this->krRatio;
    }
}
