<?php
declare(strict_types=1);

namespace App\Training\Domain;

final class TrainingPartDetails
{
    private string $mode;
    private int $mapId;
    private string $name;
    private int $value;

    public function __construct(string $mode, int $mapId, string $name, int $value)
    {
        $this->mode = $mode;
        $this->mapId = $mapId;
        $this->name = $name;
        $this->value = $value;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getMapId(): int
    {
        return $this->mapId;
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
