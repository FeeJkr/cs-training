<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Statistics;

use MyCLabs\Enum\Enum;

/**
 * @method static StatisticsType GLOBAL()
 * @method static StatisticsType MONTH()
 * @method static StatisticsType DAY()
 */
final class StatisticsType extends Enum
{
    private const GLOBAL = 'global';
    private const MONTH = 'month';
    private const DAY = 'day';
}
