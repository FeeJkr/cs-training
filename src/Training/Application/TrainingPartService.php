<?php
declare(strict_types=1);

namespace App\Training\Application;

use App\Training\Domain\Id;
use App\Training\Domain\TrainingPartRepository;

final class TrainingPartService
{
    private TrainingPartRepository $repository;

    public function __construct(TrainingPartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function toggleIsEnded(int $id): void
    {
        $part = $this->repository->getById(Id::fromInt($id));

        $part->toggleIsEnded();

        $this->repository->save($part);
    }
}
