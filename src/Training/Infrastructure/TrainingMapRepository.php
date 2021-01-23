<?php
declare(strict_types=1);

namespace App\Training\Infrastructure;

use App\Training\Domain\TrainingMap;
use App\Training\Domain\TrainingMapRepository as TrainingMapRepositoryInterface;
use Doctrine\DBAL\Connection;

final class TrainingMapRepository implements TrainingMapRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        $result = $this->connection->executeQuery("
            SELECT 
                id as map_id, 
                name as map_name 
            FROM training_maps
        ")->fetchAllAssociative();

        return array_map(static function (array $row): TrainingMap { return TrainingMap::fromRow($row); }, $result);
    }

    public function getById(int $id): TrainingMap
    {
        // TODO: Implement getById() method.
    }

    public function isMapExists(string $name): bool
    {
        // TODO: Implement isMapExists() method.
    }

    public function getByName(string $name): TrainingMap
    {
        // TODO: Implement getByName() method.
    }

    public function create(string $mapName): TrainingMap
    {
        // TODO: Implement create() method.
    }
}
