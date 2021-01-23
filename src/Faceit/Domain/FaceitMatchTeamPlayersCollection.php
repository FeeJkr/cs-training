<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

final class FaceitMatchTeamPlayersCollection
{
    private array $players;

    public function __construct(FaceitMatchTeamPlayer ...$players)
    {
        $this->players = $players;
    }

    public static function createFromApi(array $body): self
    {
        $players = [];

        foreach ($body as $player) {
            $players[] = FaceitMatchTeamPlayer::createFromApi($player);
        }

        return new self(...$players);
    }

    public function getPlayers(): array
    {
        return $this->players;
    }
}
