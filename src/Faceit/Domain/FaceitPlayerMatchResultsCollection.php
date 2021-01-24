<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

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

    public function getMatches(): array
    {
        return $this->matches;
    }
}
