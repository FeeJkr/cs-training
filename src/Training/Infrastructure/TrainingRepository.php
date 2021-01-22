<?php
declare(strict_types=1);

namespace App\Training\Infrastructure;

use App\Training\Domain\Id;
use App\Training\Domain\Training;
use App\Training\Domain\TrainingRepository as TrainingRepositoryInterface;
use Doctrine\DBAL\Connection;
use Throwable;
use Twig\Node\IfNode;

final class TrainingRepository implements TrainingRepositoryInterface
{
    private Connection $connection;
    private TrainingPartRepository $trainingPartRepository;

    public function __construct(Connection $connection, TrainingPartRepository $trainingPartRepository)
    {
        $this->connection = $connection;
        $this->trainingPartRepository = $trainingPartRepository;
    }

    public function getAll(): array
    {
        $groupedTrainings = [];

        $result = $this->connection->executeQuery("
            SELECT
                trainings.id as training_id,
                trainings.date as training_date,
                training_maps.id as map_id,
                training_maps.name as map_name,
                training_parts.id as training_part_id,
                training_parts.name as training_part_name,
                training_parts.is_ended as training_part_is_ended,
                training_parts.mode as training_part_mode,
                training_parts.value as training_part_value
            FROM trainings
                JOIN training_parts ON training_parts.training_id = trainings.id
                JOIN training_maps ON training_parts.map_id = training_maps.id;
        ")->fetchAllAssociative();

        foreach ($result as $data) {
            $groupedTrainings[$data['training_id']][] = $data;
        }

        return array_map(
            static function (array $data): Training { return Training::fromRow($data); },
            $groupedTrainings
        );
    }

    public function getById(Id $id): Training
    {
        $result = $this->connection->executeQuery("
            SELECT
                trainings.id as training_id,
                trainings.date as training_date,
                training_maps.id as map_id,
                training_maps.name as map_name,
                training_parts.id as training_part_id,
                training_parts.name as training_part_name,
                training_parts.is_ended as training_part_is_ended,
                training_parts.mode as training_part_mode,
                training_parts.value as training_part_value
            FROM trainings
                JOIN training_parts ON training_parts.training_id = trainings.id
                JOIN training_maps ON training_parts.map_id = training_maps.id
            WHERE trainings.id = :id
        ", ['id' => $id->toInt()])->fetchAllAssociative();

        return Training::fromRow($result);
    }

    public function create(Training $training): void
    {
        try {
            $this->connection->beginTransaction();

            $id = $this->connection->executeQuery(
                "INSERT INTO trainings (date) VALUES (:date) RETURNING id;",
                ['date' => $training->getDate()->format('Y-m-d 00:00:00')]
            )->fetchOne();

            $trainingId = Id::fromInt($id);

            foreach ($training->getParts() as $part) {
                $this->trainingPartRepository->create($trainingId, $part);
            }

            $this->connection->commit();
        } catch (Throwable $exception) {
            $this->connection->rollBack();
        }
    }

    public function update(Training $training): void
    {
        try {
            $this->connection->beginTransaction();

            $this->connection->executeQuery(
                "UPDATE trainings SET date = :date WHERE id = :id",
                [
                    'id' => $training->getId()->toInt(),
                    'date' => $training->getDate()->format('Y-m-d 00:00:00'),
                ]
            );

            foreach ($training->getParts() as $part) {
                $this->trainingPartRepository->delete($part);
                $this->trainingPartRepository->create($training->getId(), $part);
            }

            $this->connection->commit();
        } catch (Throwable $exception) {
            $this->connection->rollBack();
        }

    }
}
