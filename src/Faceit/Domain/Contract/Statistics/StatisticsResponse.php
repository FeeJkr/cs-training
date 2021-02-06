<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Statistics;

final class StatisticsResponse
{
    public function __construct(
        private SegmentsCollectionResponse $segments,
        private string $playerId,
        private int $matches,
        private int $wins,
        private float $winRate,
        private float $totalKdRatio,
        private float $averageKdRatio,
        private int $totalHeadshotsPercentage,
        private int $averageHeadshotsPercentage
    ){}

    public static function createFromResponse(array $response): self
    {
        return new self(
            SegmentsCollectionResponse::createFromResponse($response['segments']),
            $response['player_id'],
            (int) $response['lifetime']['Matches'],
            (int) $response['lifetime']['Wins'],
            (float) $response['lifetime']['Win Rate %'],
            (float) $response['lifetime']['K/D Ratio'],
            (float) $response['lifetime']['Average K/D Ratio'],
            (int) $response['lifetime']['Total Headshots %'],
            (int) $response['lifetime']['Average Headshots %']
        );
    }

    public function getSegments(): SegmentsCollectionResponse
    {
        return $this->segments;
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

    public function getTotalKdRatio(): float
    {
        return $this->totalKdRatio;
    }

    public function getAverageKdRatio(): float
    {
        return $this->averageKdRatio;
    }

    public function getTotalHeadshotsPercentage(): int
    {
        return $this->totalHeadshotsPercentage;
    }

    public function getAverageHeadshotsPercentage(): int
    {
        return $this->averageHeadshotsPercentage;
    }
}
