<?php
declare(strict_types=1);

namespace App\Domain\Training;

use DateTime;
use DateTimeInterface;

final class Training
{
    public function __construct(
        private ?int $id,
        private array $parts,
        private DateTimeInterface $date,
    ) {}

    public static function createFromRow(array $row): self
    {
        $parts = array_map(
            static function (array $data): TrainingPart { return TrainingPart::createFromRow($data); },
            $row
        );

        return new self(
            (int)$row[0]['training_id'],
            $parts,
            DateTime::createFromFormat('Y-m-d H:i:s', $row[0]['training_date'])
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParts(): array
    {
        return $this->parts;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
