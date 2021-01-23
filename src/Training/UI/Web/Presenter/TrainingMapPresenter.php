<?php
declare(strict_types=1);

namespace App\Training\UI\Web\Presenter;

use App\Training\Domain\TrainingMap;

final class TrainingMapPresenter
{
    public function present(array $maps): array
    {
        return array_map(static function (TrainingMap $map): array {
            return [
                'id' => $map->getId()->toInt(),
                'name' => $map->getName(),
            ];
        }, $maps);
    }
}
