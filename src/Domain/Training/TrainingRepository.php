<?php
declare(strict_types=1);

namespace App\Domain\Training;

interface TrainingRepository
{
    public function getAll(): array;
    public function getAllMaps(): array;
    public function create(Training $training): void;
    public function getMapById(int $id): TrainingMap;
    public function createMap(string $mapName): TrainingMap;
    public function getTrainingById(int $id): Training;
    public function update(Training $training): void;
    public function endTrainingPart(int $id): void;
    public function reopenTrainingPart(int $id): void;
    public function isMapExists(string $name): bool;
    public function getMapByName(string $name): TrainingMap;
}
