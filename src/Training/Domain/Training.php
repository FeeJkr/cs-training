<?php
declare(strict_types=1);

namespace App\Training\Domain;

use App\Training\Application\TrainingPartDetailsCollection;
use DateTime;
use DateTimeInterface;

final class Training
{
    private Id $id;
    private array $parts;
    private DateTimeInterface $date;

    public function __construct(Id $id, array $parts, DateTimeInterface $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->parts = $parts;
    }

    public static function create(
        DateTimeInterface $date,
        TrainingPartDetailsCollection $detailsCollection
    ): self {
        $parts = [];

        foreach ($detailsCollection->getDetails() as $partDetails) {
            $parts[] = TrainingPart::create(
                new TrainingMode($partDetails->getMode()),
                $partDetails->getMap(),
                $partDetails->getName(),
                $partDetails->getValue()
            );
        }

        return new self(
            Id::nullable(),
            $parts,
            DateTime::createFromFormat('Y-m-d', $date)
        );
    }

    public function update(DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public static function fromRow(array $row): self
    {
        $parts = array_map(
            static function (array $data): TrainingPart { return TrainingPart::fromRow($data); },
            $row
        );

        return new self(
            Id::fromInt((int)$row[0]['training_id']),
            $parts,
            DateTime::createFromFormat('Y-m-d H:i:s', $row[0]['training_date'])
        );
    }

    public function getId(): Id
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
