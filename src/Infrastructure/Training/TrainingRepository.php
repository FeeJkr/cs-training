<?php
declare(strict_types=1);

namespace App\Infrastructure\Training;

use App\Domain\Training\Training;
use App\Domain\Training\TrainingMap;
use App\Domain\Training\TrainingPart;
use App\Domain\Training\TrainingRepository as TrainingRepositoryInterface;
use DateTime;
use Doctrine\DBAL\Connection;

final class TrainingRepository implements TrainingRepositoryInterface
{
    public function __construct(private Connection $connection) {}

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
            static function (array $data): Training { return Training::createFromRow($data); },
            $groupedTrainings
        );
    }

    public function getAllMaps(): array
    {
        $result = $this->connection->executeQuery("
            SELECT id, name FROM training_maps;
        ")->fetchAllAssociative();

        return array_map(
            static function (array $data): TrainingMap { return new TrainingMap((int)$data['id'], $data['name']); },
            $result
        );
    }

    public function create(Training $training): void
    {
        $id = $this->connection->executeQuery("
            INSERT INTO trainings (date) VALUES (:date) RETURNING id;
        ", ['date' => $training->getDate()->format('Y-m-d 00:00:00')])->fetchOne();

        foreach ($training->getParts() as $part) {
            $this->createPart($part, $id);
        }
    }

    public function createPart(TrainingPart $part, int $trainingId): void
    {
        $this->connection->executeQuery("
            INSERT INTO training_parts (map_id, training_id, name, is_ended, mode, value)
            VALUES (:mapId, :trainingId, :name, false, :mode, :value);
        ", [
            'mapId' => $part->getMap()->getId(),
            'trainingId' => $trainingId,
            'name' => $part->getName(),
            'mode' => $part->getMode()->getValue(),
            'value' => $part->getValue(),
        ]);
    }

    public function getMapById(int $id): TrainingMap
    {
        $result = $this->connection->executeQuery("
            SELECT id, name FROM training_maps WHERE id = :id;
        ", ['id' => $id])->fetchAssociative();

        return new TrainingMap((int)$result['id'], $result['name']);
    }

    public function createMap(string $mapName): TrainingMap
    {
        $id = $this->connection->executeQuery("
            INSERT INTO training_maps(name) VALUES (:name) RETURNING id;
        ", ['name' => $mapName])->fetchOne();

        return new TrainingMap($id, $mapName);
    }

    public function getTrainingById(int $id): Training
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
        ", ['id' => $id])->fetchAllAssociative();

        return Training::createFromRow($result);
    }

    public function update(Training $training): void
    {
        $this->connection->executeQuery(
            "UPDATE trainings SET date = :date WHERE id = :id",
            [
                'date' => $training->getDate()->format('Y-m-d 00:00:00'),
                'id' => $training->getId(),
            ]
        );

        $this->connection->executeQuery("
            DELETE FROM training_parts WHERE training_id = :id;
        ", ['id' => $training->getId()]);

        foreach ($training->getParts() as $part) {
            $this->createPart($part, $training->getId());
        }
    }

    public function endTrainingPart(int $id): void
    {
        $this->connection->executeQuery(
            "UPDATE training_parts SET is_ended = true WHERE id = :id",
            ['id' => $id]
        );
    }

    public function reopenTrainingPart(int $id): void
    {
        $this->connection->executeQuery(
            "UPDATE training_parts SET is_ended = false WHERE id = :id",
            ['id' => $id]
        );
    }
}
