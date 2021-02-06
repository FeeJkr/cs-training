<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match\GetByPlayer;

final class TeamList
{
    private array $teams;

    public function __construct(TeamElement ...$teams)
    {
        $this->teams = $teams;
    }

    public static function createFromRows(array $rows): self
    {
        $teams = [];

        foreach ($rows as $row) {
            $teams[] = TeamElement::createFromRow($row);
        }

        return new self(...$teams);
    }

    public function toArray(): array
    {
        return $this->teams;
    }
}
