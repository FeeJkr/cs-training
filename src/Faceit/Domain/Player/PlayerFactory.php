<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Player;

use App\Faceit\Domain\Contract\Player\PlayerResponse;
use App\Faceit\Domain\Id;

final class PlayerFactory
{
    public function createFromResponse(PlayerResponse $response): Player
    {
        return new Player(
            Id::nullable(),
            $response->getPlayerId(),
            $response->getNickname(),
            $response->getAvatar(),
            $response->getFaceitUrl(),
            $response->getSkillLevel(),
            $response->getFaceitElo()
        );
    }

    public function createFromRow(array $row): Player
    {
        return new Player(
            Id::fromInt($row['id']),
            $row['faceit_id'],
            $row['nickname'],
            $row['avatar'],
            $row['faceit_url'],
            (int) $row['skill_level'],
            (int) $row['faceit_elo']
        );
    }
}
