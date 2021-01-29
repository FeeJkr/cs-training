<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

final class StatisticsCollection
{
    private array $statistics;

    public function __construct(Statistics ...$statistics)
    {
        $this->statistics = $statistics;
    }

    public function toArray(): array
    {
        return $this->statistics;
    }
}
