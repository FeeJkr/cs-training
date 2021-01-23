<?php
declare(strict_types=1);

namespace App\Training\Domain;

use DateTime;
use DateTimeInterface;

final class Training
{
    private Id $id;
    private TrainingPartsCollection $parts;
    private DateTimeInterface $date;

    public function __construct(Id $id, TrainingPartsCollection $parts, DateTimeInterface $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->parts = $parts;
    }

    public static function create(DateTimeInterface $date, TrainingPartDetailsCollection $detailsCollection): self
    {
        $parts = [];

        foreach ($detailsCollection->getDetails() as $partDetails) {
            $parts[] = TrainingPart::create(
                new TrainingMode($partDetails->getMode()),
                $partDetails->getMapId(),
                $partDetails->getName(),
                $partDetails->getValue()
            );
        }

        return new self(
            Id::nullable(),
            new TrainingPartsCollection(...$parts),
            $date
        );
    }

    public function update(DateTimeInterface $date, TrainingPartDetailsCollection $detailsCollection): void
    {
        $this->date = $date;
        $this->parts->update($detailsCollection);
    }

    public static function fromRow(array $row): self
    {
        $parts = array_map(
            static function (array $data): TrainingPart { return TrainingPart::fromRow($data); },
            $row
        );

        return new self(
            Id::fromInt((int)$row[0]['training_id']),
            new TrainingPartsCollection(...$parts),
            DateTime::createFromFormat('Y-m-d H:i:s', $row[0]['training_date'])
        );
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getParts(): TrainingPartsCollection
    {
        return $this->parts;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
