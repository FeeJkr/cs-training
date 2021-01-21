<?php
declare(strict_types=1);

namespace App\Training\Domain;

interface TrainingPartRepository
{
    public function close(int $id): void;
    public function open(int $id): void;
}
