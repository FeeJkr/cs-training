<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match\GetByPlayer;

use DateInterval;
use DateTime;

final class MatchList
{
    private array $elements;

    public function __construct(MatchElement ...$elements)
    {
        $this->elements = $elements;
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function first(): MatchElement
    {
        return $this->elements[0];
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function getTodayMatches(): self
    {
        $today = (new DateTime())->format('ymd');
        $matches = [];

        foreach ($this->elements as $match) {
            if ($match->getFinishedAt()->format('ymd') === $today) {
                $matches[] = $match;
            }
        }

        return new self(...$matches);
    }

    public function getYesterdayMatches(): self
    {
        $yesterday = (new DateTime())->sub(new DateInterval('P1D'))->format('ymd');
        $matches = [];

        foreach ($this->elements as $match) {
            if ($match->getFinishedAt()->format('ymd') === $yesterday) {
                $matches[] = $match;
            }
        }

        return new self(...$matches);
    }

    public function getMonthMatches(): self
    {
        $month = (new DateTime())->format('ym');
        $matches = [];

        foreach ($this->elements as $match) {
            if ($match->getFinishedAt()->format('ym') === $month) {
                $matches[] = $match;
            }
        }

        return new self(...$matches);
    }

    /**
     * @return MatchList[]
     */
    public function groupByMap(): array
    {
        $grouped = [];

        foreach ($this->elements as $element) {
            $grouped[$element->getMap()][] = $element;
        }

        return array_map(static fn(array $elements): MatchList => new MatchList(...$elements), $grouped);
    }

    public function getTotalMatches(): int
    {
        return count($this->elements);
    }

    public function getWins(): int
    {
        $wins = 0;

        foreach ($this->elements as $element) {
            if ($element->isWin()) {
                $wins++;
            }
        }

        return $wins;
    }

    public function getLoses(): int
    {
        return $this->getTotalMatches() - $this->getWins();
    }

    public function getWinRate(): int
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return (int) round(($this->getWins() / $this->getTotalMatches()) * 100);
    }

    public function getTotalKdRatio(): float
    {
        $kdRatio = 0.0;

        foreach ($this->elements as $element) {
            $kdRatio += $element->getKdRatio();
        }

        return $kdRatio;
    }

    public function getAverageKdRatio(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getTotalKdRatio() / $this->getTotalMatches(), 2);
    }

    public function getTotalKrRatio(): float
    {
        $krRatio = 0.0;

        foreach ($this->elements as $element) {
            $krRatio += $element->getKrRatio();
        }

        return $krRatio;
    }

    public function getAverageKrRatio(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getTotalKrRatio() / $this->getTotalMatches(), 2);
    }

    public function getTotalHeadshotsPercentage(): int
    {
        $headshotsPercentage = 0;

        foreach ($this->elements as $element) {
            $headshotsPercentage += $element->getHeadshotsPercentage();
        }

        return (int) round($headshotsPercentage);
    }

    public function getAverageHeadshotsPercentage(): int
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return (int) round($this->getTotalHeadshotsPercentage() / $this->getTotalMatches());
    }

    public function getKills(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getKills();
        }

        return $counter;
    }

    public function getAssists(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getAssists();
        }

        return $counter;
    }

    public function getDeaths(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getDeaths();
        }

        return $counter;
    }

    public function getAverageKills(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getKills() / $this->getTotalMatches(), 2);
    }

    public function getAverageAssists(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getAssists() / $this->getTotalMatches(), 2);
    }

    public function getAverageDeaths(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getDeaths() / $this->getTotalMatches(), 2);
    }

    public function getTotalHeadshots(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getHeadshots();
        }

        return $counter;
    }

    public function getHeadshotsPerMatch(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getTotalHeadshots() / $this->getTotalMatches(), 2);
    }

    public function getTripleKills(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getTripleKills();
        }

        return $counter;
    }

    public function getQuadroKills(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getQuadroKills();
        }

        return $counter;
    }

    public function getPentaKills(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getPentaKills();
        }

        return $counter;
    }

    public function getAverageTripleKills(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getTripleKills() / $this->getTotalMatches(), 2);
    }

    public function getAverageQuadroKills(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getQuadroKills() / $this->getTotalMatches(), 2);
    }

    public function getAveragePentaKills(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getPentaKills() / $this->getTotalMatches(), 2);
    }

    public function getTotalMvps(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getMvps();
        }

        return $counter;
    }

    public function getAverageMvps(): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getTotalMvps() / $this->getTotalMatches(), 2);
    }

    public function getTotalRounds(): int
    {
        $counter = 0;

        foreach ($this->elements as $element) {
            $counter += $element->getRounds();
        }

        return $counter;
    }
}
