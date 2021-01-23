<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

interface FaceitApi
{
    public function getPlayerInformationByNickname(string $nickname): array;
    public function getMatches(string $faceitId, int $limit, int $offset): array;
    public function getMatchInformation(string $matchId): array;
    public function getPlayerStatistics(string $faceitId): array;
}
