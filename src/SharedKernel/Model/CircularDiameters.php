<?php

namespace App\SharedKernel\Model;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema:"diameters",
    title:"diameters",
    properties:[
        new OA\Property(property:"80", type:"integer"),
        new OA\Property(property:"160", type:"integer"),
        new OA\Property(property:"200", type:"integer"),
        new OA\Property(property:"250", type:"integer"),
        new OA\Property(property:"315", type:"integer"),
        new OA\Property(property:"355", type:"integer"),
        new OA\Property(property:"400", type:"integer"),
        new OA\Property(property:"450", type:"integer"),
        new OA\Property(property:"500", type:"integer"),
        new OA\Property(property:"630", type:"integer"),
        new OA\Property(property:"710", type:"integer"),
        new OA\Property(property:"800", type:"integer"),
        new OA\Property(property:"900", type:"integer"),
        new OA\Property(property:"1000", type:"integer"),
        new OA\Property(property:"1120", type:"integer"),
        new OA\Property(property:"1250", type:"integer"),
    ]
)]
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