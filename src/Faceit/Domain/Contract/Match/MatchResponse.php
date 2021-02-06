<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract\Match;

use DateTime;
use DateTimeInterface;

final class MatchResponse
{
    public function __construct(
        private string $matchId,
        private MatchTeamsCollectionResponse $teams,
        private string $gameMode,
        private string $competitionType,
        private string $status,
        private DateTimeInterface $startedAt,
        private DateTimeInterface $finishedAt,
        private string $score,
        private string $faceitUrl,
        private string $map,
        private int $rounds
    ){}

    public static function createFromResponse(array $response): self
    {
        return new self(
            $response['match_id'],
            MatchTeamsCollectionResponse::createFromResponse($response['match_information']['teams']),
            $response['game_mode'],
            $response['competition_type'],
            $response['status'],
            (new DateTime)->setTimestamp($response['started_at']),
            (new DateTime)->setTimestamp($response['finished_at']),
            $response['match_information']['round_stats']['Score'],
            str_replace('{lang}', 'ru', $response['faceit_url']),
            $response['match_information']['round_stats']['Map'],
            (int) $response['match_information']['round_stats']['Rounds']
        );
    }

    public function getMatchId(): string
    {
        return $this->matchId;
    }

    public function getTeams(): MatchTeamsCollectionResponse
    {
        return $this->teams;
    }

    public function getGameMode(): string
    {
        return $this->gameMode;
    }

    public function getCompetitionType(): string
    {
        return $this->competitionType;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function getFaceitUrl(): string
    {
        return $this->faceitUrl;
    }

    public function getMap(): string
    {
        return $this->map;
    }

    public function getRounds(): int
    {
        return $this->rounds;
    }
}
