<?php

namespace App\Infrastructure\Utility;

class Utilities
{
    public static function varCaster(&$data)
    {
        if ($data === null || $data === true || $data === false) {
            return $data;
        }
    
        if (is_numeric($data) && (int)$data != $data) {
            return (float)$data;
        }
    
        if (is_string($data) && !ctype_digit($data)) {
            return $data;
        }
    
        if (ctype_digit($data) || (int)$data == $data) {
            return (int)$data;
        }
    
        return $data;
    }
}
