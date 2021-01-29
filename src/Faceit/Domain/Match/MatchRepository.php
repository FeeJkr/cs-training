<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match;

use App\Faceit\Domain\Match\GetByPlayer\MatchList;
use App\Faceit\Domain\Match\GetByPlayer\Scope;

interface MatchRepository
{
    public function add(Match $match): void;
    public function exists(string $faceitId): bool;
    public function getByPlayer(string $playerId, Scope $scope): MatchList;
}
