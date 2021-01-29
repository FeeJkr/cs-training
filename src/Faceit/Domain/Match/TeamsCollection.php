<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

final class TeamsCollection
{
    private array $teams;

    public function __construct(Team ...$teams)
    {
        $this->teams = $teams;
    }

    public function first(): Team
    {
        return $this->teams[0];
    }

    public function toArray(): array
    {
        return $this->teams;
    }
}
