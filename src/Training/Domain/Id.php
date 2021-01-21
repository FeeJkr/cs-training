<?php
declare(strict_types=1);

namespace App\Training\Domain;

final class Id
{
    private const NULLABLE_VALUE = 0;

    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromInt(int $id): self
    {
        return new self($id);
    }

    public static function nullable(): self
    {
        return new self(self::NULLABLE_VALUE);
    }

    public function toInt(): int
    {
        return $this->id;
    }

    public function isNull(): bool
    {
        return $this->id === self::NULLABLE_VALUE;
    }
}
