<?php
declare(strict_types=1);

namespace App\Training\Domain;

interface TrainingMapRepository
{
    public function getAll(): array;
    public function getById(int $id): TrainingMap;
    public function isMapExists(string $name): bool;
    public function getByName(string $name): TrainingMap;
    public function create(string $mapName): TrainingMap;
}
