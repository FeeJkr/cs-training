<?php
declare(strict_types=1);

namespace App\Training\UI\Web\Presenter;

use App\Training\Domain\Training;
use App\Training\Domain\TrainingMode;
use App\Training\Domain\TrainingPart;

final class TrainingPresenter
{
    public function present(array $trainings): array
    {
        return array_map(function (Training $training): array {
            return $this->presentTraining($training);
        }, $trainings);
    }

    public function presentTraining(Training $training): array
    {
        return [
            'id' => $training->getId()->toInt(),
            'parts' => array_map(static function (TrainingPart $part): array {
                return [
                    'id' => $part->getId()->toInt(),
                    'mode' => $part->getMode()->getValue(),
                    'modePrefix' => $part->getMode()->getModePrefix(),
                    'value' => $part->getValue(),
                    'name' => $part->getName(),
                    'map' => [
                        'id' => $part->getMap()->getId()->toInt(),
                        'name' => $part->getMap()->getName(),
                    ],
                    'isEnded' => $part->isEnded(),
                ];
            }, $training->getParts()->getParts()),
            'formatDate' => $training->getDate()->format('d-m-Y (l)'),
            'date' => $training->getDate()->format('Y-m-d'),
        ];
    }

    public function presentTrainingMods(array $trainingModes): array
    {
        return array_map(
            static function (TrainingMode $mode): array {
                return [
                    'key' => $mode->getValue(),
                    'value' => $mode->getValueRU(),
                ];
            }, $trainingModes
        );
    }
}
