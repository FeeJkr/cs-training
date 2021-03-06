<?php
declare(strict_types=1);

namespace App\Training\Domain;

final class TrainingMap
{
    private Id $id;
    private string $name;

    public function __construct(Id $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function create(string $mapName): self
    {
        return new self(
            Id::nullable(), $mapName
        );
    }

    public static function createWithId(int $id): self
    {
        return new self(
            Id::fromInt($id),
            'random string'
        );
    }

    public static function fromRow(array $data): self
    {
        return new self(Id::fromInt((int)$data['map_id']), $data['map_name']);
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
