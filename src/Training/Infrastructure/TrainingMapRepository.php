<?php
declare(strict_types=1);

namespace App\Training\Infrastructure;

use App\Training\Domain\TrainingMap;
use Doctrine\DBAL\Connection;

final class TrainingMapRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(TrainingMap $map): int
    {
        return $this->connection->executeQuery("
            INSERT INTO training_maps(name) VALUES (:name) RETURNING id;
        ", ['name' => $map->getName()])->fetchOne();
    }
}
