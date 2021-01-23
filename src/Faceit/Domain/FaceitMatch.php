<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

use DateTime;
use DateTimeInterface;

final class FaceitMatch
{
    private Id $id;
    private FaceitMatchTeam $firstTeam;
    private FaceitMatchTeam $secondTeam;
    private string $faceitId;
    private string $gameMode;
    private string $competitionType;
    private string $status;
    private DateTimeInterface $startedAt;
    private DateTimeInterface $finishedAt;
    private string $faceitUrl;
    private string $map;
    private int $rounds;

    public function __construct(
        Id $id,
        FaceitMatchTeam $firstTeam,
        FaceitMatchTeam $secondTeam,
        string $faceitId,
        string $gameMode,
        string $competitionType,
        string $status,
        string $map,
        int $rounds,
        DateTimeInterface $startedAt,
        DateTimeInterface $finishedAt,
        string $faceitUrl
    ) {
        $this->id = $id;
        $this->firstTeam = $firstTeam;
        $this->secondTeam = $secondTeam;
        $this->faceitId = $faceitId;
        $this->gameMode = $gameMode;
        $this->competitionType = $competitionType;
        $this->status = $status;
        $this->map = $map;
        $this->rounds = $rounds;
        $this->startedAt = $startedAt;
        $this->finishedAt = $finishedAt;
        $this->faceitUrl = $faceitUrl;
    }

    public static function createFromApi(array $body, FaceitApi $faceitApi): self
    {
        $matchInformation = $faceitApi->getMatchInformation($body['match_id'])['rounds'][0];

        return new self(
            Id::nullable(),
            FaceitMatchTeam::createFromApi($matchInformation['teams'][0]),
            FaceitMatchTeam::createFromApi($matchInformation['teams'][1]),
            $body['match_id'],
            $body['game_mode'],
            $body['competition_type'],
            $body['status'],
            $matchInformation['round_stats']['Map'],
            (int) $matchInformation['round_stats']['Rounds'],
            (new DateTime)->setTimestamp($body['started_at']),
            (new DateTime)->setTimestamp($body['finished_at']),
            str_replace('{lang}', 'ru', $body['faceit_url'])
        );
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getFirstTeam(): FaceitMatchTeam
    {
        return $this->firstTeam;
    }

    public function getSecondTeam(): FaceitMatchTeam
    {
        return $this->secondTeam;
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

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function getFaceitUrl(): string
    {
        return $this->faceitUrl;
    }
}
