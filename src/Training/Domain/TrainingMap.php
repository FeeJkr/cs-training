<?php
declare(strict_types=1);

namespace App\Training\Domain;

final class TrainingMap
{
    private Id $id;
    private string $name;

    public function __construct(Id $id, string $name)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public static function create(string $mapName): self
    {
        return new self(
            Id::nullable(),
            $mapName
        );
    }

    public static function fromRow(array $data): self
    {
        return new self(
            Id::fromInt((int)$data['map_id']),
            $data['map_name']
        );
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
