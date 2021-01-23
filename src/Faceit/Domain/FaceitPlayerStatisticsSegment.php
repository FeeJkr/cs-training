<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayerStatisticsSegment
{
    private string $type;
    private string $mode;
    private string $label;
    private string $image;
    private int $headshots;
    private float $averageAssists;
    private int $quadroKills;
    private int $matches;
    private int $assists;
    private int $mvps;
    private float $krRatio;
    private float $averageKrRatio;
    private int $kills;
    private float $averageKills;
    private int $totalHeadshots;
    private float $averageKdRatio;
    private int $winRate;
    private int $tripleKills;
    private int $pentaKills;
    private float $headshotsPerMatch;
    private float $averageHeadshots;
    private float $kdRatio;
    private int $wins;
    private float $averagePentaKills;
    private int $deaths;
    private float $averageTripleKills;
    private float $averageQuadroKills;
    private float $averageDeaths;
    private float $averageMvps;
    private int $rounds;

    public function __construct(
        string $type,
        string $mode,
        string $label,
        string $image,
        int $headshots,
        float $averageAssists,
        int $quadroKills,
        int $matches,
        int $assists,
        int $mvps,
        float $krRatio,
        float $averageKrRatio,
        int $kills,
        float $averageKills,
        int $totalHeadshots,
        float $averageKdRatio,
        int $winRate,
        int $tripleKills,
        int $pentaKills,
        float $headshotsPerMatch,
        float $averageHeadshots,
        float $kdRatio,
        int $wins,
        float $averagePentaKills,
        int $deaths,
        float $averageTripleKills,
        float $averageQuadroKills,
        float $averageDeaths,
        float $averageMvps,
        int $rounds
    ) {
        $this->type = $type;
        $this->mode = $mode;
        $this->label = $label;
        $this->image = $image;
        $this->headshots = $headshots;
        $this->averageAssists = $averageAssists;
        $this->quadroKills = $quadroKills;
        $this->matches = $matches;
        $this->assists = $assists;
        $this->mvps = $mvps;
        $this->krRatio = $krRatio;
        $this->averageKrRatio = $averageKrRatio;
        $this->kills = $kills;
        $this->averageKills = $averageKills;
        $this->totalHeadshots = $totalHeadshots;
        $this->averageKdRatio = $averageKdRatio;
        $this->winRate = $winRate;
        $this->tripleKills = $tripleKills;
        $this->pentaKills = $pentaKills;
        $this->headshotsPerMatch = $headshotsPerMatch;
        $this->averageHeadshots = $averageHeadshots;
        $this->kdRatio = $kdRatio;
        $this->wins = $wins;
        $this->averagePentaKills = $averagePentaKills;
        $this->deaths = $deaths;
        $this->averageTripleKills = $averageTripleKills;
        $this->averageQuadroKills = $averageQuadroKills;
        $this->averageDeaths = $averageDeaths;
        $this->averageMvps = $averageMvps;
        $this->rounds = $rounds;
    }

    public function createFromApi(array $body): self
    {

    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getHeadshots(): int
    {
        return $this->headshots;
    }

    public function getAverageAssists(): float
    {
        return $this->averageAssists;
    }

    public function getQuadroKills(): int
    {
        return $this->quadroKills;
    }

    public function getMatches(): int
    {
        return $this->matches;
    }

    public function getAssists(): int
    {
        return $this->assists;
    }

    public function getMvps(): int
    {
        return $this->mvps;
    }

    public function getKrRatio(): float
    {
        return $this->krRatio;
    }

    public function getAverageKrRatio(): float
    {
        return $this->averageKrRatio;
    }

    public function getKills(): int
    {
        return $this->kills;
    }

    public function getAverageKills(): float
    {
        return $this->averageKills;
    }

    public function getTotalHeadshots(): int
    {
        return $this->totalHeadshots;
    }

    public function getAverageKdRatio(): float
    {
        return $this->averageKdRatio;
    }

    public function getWinRate(): int
    {
        return $this->winRate;
    }

    public function getTripleKills(): int
    {
        return $this->tripleKills;
    }

    public function getPentaKills(): int
    {
        return $this->pentaKills;
    }

    public function getHeadshotsPerMatch(): float
    {
        return $this->headshotsPerMatch;
    }

    public function getAverageHeadshots(): float
    {
        return $this->averageHeadshots;
    }

    public function getKdRatio(): float
    {
        return $this->kdRatio;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getAveragePentaKills(): float
    {
        return $this->averagePentaKills;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getAverageTripleKills(): float
    {
        return $this->averageTripleKills;
    }

    public function getAverageQuadroKills(): float
    {
        return $this->averageQuadroKills;
    }

    public function getAverageDeaths(): float
    {
        return $this->averageDeaths;
    }

    public function getAverageMvps(): float
    {
        return $this->averageMvps;
    }

    public function getRounds(): int
    {
        return $this->rounds;
    }
}
