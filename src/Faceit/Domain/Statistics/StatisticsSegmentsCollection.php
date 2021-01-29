<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

final class StatisticsSegmentsCollection
{
    private array $segments;

    public function __construct(StatisticsSegment ...$segments)
    {
        $this->segments = $segments;
    }

    public function isEmpty(): bool
    {
        return empty($this->segments);
    }

    public function toArray(): array
    {
        return $this->segments;
    }
}
