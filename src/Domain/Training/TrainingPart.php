<?php
declare(strict_types=1);

namespace App\Domain\Training;

use JetBrains\PhpStorm\Pure;

final class TrainingPart
{
    public function __construct(
        private TrainingMode $mode,
        private int $value,
        private string $name,
        private TrainingMap $map,
        private bool $isEnded,
    ) {}

    #[Pure]
    public static function createFromRow(array $data): self
    {
        return new self(
            new TrainingMode($data['training_part_mode']),
            (int) $data['training_part_value'],
            $data['training_part_name'],
            new TrainingMap((int)$data['map_id'], $data['map_name']),
            $data['training_part_is_ended']
        );
    }

    public function getMode(): TrainingMode
    {
        return $this->mode;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMap(): TrainingMap
    {
        return $this->map;
    }

    public function isEnded(): bool
    {
        return $this->isEnded;
    }
}
