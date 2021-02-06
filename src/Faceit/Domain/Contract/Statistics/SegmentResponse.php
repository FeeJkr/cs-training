<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Statistics;

final class SegmentResponse
{
    public function __construct(
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
        private int $totalHeadshotsPercentage,
        private float $averageHeadshotsPercentage,
        private float $headshotsPerMatch,
        private float $totalKrRatio,
        private float $averageKrRatio,
        private float $totalKdRatio,
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
    ){}
    
    public static function createFromResponse(array $response): self
    {
        return new self(
            $response['type'],
            $response['mode'],
            $response['label'],
            $response['img_regular'],
            (int) $response['stats']['Kills'],
            (float) $response['stats']['Average Kills'],
            (int) $response['stats']['Assists'],
            (float) $response['stats']['Average Assists'],
            (int) $response['stats']['Deaths'],
            (float) $response['stats']['Average Deaths'],
            (int) $response['stats']['Headshots'],
            (int) $response['stats']['Total Headshots %'],
            (float) $response['stats']['Average Headshots %'],
            (float) $response['stats']['Headshots per Match'],
            (float) $response['stats']['K/R Ratio'],
            (float) $response['stats']['Average K/R Ratio'],
            (float) $response['stats']['K/D Ratio'],
            (float) $response['stats']['Average K/D Ratio'],
            (int) $response['stats']['Triple Kills'],
            (int) $response['stats']['Quadro Kills'],
            (int) $response['stats']['Penta Kills'],
            (float) $response['stats']['Average Triple Kills'],
            (float) $response['stats']['Average Quadro Kills'],
            (float) $response['stats']['Average Penta Kills'],
            (int) $response['stats']['MVPs'],
            (float) $response['stats']['Average MVPs'],
            (int) $response['stats']['Matches'],
            (int) $response['stats']['Rounds'],
            (int) $response['stats']['Wins'],
            (float) $response['stats']['Win Rate %']
        );
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

    public function getKills(): int
    {
        return $this->kills;
    }

    public function getAverageKills(): float
    {
        return $this->averageKills;
    }

    public function getAssists(): int
    {
        return $this->assists;
    }

    public function getAverageAssists(): float
    {
        return $this->averageAssists;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getAverageDeaths(): float
    {
        return $this->averageDeaths;
    }

    public function getHeadshots(): int
    {
        return $this->headshots;
    }

    public function getTotalHeadshotsPercentage(): int
    {
        return $this->totalHeadshotsPercentage;
    }

    public function getAverageHeadshotsPercentage(): float
    {
        return $this->averageHeadshotsPercentage;
    }

    public function getHeadshotsPerMatch(): float
    {
        return $this->headshotsPerMatch;
    }

    public function getTotalKrRatio(): float
    {
        return $this->totalKrRatio;
    }

    public function getAverageKrRatio(): float
    {
        return $this->averageKrRatio;
    }

    public function getTotalKdRatio(): float
    {
        return $this->totalKdRatio;
    }

    public function getAverageKdRatio(): float
    {
        return $this->averageKdRatio;
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

    public function getAverageTripleKills(): float
    {
        return $this->averageTripleKills;
    }

    public function getAverageQuadroKills(): float
    {
        return $this->averageQuadroKills;
    }

    public function getAveragePentaKills(): float
    {
        return $this->averagePentaKills;
    }

    public function getMvps(): int
    {
        return $this->mvps;
    }

    public function getAverageMvps(): float
    {
        return $this->averageMvps;
    }

    public function getMatches(): int
    {
        return $this->matches;
    }

    public function getRounds(): int
    {
        return $this->rounds;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getWinRate(): float
    {
        return $this->winRate;
    }
}
