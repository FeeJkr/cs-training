<?php
declare(strict_types=1);

namespace App\Training\Application;

use App\Training\Domain\TrainingMapRepository;

final class TrainingMapService
{
    private TrainingMapRepository $repository;

    public function __construct(TrainingMapRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }
}
