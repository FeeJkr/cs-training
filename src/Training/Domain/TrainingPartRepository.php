<?php
declare(strict_types=1);

namespace App\Training\Domain;

interface TrainingPartRepository
{
    public function getById(Id $id): TrainingPart;
    public function save(TrainingPart $part): void;
}
