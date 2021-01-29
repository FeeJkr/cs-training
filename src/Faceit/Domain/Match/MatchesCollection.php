<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use DateInterval;
use DateTime;

final class MatchesCollection
{
    private array $matches;

    public function __construct(Match ...$matches)
    {
        $this->matches = $matches;
    }

    public function getTodayMatches(): self
    {
        $today = (new DateTime())->format('ymd');
        $matches = [];

        foreach ($this->matches as $match) {
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

        foreach ($this->matches as $match) {
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

        foreach ($this->matches as $match) {
            if ($match->getFinishedAt()->format('ym') === $month) {
                $matches[] = $match;
            }
        }

        return new self(...$matches);
    }

    public function getTotalMatches(): int
    {
        return count($this->matches);
    }

    public function getWins(): int
    {
        $wins = 0;

        foreach ($this->matches as $match) {
            if ($match->getTeams()->toArray()[0]->isWinner()) {
                $wins++;
            }
        }

        return $wins;
    }

    public function getLoses(): int
    {
        return $this->getTotalMatches() - $this->getWins();
    }

    public function getAverageKd(): float
    {
        $totalKdRatio = 0.0;

        foreach ($this->matches as $match) {
            $totalKdRatio += $match->getTeams()->toArray()[0]->getPlayers()->toArray()[0]->getKdRatio();
        }

        return round($totalKdRatio / $this->getTotalMatches(), 2);
    }

    public function toArray(): array
    {
        return $this->matches;
    }
}
