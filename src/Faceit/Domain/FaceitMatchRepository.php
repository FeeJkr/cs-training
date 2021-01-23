<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

interface FaceitMatchRepository
{
    public function countByPlayer(FaceitPlayer $player): int;
    public function add(FaceitMatch $match): void;
    public function matchExists(string $faceitId): bool;
}
