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

    public function updateFromApi(array $body): void
    {
        foreach ($body as $key => $segment) {
            foreach ($this->getSegments() as $segmentInCollection) {
                if ($segment['label'] === $segmentInCollection->getLabel()) {
                    $segmentInCollection->updateFromApi($segment);
                    unset($body[$key]);
                }
            }
        }

        foreach ($body as $segment) {
            $this->add(FaceitPlayerStatisticsSegment::createFromApi($segment));
        }
    }

    public function add(FaceitPlayerStatisticsSegment $segment): void
    {
        $this->segments[] = $segment;
    }

    public function getSegments(): array
    {
        return $this->segments;
    }
}
