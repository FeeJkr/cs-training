<?php
declare(strict_types=1);

namespace App\Training\Application;

use App\Training\Domain\Id;
use App\Training\Domain\Training;
use App\Training\Domain\TrainingPartDetailsCollection;
use App\Training\Domain\TrainingRepository;
use DateTimeInterface;

final class TrainingService
{
    private TrainingRepository $repository;

    public function __construct(TrainingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): Training
    {
        return $this->repository->getById(Id::fromInt($id));
    }

    public function create(DateTimeInterface $date, TrainingPartDetailsCollection $detailsCollection): void
    {
        $training = Training::create($date, $detailsCollection);

        $this->repository->create($training);
    }

    public function update(int $id, DateTimeInterface $date, TrainingPartDetailsCollection $detailsCollection): void
    {
        $training = $this->repository->getById(Id::fromInt($id));

        $training->update($date, $detailsCollection);

        $this->repository->update($training);
    }
}
