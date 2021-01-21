<?php
declare(strict_types=1);

namespace App\Training\Application;

final class TrainingPartDetailsCollection
{
    private array $details;

    public function __construct(TrainingPartDetails ...$details)
    {
        $this->details = $details;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
