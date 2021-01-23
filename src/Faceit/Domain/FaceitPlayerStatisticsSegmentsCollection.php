<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitPlayerStatisticsSegmentsCollection
{
    private array $segments;

    public function __construct(FaceitPlayerStatisticsSegment ...$segments)
    {
        $this->segments = $segments;
    }

    public static function createFromApi(array $body): self
    {
        $segments = [];

        foreach ($body as $segment) {
            $segments[] = FaceitPlayerStatisticsSegment::createFromApi($segment);
        }

        return new self(...$segments);
    }

    public static function createFromRows(array $rows): self
    {
        $segments = [];

        foreach ($rows as $row) {
            $segments[] = FaceitPlayerStatisticsSegment::createFromRow($row);
        }

        return new self(...$segments);
    }

    public function getSegments(): array
    {
        return $this->segments;
    }
}
