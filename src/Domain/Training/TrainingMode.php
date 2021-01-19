<?php
declare(strict_types=1);

namespace App\Domain\Training;

use MyCLabs\Enum\Enum;

/**
 * @method static TrainingMode MINUTE()
 * @method static TrainingMode KILL()
 */
final class TrainingMode extends Enum
{
    private const MINUTE = 'minute';
    private const KILL = 'kill';
}
