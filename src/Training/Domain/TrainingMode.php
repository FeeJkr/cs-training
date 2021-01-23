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

    private static array $languageRU = [
        self::MINUTE => 'Минуты',
        self::KILL => 'Убийства',
        self::BHOP => 'БХоп',
    ];

    private static array $prefixRU = [
        self::MINUTE => 'Минут',
        self::KILL => 'Убийств',
        self::BHOP => 'Минут (БХоп)',
    ];

    public function getModePrefix(): string
    {
        return self::$prefixRU[$this->value];
    }

    public function getValueRU(): string
    {
        return self::$languageRU[$this->value];
    }
}
