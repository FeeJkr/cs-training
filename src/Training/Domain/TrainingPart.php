<?php
declare(strict_types=1);

namespace App\Training\Domain;

final class TrainingPart
{
    private Id $id;
    private TrainingMode $mode;
    private TrainingMap $map;
    private string $name;
    private int $value;
    private bool $isEnded;

    public function __construct(
        Id $id,
        TrainingMode $mode,
        TrainingMap $map,
        string $name,
        int $value,
        bool $isEnded
    ) {
        $this->id = $id;
        $this->mode = $mode;
        $this->map = $map;
        $this->name = $name;
        $this->value = $value;
        $this->isEnded = $isEnded;
    }

    public static function create(
        TrainingMode $mode,
        string $mapName,
        string $name,
        int $value
    ): self {
        return new self(
            Id::nullable(),
            $mode,
            TrainingMap::create($mapName),
            $name,
            $value,
            false
        );
    }

    public static function fromRow(array $data): self
    {
        return new self(
            Id::fromInt((int) $data['training_part_id']),
            new TrainingMode($data['training_part_mode']),
            TrainingMap::fromRow($data),
            $data['training_part_name'],
            (int) $data['training_part_value'],
            $data['training_part_is_ended']
        );
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getMode(): TrainingMode
    {
        return $this->mode;
    }

    public function getMap(): TrainingMap
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

    public function isEnded(): bool
    {
        return $this->isEnded;
    }
}
