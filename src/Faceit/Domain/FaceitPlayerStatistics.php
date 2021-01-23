<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayerStatistics
{
    private Id $id;
    private int $matches;
    private int $wins;
    private float $winRate;
    private float $kdRatio;
    private float $averageKdRatio;
    private int $headshots;
    private float $averageHeadshots;
    private FaceitPlayerStatisticsSegmentsCollection $segmentsCollection;

    public function __construct(
        Id $id,
        int $matches,
        int $wins,
        float $winRate,
        float $kdRatio,
        float $averageKdRatio,
        int $headshots,
        float $averageHeadshots,
        FaceitPlayerStatisticsSegmentsCollection $segmentsCollection
    ) {
        $this->id = $id;
        $this->matches = $matches;
        $this->wins = $wins;
        $this->winRate = $winRate;
        $this->kdRatio = $kdRatio;
        $this->averageKdRatio = $averageKdRatio;
        $this->headshots = $headshots;
        $this->averageHeadshots = $averageHeadshots;
        $this->segmentsCollection = $segmentsCollection;
    }

    public static function createFromApi(array $body): self
    {
        return new self(
            Id::nullable(),
            (int) $body['lifetime']['Matches'],
            (int) $body['lifetime']['Wins'],
            (float) $body['lifetime']['Win Rate %'],
            (float) $body['lifetime']['K/D Ratio'],
            (float) $body['lifetime']['Average K/D Ratio'],
            (int) $body['lifetime']['Total Headshots %'],
            (float) $body['lifetime']['Average Headshots %'],
            FaceitPlayerStatisticsSegmentsCollection::createFromApi($body['segments'])
        );
    }

    public static function createFromRow(array $row): self
    {
        return new self(
            Id::fromInt($row['statistics_id']),
            (int) $row['statistics_matches'],
            (int) $row['statistics_wins'],
            (float) $row['statistics_win_rate'],
            (float) $row['statistics_kd_ratio'],
            (float) $row['statistics_average_kd_ratio'],
            (int) $row['statistics_headshots'],
            (float) $row['statistics_average_headshots'],
            FaceitPlayerStatisticsSegmentsCollection::createFromRows($row['segments'])
        );
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

    public function getId(): Id
    {
        return $this->id;
    }

    public function getSegmentsCollection(): FaceitPlayerStatisticsSegmentsCollection
    {
        return $this->segmentsCollection;
    }
}
