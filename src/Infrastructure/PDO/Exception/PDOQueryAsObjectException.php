<?php

namespace App\Infrastructure\PDO\Exception;

use Exception;
use Throwable;

class PDOQueryAsObjectException extends Exception
{
    public function __construct(string $msg = "Error al obtener objetos", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }
}
