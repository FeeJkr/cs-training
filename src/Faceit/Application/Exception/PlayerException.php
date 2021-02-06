<?php
declare(strict_types=1);

namespace App\Faceit\Application\Exception;

use App\Faceit\Domain\Contract\FaceitException;
use JetBrains\PhpStorm\Pure;

final class PlayerException extends ApplicationException
{
    #[Pure]
    public static function createFromDomainException(FaceitException $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }

    #[Pure]
    public static function playerAlreadyExists(string $nickname): self
    {
        return new self(sprintf('User "%s" already exists.', $nickname));
    }
}
