<?php
declare(strict_types=1);

namespace App\UI\Web\Presenter\Training;

use App\Domain\Training\Training;
use App\Domain\Training\TrainingPart;

final class TrainingPresenter
{
    public function present(array $trainings): array
    {
        return array_map(static function (Training $training): array {
            return [
                'id' => $training->getId(),
                'parts' => array_map(static function (TrainingPart $part): array {
                        return [
                            'id' => $part->getId(),
                            'mode' => $part->getMode()->getValue(),
                            'value' => $part->getValue(),
                            'name' => $part->getName(),
                            'map' => $part->getMap()->getName(),
                            'isEnded' => $part->isEnded(),
                        ];
                    }, $training->getParts()),
                'date' => $training->getDate()->format('d-m-Y (l)'),
            ];
        }, $trainings);
    }
}
