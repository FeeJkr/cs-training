<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

interface StatisticsRepository
{
    public function save(Statistics $statistics): void;
    public function getAllByPlayer(string $playerId): StatisticsCollection;
    public function getPlayerMatchesByMonth(string $playerId): PlayerMatches;
}
