<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match\GetByPlayer;

final class PlayerList
{
    private array $players;

    public function __construct(PlayerElement ...$players)
    {
        $this->players = $players;
    }

    public static function createFromRows(array $rows): self
    {
        $players = [];

        foreach ($rows as $row) {
            $players[] = PlayerElement::createFromRow($row);
        }

        return new self(...$players);
    }

    public function toArray(): array
    {
        return $this->players;
    }
}
