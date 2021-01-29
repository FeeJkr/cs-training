<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract;

use Exception;

final class FaceitException extends Exception
{
    private const GLOBAL_ERROR_CODE = 100;

    private const INVALID_FACEIT_RESPONSE_MESSAGE = 'Response from faceit server is invalid.';
    private const INVALID_FACEIT_RESPONSE_CODE = self::GLOBAL_ERROR_CODE + 1;

    public static function invalidFaceitResponse(): self
    {
        return new self(self::INVALID_FACEIT_RESPONSE_MESSAGE, self::INVALID_FACEIT_RESPONSE_CODE);
    }
}
