<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Match;

final class MatchPlayersCollectionResponse
{
    private array $matchPlayers;

    public function __construct(MatchPlayerResponse ...$matchPlayers)
    {
        $this->matchPlayers = $matchPlayers;
    }

    public static function createFromResponse(array $response): self
    {
        $players = [];

        foreach ($response as $player) {
            $players[] = MatchPlayerResponse::createFromResponse($player);
        }

        return new self(...$players);
    }

    public function toArray(): array
    {
        return $this->matchPlayers;
    }
}
