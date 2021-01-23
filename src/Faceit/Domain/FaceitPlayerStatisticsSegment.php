<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayerStatisticsSegment
{
    private Id $id;
    private string $type;
    private string $mode;
    private string $label;
    private string $image;
    private int $kills;
    private float $averageKills;
    private int $assists;
    private float $averageAssists;
    private int $deaths;
    private float $averageDeaths;
    private int $headshots;
    private int $totalHeadshots;
    private float $averageHeadshots;
    private float $headshotsPerMatch;
    private float $krRatio;
    private float $averageKrRatio;
    private float $kdRatio;
    private float $averageKdRatio;
    private int $tripleKills;
    private int $quadroKills;
    private int $pentaKills;
    private float $averageTripleKills;
    private float $averageQuadroKills;
    private float $averagePentaKills;
    private int $mvps;
    private float $averageMvps;
    private int $matches;
    private int $rounds;
    private int $wins;
    private float $winRate;

    public function __construct(
        Id $id,
        string $type,
        string $mode,
        string $label,
        string $image,
        int $kills,
        float $averageKills,
        int $assists,
        float $averageAssists,
        int $deaths,
        float $averageDeaths,
        int $headshots,
        int $totalHeadshots,
        float $averageHeadshots,
        float $headshotsPerMatch,
        float $krRatio,
        float $averageKrRatio,
        float $kdRatio,
        float $averageKdRatio,
        int $tripleKills,
        int $quadroKills,
        int $pentaKills,
        float $averageTripleKills,
        float $averageQuadroKills,
        float $averagePentaKills,
        int $mvps,
        float $averageMvps,
        int $matches,
        int $rounds,
        int $wins,
        float $winRate
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->mode = $mode;
        $this->label = $label;
        $this->image = $image;
        $this->kills = $kills;
        $this->averageKills = $averageKills;
        $this->assists = $assists;
        $this->averageAssists = $averageAssists;
        $this->deaths = $deaths;
        $this->averageDeaths = $averageDeaths;
        $this->headshots = $headshots;
        $this->totalHeadshots = $totalHeadshots;
        $this->averageHeadshots = $averageHeadshots;
        $this->headshotsPerMatch = $headshotsPerMatch;
        $this->krRatio = $krRatio;
        $this->averageKrRatio = $averageKrRatio;
        $this->kdRatio = $kdRatio;
        $this->averageKdRatio = $averageKdRatio;
        $this->tripleKills = $tripleKills;
        $this->quadroKills = $quadroKills;
        $this->pentaKills = $pentaKills;
        $this->averageTripleKills = $averageTripleKills;
        $this->averageQuadroKills = $averageQuadroKills;
        $this->averagePentaKills = $averagePentaKills;
        $this->mvps = $mvps;
        $this->averageMvps = $averageMvps;
        $this->matches = $matches;
        $this->rounds = $rounds;
        $this->wins = $wins;
        $this->winRate = $winRate;
    }

    public static function createFromApi(array $body): self
    {
        return new self(
            Id::nullable(),
            $body['type'],
            $body['mode'],
            $body['label'],
            $body['img_regular'],
            (int) $body['stats']['Kills'],
            (float) $body['stats']['Average Kills'],
            (int) $body['stats']['Assists'],
            (float) $body['stats']['Average Assists'],
            (int) $body['stats']['Deaths'],
            (float) $body['stats']['Average Deaths'],
            (int) $body['stats']['Headshots'],
            (int) $body['stats']['Total Headshots %'],
            (float) $body['stats']['Average Headshots %'],
            (float) $body['stats']['Headshots per Match'],
            (float) $body['stats']['K/R Ratio'],
            (float) $body['stats']['Average K/R Ratio'],
            (float) $body['stats']['K/D Ratio'],
            (float) $body['stats']['Average K/D Ratio'],
            (int) $body['stats']['Triple Kills'],
            (int) $body['stats']['Quadro Kills'],
            (int) $body['stats']['Penta Kills'],
            (float) $body['stats']['Average Triple Kills'],
            (float) $body['stats']['Average Quadro Kills'],
            (float) $body['stats']['Average Penta Kills'],
            (int) $body['stats']['MVPs'],
            (float) $body['stats']['Average MVPs'],
            (int) $body['stats']['Matches'],
            (int) $body['stats']['Rounds'],
            (int) $body['stats']['Wins'],
            (float) $body['stats']['Win Rate %']
        );
    }

    public static function createFromRow(array $row): self
    {
        return new self(
            Id::fromInt($row['id']),
            $row['type'],
            $row['mode'],
            $row['label'],
            $row['image'],
            (int) $row['kills'],
            (float) $row['average_kills'],
            (int) $row['assists'],
            (float) $row['average_assists'],
            (int) $row['deaths'],
            (float) $row['average_deaths'],
            (int) $row['headshots'],
            (int) $row['total_headshots'],
            (float) $row['average_headshots'],
            (float) $row['headshots_per_match'],
            (float) $row['kr_ratio'],
            (float) $row['average_kr_ratio'],
            (float) $row['kd_ratio'],
            (float) $row['average_kd_ratio'],
            (int) $row['triple_kills'],
            (int) $row['quadro_kills'],
            (int) $row['penta_kills'],
            (float) $row['average_triple_kills'],
            (float) $row['average_quadro_kills'],
            (float) $row['average_penta_kills'],
            (int) $row['mvps'],
            (float) $row['average_mvps'],
            (int) $row['matches'],
            (int) $row['rounds'],
            (int) $row['wins'],
            (float) $row['win_rate']
        );
    }

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
}
