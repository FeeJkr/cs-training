<?php
declare(strict_types=1);

namespace App\Training\Domain;

use App\Training\Domain\TrainingMode;

final class TrainingPartDetails
{
    private string $mode;
    private string $map;
    private string $name;
    private int $value;

    public function __construct(string $mode, string $map, string $name, int $value)
    {
        $this->mode = $mode;
        $this->map = $map;
        $this->name = $name;
        $this->value = $value;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getMap(): string
    {
        return $this->map;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
