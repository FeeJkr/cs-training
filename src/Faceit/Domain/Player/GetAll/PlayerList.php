<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Player\GetAll;

final class PlayerList
{
    private array $elements;

    public function __construct(PlayerElement ...$elements)
    {
        $this->elements = $elements;
    }

    public function toArray(): array
    {
        return $this->elements;
    }
}
