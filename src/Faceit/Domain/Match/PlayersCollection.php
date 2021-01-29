<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

final class PlayersCollection
{
    private array $players;

    public function __construct(Player ...$players)
    {
        $this->players = $players;
    }

    public function first(): Player
    {
        return $this->players[0];
    }

    public function toArray(): array
    {
        return $this->players;
    }
}
