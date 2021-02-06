<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use DateInterval;
use DateTime;

final class MatchesCollection
{
    private array $matches;

    public function __construct(FaceitMatch ...$matches)
    {
        $this->matches = $matches;
    }

    public function toArray(): array
    {
        return $this->matches;
    }
}
