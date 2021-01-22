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

    public function close(int $id): void
    {
        // TODO: Implement close() method.
    }

    public function open(int $id): void
    {
        // TODO: Implement open() method.
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

    public function delete(TrainingPart $part): void
    {
        $this->connection->executeQuery(
            "DELETE FROM training_parts WHERE id = :id;",
            ['id' => $part->getId()->toInt()]
        );
    }
}
