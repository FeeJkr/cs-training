<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use App\Faceit\Domain\Id;
use DateTimeInterface;

final class Match
{
    private const FINISHED_STATUS = 'finished';

    private Id $id;
    private TeamsCollection $teams;
    private string $faceitId;
    private string $gameMode;
    private string $competitionType;
    private string $status;
    private string $map;
    private int $rounds;
    private string $score;
    private string $faceitUrl;
    private DateTimeInterface $startedAt;
    private DateTimeInterface $finishedAt;

    public function __construct(
        Id $id,
        TeamsCollection $teams,
        string $faceitId,
        string $gameMode,
        string $competitionType,
        string $status,
        string $map,
        int $rounds,
        string $score,
        string $faceitUrl,
        DateTimeInterface $startedAt,
        DateTimeInterface $finishedAt
    ) {
        $this->id = $id;
        $this->teams = $teams;
        $this->faceitId = $faceitId;
        $this->gameMode = $gameMode;
        $this->competitionType = $competitionType;
        $this->status = $status;
        $this->map = $map;
        $this->rounds = $rounds;
        $this->score = $score;
        $this->faceitUrl = $faceitUrl;
        $this->startedAt = $startedAt;
        $this->finishedAt = $finishedAt;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getTeams(): TeamsCollection
    {
        return $this->teams;
    }

    public function getFaceitId(): string
    {
        return $this->faceitId;
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

    public function getMap(): string
    {
        return $this->map;
    }

    public function getRounds(): int
    {
        return $this->rounds;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function getFaceitUrl(): string
    {
        return $this->faceitUrl;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function isFinished(): bool
    {
        return $this->getStatus() === self::FINISHED_STATUS;
    }
}
