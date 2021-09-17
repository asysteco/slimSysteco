<?php

namespace App\Infrastructure\PDO\Exception;

use Exception;
use Throwable;

class PDOInsertException extends Exception
{
    public function __construct(string $msg = "Error al insertar datos", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }
}
