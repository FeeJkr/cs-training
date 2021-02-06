<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

use App\Faceit\Domain\Id;

final class StatisticsSegment
{
    public function __construct(
        private Id $id,
        private string $type,
        private string $mode,
        private string $label,
        private string $image,
        private int $kills,
        private float $averageKills,
        private int $assists,
        private float $averageAssists,
        private int $deaths,
        private float $averageDeaths,
        private int $headshots,
        private int $totalHeadshots,
        private float $averageHeadshots,
        private float $headshotsPerMatch,
        private float $krRatio,
        private float $averageKrRatio,
        private float $kdRatio,
        private float $averageKdRatio,
        private int $tripleKills,
        private int $quadroKills,
        private int $pentaKills,
        private float $averageTripleKills,
        private float $averageQuadroKills,
        private float $averagePentaKills,
        private int $mvps,
        private float $averageMvps,
        private int $matches,
        private int $rounds,
        private int $wins,
        private float $winRate
    ) {}

    public function getId(): Id
    {
        return $this->id;
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

    public function getWinRate(): float
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

    public function isGoodAverageKills(): bool
    {
        return $this->averageKills > 20.0;
    }

    public function isGoodAverageAssists(): bool
    {
        return $this->averageAssists < 6.0;
    }

    public function isGoodAverageDeaths(): bool
    {
        return $this->averageDeaths < 20.0;
    }

    public function isGoodAverageHeadshots(): bool
    {
        return $this->averageHeadshots > 40.0;
    }

    public function isGoodAverageKrRatio(): bool
    {
        return $this->averageKrRatio >= 0.8;
    }

    public function isGoodAverageKdRatio(): bool
    {
        return $this->averageKdRatio > 1.0;
    }

    public function isGoodAverageMvps(): bool
    {
        return $this->averageMvps > 4.0;
    }

    public function isGoodWinRate(): bool
    {
        return $this->winRate > 50.0;
    }
}
