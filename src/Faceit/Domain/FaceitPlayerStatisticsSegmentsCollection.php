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

    }

    public function getSegments(): array
    {
        return $this->segments;
    }
}
