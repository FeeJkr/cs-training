<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

interface FaceitPlayerRepository
{
    public function userExists(string $nickname): bool;
    public function add(FaceitPlayer $player): void;
    public function getByNickname(string $nickname): FaceitPlayer;
}
