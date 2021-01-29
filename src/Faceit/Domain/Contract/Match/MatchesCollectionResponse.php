<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Match;

final class MatchesCollectionResponse
{
    private array $matches;

    public function __construct(MatchResponse ...$matches)
    {
        $this->matches = $matches;
    }

    public static function createFromResponse(array $response): self
    {
        $matches = [];

        foreach ($response as $match) {
            $matches[] = MatchResponse::createFromResponse($match);
        }

        return new self(...$matches);
    }

    public function toArray(): array
    {
        return $this->matches;
    }
}
