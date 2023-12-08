<?php

namespace App\SharedKernel\Model;

class CircularDiameters
{
    static $diameters = [
        80,
        160,
        200,
        250,
        315,
        355,
        400,
        450,
        500,
        560,
        630,
        710,
        800,
        900,
        1000,
        1120,
        1250
    ];

    public static function getUpperDiameter(int $diameter): int
    {
        $closest = null;
        foreach (self::$diameters as $item) {
            // if ($closest === null || abs($diameter - $closest) >= abs($item - $diameter)) {
            if (abs($item) >= abs($diameter)) {
                $closest = $item;
                return $closest;
            }
        }

    }
}