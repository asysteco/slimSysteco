<?php

namespace App\Infrastructure\PDO\Exception;

use Exception;
use Throwable;

class PDOQueryException extends Exception
{
    public function __construct(string $msg = "Error al obtener datos", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }
}
