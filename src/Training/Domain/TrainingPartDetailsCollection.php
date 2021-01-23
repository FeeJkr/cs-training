<?php
declare(strict_types=1);

namespace App\Training\Domain;

final class TrainingPartDetailsCollection
{
    private array $details;

    public function __construct(TrainingPartDetails ...$details)
    {
        $this->details = $details;
    }

    public static function createFromArray(array $data): self
    {
        $partDetails = [];

        foreach ($data as $part) {
            $partDetails[] = new TrainingPartDetails(
                $part['mode'],
                (int) $part['mapId'],
                $part['name'],
                (int) $part['value']
            );
        }

        return new self(...$partDetails);
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
