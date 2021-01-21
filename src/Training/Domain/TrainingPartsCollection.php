<?php
declare(strict_types=1);

namespace App\Training\Domain;

final class TrainingPartsCollection
{
    private array $parts;

    public function __construct(TrainingPart ...$parts)
    {
        $this->parts = $parts;
    }

    public function update(TrainingPartsCollection $partsCollection): void
    {

    }

    public function getParts(): array
    {
        return $this->parts;
    }
}
