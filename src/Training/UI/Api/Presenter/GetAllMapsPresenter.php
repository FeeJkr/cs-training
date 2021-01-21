<?php
declare(strict_types=1);

namespace App\UI\Api\Presenter\Training;

use App\Domain\Training\TrainingMap;

final class GetAllMapsPresenter
{
    public static function present(array $data): array
    {
        $maps = array_map(
            static function (TrainingMap $map): array {
                return [
                    'id' => $map->getName(),
                    'text' => $map->getName(),
                ];
            },
            $data
        );

        return [
            'results' => $maps,
        ];
    }
}
