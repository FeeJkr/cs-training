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

    public function getWins(string $playerFaceitId): int
    {
        $wins = 0;

        foreach ($this->elements as $element) {
            foreach ($element->getTeams()->toArray() as $team) {
                foreach ($team->getPlayers()->toArray() as $player) {
                    if ($player->getFaceitId() === $playerFaceitId && $team->isWinner()) {
                        $wins++;
                    }
                }
            }
        }

        return $wins;
    }

    public function getLoses(string $playerFaceitId): int
    {
        return $this->getTotalMatches() - $this->getWins($playerFaceitId);
    }

    public function getAverageKdRatio(string $playerFaceitId): float
    {
        if ($this->getTotalMatches() === 0) {
            return 0;
        }

        return round($this->getTotalKdRatio($playerFaceitId) / $this->getTotalMatches(), 2);
    }

    private function getTotalKdRatio(string $playerFaceitId): float
    {
        $kdRatio = 0.0;

        foreach ($this->elements as $match) {
            foreach ($match->getTeams()->toArray() as $teams) {
                foreach ($teams->getPlayers()->toArray() as $player) {
                    if ($player->getFaceitId() === $playerFaceitId) {
                        $kdRatio += $player->getKdRatio();
                    }
                }
            }
        }

        return $kdRatio;
    }
}
