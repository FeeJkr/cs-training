<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

use DateTime;
use DateTimeInterface;

final class PlayerMatch
{
    public function __construct(
        private string $playerId,
        private string $mode,
        private string $map,
        private string $score,
        private int $kills,
        private int $assists,
        private int $deaths,
        private int $headshots,
        private float $headshotsPercentage,
        private float $krRatio,
        private float $kdRatio,
        private int $tripleKills,
        private int $quadroKills,
        private int $pentaKills,
        private int $mvps,
        private int $rounds,
        private string $faceitUrl,
        private bool $isWin,
        private DateTimeInterface $finishedAt
    ){}

    public static function createFromRow(array $row): self
    {
        return new self(
            $row['player_id'],
            $row['mode'],
            $row['map'],
            $row['score'],
            (int) $row['kills'],
            (int) $row['assists'],
            (int) $row['deaths'],
            (int) $row['headshots'],
            (float) $row['headshots_percentage'],
            (float) $row['kr_ratio'],
            (float) $row['kd_ratio'],
            (int) $row['triple_kills'],
            (int) $row['quadro_kills'],
            (int) $row['penta_kills'],
            (int) $row['mvps'],
            (int) $row['rounds'],
            $row['faceit_url'],
            $row['is_win'],
            DateTime::createFromFormat('Y-m-d H:i:s', $row['finished_at'])
        );
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getMap(): string
    {
        return $this->map;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function getKills(): int
    {
        return $this->kills;
    }

    public function getAssists(): int
    {
        return $this->assists;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getHeadshots(): int
    {
        return $this->headshots;
    }

    public function getHeadshotsPercentage(): float
    {
        return $this->headshotsPercentage;
    }

    public function getKrRatio(): float
    {
        return $this->krRatio;
    }

    public function getKdRatio(): float
    {
        return $this->kdRatio;
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

    public function getRounds(): int
    {
        return $this->rounds;
    }

    public function getFaceitUrl(): string
    {
        return $this->faceitUrl;
    }

    public function isWin(): bool
    {
        return $this->isWin;
    }

    public function getFinishedAt(): DateTimeInterface
    {
        return $this->finishedAt;
    }
}
