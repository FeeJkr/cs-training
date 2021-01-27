<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

interface FaceitPlayerRepository
{
    public function userExists(string $nickname): bool;
    public function add(FaceitPlayer $player): void;
    public function getByNickname(string $nickname): FaceitPlayer;
    public function addStatistics(Id $playerId, FaceitPlayerStatistics $statistics): void;
    public function updateStatistics(FaceitPlayer $player): void;
    public function getAllPlayersNicknames(): array;
    public function addGameInformation(Id $playerId, FaceitPlayerGame $game): void;
    public function updateGameInformation(FaceitPlayer $player): void;
    public function getMonthStatistics(FaceitPlayer $player): FaceitPlayerStatistics;
}
