<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayerMatchResult
{
    private string $team;
    private string $map;
    private string $mode;
    private string $score;
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
    private bool $isWin;

    public function __construct(
        string $team,
        string $map,
        string $mode,
        string $score,
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
        float $krRatio,
        bool $isWin
    ) {
        $this->team = $team;
        $this->map = $map;
        $this->mode = $mode;
        $this->score = $score;
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
        $this->isWin = $isWin;
    }

    public static function createFromRow(array $row): self
    {
        return new self(
            $row['team'],
            $row['map'],
            $row['mode'],
            $row['score'],
            (int) $row['kills'],
            (int) $row['deaths'],
            (int) $row['assists'],
            (int) $row['headshots'],
            (float) $row['headshots_percentage'],
            (int) $row['triple_kills'],
            (int) $row['quadro_kills'],
            (int) $row['penta_kills'],
            (int) $row['mvps'],
            (float) $row['kd_ratio'],
            (float) $row['kr_ratio'],
            $row['is_win']
        );
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function getMap(): string
    {
        return $this->map;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getScore(): string
    {
        return $this->score;
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

    public function isWin(): bool
    {
        return $this->isWin;
    }

    public function isGoodKrRatio(): bool
    {
        return $this->krRatio >= 0.8;
    }

    public function isGoodKdRatio(): bool
    {
        return $this->kdRatio > 1.0;
    }
}
