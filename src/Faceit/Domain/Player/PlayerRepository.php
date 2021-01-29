<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Player;

use App\Faceit\Domain\Player\GetAll\PlayerList;

interface PlayerRepository
{
    public function userExists(string $nickname): bool;
    public function add(Player $player): void;
    public function getAll(): PlayerList;
}
