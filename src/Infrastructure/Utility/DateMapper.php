<?php

namespace App\Infrastructure\Utility;

class DateMapper
{
    private const WEEK_MAP = [
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miercoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sabado',
        'Sunday' => 'Domingo'
    ];

    private const MONTH_MAP = [
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre',
    ];

    public static function get(string $timestamp = null): array
    {
        $date = $timestamp !== null ? getdate($timestamp) : getdate();
        $weekday = $date['weekday'];
        $month = $date['month'];

        $date['weekday'] = self::WEEK_MAP[$weekday];
        $date['month'] = self::MONTH_MAP[$month];

        return $date;
    }
}
