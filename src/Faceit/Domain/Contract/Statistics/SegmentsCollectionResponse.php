<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Statistics;

final class SegmentsCollectionResponse
{
    private array $segments;

    public function __construct(SegmentResponse ...$segments)
    {
        $this->segments = $segments;
    }

    public static function createFromResponse(array $response): self
    {
        $segments = [];

        foreach ($response as $segment) {
            $segments[] = SegmentResponse::createFromResponse($segment);
        }

        return new self(...$segments);
    }

    public function toArray(): array
    {
        return $this->segments;
    }
}
