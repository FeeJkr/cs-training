<?php
declare(strict_types=1);

namespace App\Faceit\Domain;

use JetBrains\PhpStorm\Pure;

final class Id
{
    private const NULLABLE_VALUE = 0;

    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    #[Pure]
    public static function fromInt(int $id): self
    {
        return new self($id);
    }

    #[Pure]
    public static function nullable(): self
    {
        return new self(self::NULLABLE_VALUE);
    }

    public function toInt(): int
    {
        return $this->id;
    }
}
