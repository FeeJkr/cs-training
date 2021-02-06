<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

use App\Faceit\Domain\Id;

final class Statistics
{
    private const GOOD_WIN_RATE = 50;
    private const GOOD_AVERAGE_KD_RATIO = 1.0;
    private const GOOD_AVERAGE_HEADSHOTS = 40.0;

    public function __construct(
        private Id $id,
        private StatisticsType $type,
        private string $playerId,
        private int $matches,
        private int $wins,
        private float $winRate,
        private float $kdRatio,
        private float $averageKdRatio,
        private int $headshots,
        private int $averageHeadshots,
        private StatisticsSegmentsCollection $segments
    ){}

    public function getId(): Id
    {
        return $this->id;
    }

    public function getType(): StatisticsType
    {
        return $this->type;
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
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

    public function isGoodWinRate(): bool
    {
        return $this->getWinRate() > self::GOOD_WIN_RATE;
    }

    public function getKdRatio(): float
    {
        return $this->kdRatio;
    }

    public function getAverageKdRatio(): float
    {
        return $this->averageKdRatio;
    }

    public function isGoodAverageKdRatio(): bool
    {
        return $this->getAverageKdRatio() >= self::GOOD_AVERAGE_KD_RATIO;
    }

    public function getHeadshots(): int
    {
        return $this->headshots;
    }

    public function getAverageHeadshots(): int
    {
        return $this->averageHeadshots;
    }

    public function isGoodAverageHeadshots(): bool
    {
        return $this->getAverageHeadshots() >= self::GOOD_AVERAGE_HEADSHOTS;
    }

    public function getSegments(): StatisticsSegmentsCollection
    {
        return $this->segments;
    }
}
