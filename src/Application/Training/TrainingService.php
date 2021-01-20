<?php
declare(strict_types=1);

namespace App\Application\Training;

use App\Domain\Training\Training;
use App\Domain\Training\TrainingMap;
use App\Domain\Training\TrainingMode;
use App\Domain\Training\TrainingPart;
use App\Domain\Training\TrainingRepository;
use DateTime;

final class TrainingService
{
    public function __construct(private TrainingRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getAllMaps(): array
    {
        return $this->repository->getAllMaps();
    }

    public function addTraining(string $date, array $parts): void
    {
        $parts = array_map(
            function (array $data): TrainingPart {
                if (is_numeric ($data['map'])) {
                    $map = $this->getMapById((int)$data['map']);
                } else {
                    $map = $this->addMap($data['map']);
                }

                return new TrainingPart(
                    new TrainingMode($data['mod']),
                    (int)$data['value'],
                    $data['name'],
                    $map,
                    false,
                );
            },
            $parts
        );

        $this->repository->create(
            new Training(
                null,
                $parts,
                DateTime::createFromFormat('Y-m-d', $date)
            )
        );
    }

    public function getMapById(int $id): TrainingMap
    {
        return $this->repository->getMapById($id);
    }

    public function addMap(string $mapName): TrainingMap
    {
        return $this->repository->createMap($mapName);
    }

    public function getTrainingById(int $id): Training
    {
        return $this->repository->getTrainingById($id);
    }

    public function updateTraining(int $trainingId, string $date, array $parts): void
    {
        $parts = array_map(
            function (array $data): TrainingPart {
                if (is_numeric ($data['map'])) {
                    $map = $this->getMapById((int)$data['map']);
                } else {
                    $map = $this->addMap($data['map']);
                }

                return new TrainingPart(
                    new TrainingMode($data['mod']),
                    (int)$data['value'],
                    $data['name'],
                    $map,
                    false,
                );
            },
            $parts
        );

        $training = new Training($trainingId, $parts, DateTime::createFromFormat('Y-m-d', $date));

        $this->repository->update($training);
    }

    public function endTrainingPart(int $id): void
    {
        $this->repository->endTrainingPart($id);
    }

    public function reopenTrainingPart(int $id): void
    {
        $this->repository->reopenTrainingPart($id);
    }
}
