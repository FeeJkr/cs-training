<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

use DateTime;
use DateTimeInterface;
use function array_sum;

final class FaceitPlayerMatchResultsCollection
{
    private array $matches;

    public function __construct(FaceitPlayerMatchResult ...$matches)
    {
        $this->matches = $matches;
    }

    public static function createFromRows(array $rows): self
    {
        $matches = [];

        foreach ($rows as $row) {
            $matches[] = FaceitPlayerMatchResult::createFromRow($row);
        }

        return new self(...$matches);
    }

    public function getTodayMatches(): self
    {
        $today = new DateTime();
        $matches = [];

        foreach ($this->getMatches() as $match) {
            if ($this->isTodayMatch($today, $match->getFinishedAt())) {
                $matches[] = $match;
            }
        }

        return new self(...$matches);
    }

    public function getYesterdayMatches(): self
    {
        $today = new DateTime();
        $matches = [];

        foreach ($this->getMatches() as $match) {
            if ($this->isYesterdayMatch($today, $match->getFinishedAt())) {
                $matches[] = $match;
            }
        }

        return new self(...$matches);
    }

    public function getThisMonthMatches(): self
    {
        $today = new DateTime();
        $matches = [];

        foreach ($this->getMatches() as $match) {
            if ($this->isThisMonthMatch($today, $match->getFinishedAt())) {
                $matches[] = $match;
            }
        }

        return new self(...$matches);
    }

    private function isTodayMatch(DateTimeInterface $today, DateTimeInterface $matchDay): bool
    {
        return $matchDay->diff($today)->days === 0;
    }

    private function isYesterdayMatch(DateTimeInterface $today, DateTimeInterface $matchDay): bool
    {
        return $matchDay->diff($today)->days === 1;
    }

    private function isThisMonthMatch(DateTimeInterface $today, DateTimeInterface $matchDay): bool
    {
        return $today->format('m.y') === $matchDay->format('m.y');
    }

    public function getTotalCount(): int
    {
        return count($this->getMatches());
    }

    public function getWins(): int
    {
        $wins = 0;

        foreach ($this->getMatches() as $match) {
            if ($match->isWin()) {
                $wins++;
            }
        }

        return $wins;
    }

    public function getLoses(): int
    {
        return $this->getTotalCount() - $this->getWins();
    }

    public function getAverageKd(): float
    {
        $kd = [];

        foreach ($this->getMatches() as $match) {
            $kd[] = $match->getKdRatio();
        }

        if (count($kd) === 0) {
            return 0.0;
        }

        return round(array_sum($kd) / count($kd), 2);
    }

    public function getMatches(): array
    {
        return $this->matches;
    }
}
