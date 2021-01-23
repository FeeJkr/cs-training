<?php
declare(strict_types=1);

namespace App\Training\Infrastructure;

use App\Training\Domain\Id;
use App\Training\Domain\TrainingPart;
use App\Training\Domain\TrainingPartRepository as TrainingPartRepositoryInterface;
use Doctrine\DBAL\Connection;

final class TrainingPartRepository implements TrainingPartRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(Id $trainingId, TrainingPart $part): void
    {
        $this->connection->executeQuery("
            INSERT INTO training_parts (map_id, training_id, name, is_ended, mode, value)
            VALUES (:mapId, :trainingId, :name, false, :mode, :value);
        ", [
            'mapId' => $part->getMap()->getId()->toInt(),
            'trainingId' => $trainingId->toInt(),
            'name' => $part->getName(),
            'mode' => $part->getMode()->getValue(),
            'value' => $part->getValue(),
        ]);
    }

    public function deleteByTrainingId(Id $trainingId): void
    {
        $this->connection->executeQuery(
            "DELETE FROM training_parts WHERE training_id = :id;",
            ['id' => $trainingId->toInt()]
        );
    }

    public function getById(Id $id): TrainingPart
    {
        $result = $this->connection->executeQuery("
            SELECT
                training_parts.id as training_part_id,
                training_parts.mode as training_part_mode,
                training_maps.id as map_id,
                training_maps.name as map_name,
                training_parts.name as training_part_name,
                training_parts.value as training_part_value,
                training_parts.is_ended as training_part_is_ended
            FROM training_parts
                LEFT JOIN training_maps on training_parts.map_id = training_maps.id
            WHERE training_parts.id = :id
        ", [
            'id' => $id->toInt(),
        ])->fetchAssociative();

        return TrainingPart::fromRow($result);
    }

    public function save(TrainingPart $part): void
    {
        $this->connection->executeQuery("
            UPDATE training_parts SET is_ended = :isEnded WHERE id = :id 
        ", [
            'id' => $part->getId()->toInt(),
            'isEnded' => $part->isEnded() ? 1 : 0,
        ]);
    }
}
