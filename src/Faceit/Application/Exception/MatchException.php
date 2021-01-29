<?php
declare(strict_types=1);

namespace App\Faceit\Application\Exception;

use App\Faceit\Domain\Contract\FaceitException;

final class MatchException extends ApplicationException
{
    public static function createFromDomainException(FaceitException $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}
