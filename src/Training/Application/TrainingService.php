<?php
declare(strict_types=1);

namespace App\Training\Application;

use App\Training\Domain\Id;
use App\Training\Domain\Training;
use App\Training\Domain\TrainingRepository;
use DateTime;
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

    public function create(DateTimeInterface $date, TrainingPartDetailsCollection $detailsCollection): void
    {
        $training = Training::create($date, $detailsCollection);

        $this->repository->create($training);
    }

    public function getById(int $id): Training
    {
        return $this->repository->getById(Id::fromInt($id));
    }

    public function update(int $id, DateTimeInterface $date, TrainingPartDetailsCollection $detailsCollection): void
    {
        $training = $this->repository->getById(Id::fromInt($id));

        $training->update();

        $this->repository->update($training);
    }
}
