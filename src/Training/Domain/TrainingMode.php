<?php
declare(strict_types=1);

namespace App\Training\Domain;

use MyCLabs\Enum\Enum;

/**
 * @method static TrainingMode MINUTE()
 * @method static TrainingMode KILL()
 * @method static TrainingMode BHOP()
 */
final class TrainingMode extends Enum
{
    private const MINUTE = 'minute';
    private const KILL = 'kill';
    private const BHOP = 'bhop';

    public function getModePrefix(): string
    {
        if ($this->value === self::KILL) {
            return 'Убийств';
        }

        return 'Минут';
    }
}
