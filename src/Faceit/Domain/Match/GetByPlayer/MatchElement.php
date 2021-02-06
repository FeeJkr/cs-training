<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match\GetByPlayer;

use DateTime;
use DateTimeInterface;
use Exception;

final class MatchElement
{
    public function __construct(
        private int $id,
        private string $faceitId,
        private TeamList $teams,
        private string $mode,
        private string $map,
        private string $score,
        private string $faceitUrl,
        private DateTimeInterface $finishedAt
    ){}

    public static function createFromRow(array $row): self
    {
        $matchInformation = array_values($row['teams'])[0][0];

        return new self(
            $matchInformation['match_id'],
            $matchInformation['match_faceit_id'],
            TeamList::createFromRows($row['teams']),
            $matchInformation['match_game_mode'],
            $matchInformation['match_map'],
            $matchInformation['match_score'],
            $matchInformation['match_url'],
            DateTime::createFromFormat('Y-m-d H:i:s', $matchInformation['match_finished_at'])
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFaceitId(): string
    {
        return $this->faceitId;
    }

    public function getTeams(): TeamList
    {
        return $this->teams;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getMap(): string
    {
        return $this->map;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function getFaceitUrl(): string
    {
        return $this->faceitUrl;
    }

    public function getFinishedAt(): DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function getRequestedPlayer(string $playerFaceitId): PlayerElement
    {
        foreach ($this->teams->toArray() as $team) {
            foreach ($team->getPlayers()->toArray() as $player) {
                if ($player->getFaceitId() === $playerFaceitId) {
                    return $player;
                }
            }
        }

        throw new Exception('Player not found');
    }

    public function isWin(string $faceitPlayerId): bool
    {
        foreach ($this->teams->toArray() as $team) {
            foreach ($team->getPlayers()->toArray() as $player) {
                if ($player->getFaceitId() === $faceitPlayerId) {
                    return $team->isWinner();
                }
            }
        }

        return false;
    }
}
