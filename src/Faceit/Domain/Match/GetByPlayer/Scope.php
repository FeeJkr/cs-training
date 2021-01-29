<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Match\GetByPlayer;

use DateInterval;
use DateTime;
use DateTimeInterface;
use MyCLabs\Enum\Enum;

/**
 * @method static Scope GLOBAL()
 * @method static Scope MONTH()
 * @method static Scope YESTERDAY()
 * @method static Scope TODAY()
 */
final class Scope extends Enum
{
    private const GLOBAL = 'global';
    private const MONTH = 'month';
    private const YESTERDAY = 'yesterday';
    private const TODAY = 'today';

    public function getDateByScope(): DateTimeInterface
    {
        $today = new DateTime();

        if ($this->value === self::MONTH) {
            return $today->setDate((int) $today->format('Y'), (int) $today->format('m'), 1);
        }

        if ($this->value === self::YESTERDAY) {
            return $today->sub(new DateInterval('P1D'));
        }

        return $today;
    }
}
