<?php
declare(strict_types=1);

namespace App\Training\Domain;

interface TrainingRepository
{
    public function getAll(): array;
    public function getById(Id $id): Training;
    public function create(Training $training): void;
    public function update(Training $training): void;
}
