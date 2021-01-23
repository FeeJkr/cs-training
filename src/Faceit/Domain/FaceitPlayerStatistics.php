<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayerStatistics
{
    private int $matches;
    private int $wins;
    private float $winRate;
    private float $kdRatio;
    private float $averageKdRatio;
    private int $headshots;
    private float $averageHeadshots;

    public function __construct(
        int $matches,
        int $wins,
        float $winRate,
        float $kdRatio,
        float $averageKdRatio,
        int $headshots,
        float $averageHeadshots,
        FaceitPlayerStatisticsSegmentsCollection $segmentsCollection
    ) {
        $this->matches = $matches;
        $this->wins = $wins;
        $this->winRate = $winRate;
        $this->kdRatio = $kdRatio;
        $this->averageKdRatio = $averageKdRatio;
        $this->headshots = $headshots;
        $this->averageHeadshots = $averageHeadshots;
    }

    public static function createFromApi(array $body): self
    {

    }

    public function getMatches(): int
    {
        return $this->matches;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getWinRate(): float
    {
        return $this->winRate;
    }

    public function getKdRatio(): float
    {
        return $this->kdRatio;
    }

    public function getAverageKdRatio(): float
    {
        return $this->averageKdRatio;
    }

    public function getHeadshots(): int
    {
        return $this->headshots;
    }

    public function getAverageHeadshots(): float
    {
        return $this->averageHeadshots;
    }
}
