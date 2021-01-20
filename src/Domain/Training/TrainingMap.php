<?php
declare(strict_types=1);

namespace App\Domain\Training;

final class TrainingMap
{
    private ?int $id;
    private string $name;

    public function __construct(?int $id, string $name) {
        $this->name = $name;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
