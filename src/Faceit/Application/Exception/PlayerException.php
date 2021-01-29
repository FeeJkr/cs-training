<?php
declare(strict_types=1);

namespace App\Faceit\Application\Exception;

use App\Faceit\Domain\Contract\FaceitException;

final class PlayerException extends ApplicationException
{
    public static function createFromDomainException(FaceitException $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }

    public static function playerAlreadyExists(string $nickname): self
    {
        return new self(sprintf('User "%s" already exists.', $nickname));
    }

    public static function playerNotFound(string $nickname): self
    {
        return new self(sprintf('Player "%s" not found.', $nickname));
    }
}
