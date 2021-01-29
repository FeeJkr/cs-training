<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Match;

final class MatchPlayerResponse
{
    private string $playerId;
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
        string $playerId,
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
        $this->playerId = $playerId;
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
    
    public static function createFromResponse(array $response): self
    {
        return new self(
            $response['player_id'],
            $response['nickname'],
            (int) $response['player_stats']['Kills'],
            (int) $response['player_stats']['Deaths'],
            (int) $response['player_stats']['Assists'],
            (int) $response['player_stats']['Headshot'],
            (float) $response['player_stats']['Headshots %'],
            (int) $response['player_stats']['Triple Kills'],
            (int) $response['player_stats']['Quadro Kills'],
            (int) $response['player_stats']['Penta Kills'],
            (int) $response['player_stats']['MVPs'],
            (float) $response['player_stats']['K/D Ratio'],
            (float) $response['player_stats']['K/R Ratio']
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
