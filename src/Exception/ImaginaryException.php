<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Throwable;

/**
 * Class ImaginaryException
 * @package App\Exception
 */
class ImaginaryException extends \Exception
{
    #[Pure]
    public function __construct(string $message = "Un problème est survenu avec Imaginary.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
