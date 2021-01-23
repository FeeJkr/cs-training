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

    public function update(TrainingPartDetailsCollection $detailsCollection): void
    {
        $parts = [];

        foreach ($detailsCollection->getDetails() as $partDetails) {
            $parts[] = TrainingPart::create(
                new TrainingMode($partDetails->getMode()),
                $partDetails->getMapId(),
                $partDetails->getName(),
                $partDetails->getValue()
            );
        }

        $this->parts = $parts;
    }

    public function getParts(): array
    {
        return $this->parts;
    }
}
