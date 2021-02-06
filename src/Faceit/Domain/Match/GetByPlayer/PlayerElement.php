<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match\GetByPlayer;

use JetBrains\PhpStorm\Pure;

final class PlayerElement
{
    private const GOOD_KD_RATIO = 1.0;
    private const GOOD_KR_RATIO = 0.7;

    public function __construct(
        private string $faceitId,
        private string $nickname,
        private int $kills,
        private int $assists,
        private int $deaths,
        private int $headshots,
        private int $headshotsPercentage,
        private int $tripleKills,
        private int $quadroKills,
        private int $pentaKills,
        private int $mvps,
        private float $kdRatio,
        private float $krRatio
    ){}

    #[Pure]
    public static function createFromRow(array $row): self
    {
        return new self(
            $row['player_faceit_id'],
            $row['player_nickname'],
            (int) $row['player_kills'],
            (int) $row['player_assists'],
            (int) $row['player_deaths'],
            (int) $row['player_headshots'],
            (int) $row['player_headshots_percentage'],
            (int) $row['player_triple_kills'],
            (int) $row['player_quadro_kills'],
            (int) $row['player_penta_kills'],
            (int) $row['player_mvps'],
            (float) $row['player_kd_ratio'],
            (float) $row['player_kr_ratio']
        );
    }

    public function getFaceitId(): string
    {
        return $this->faceitId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
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

    public function getHeadshotsPercentage(): int
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

    public function isGoodKdRatio(): bool
    {
        return $this->kdRatio >= self::GOOD_KD_RATIO;
    }

    public function isGoodKrRatio(): bool
    {
        return $this->krRatio >= self::GOOD_KR_RATIO;
    }
}
