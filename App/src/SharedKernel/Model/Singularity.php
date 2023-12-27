<?php

namespace App\SharedKernel\Model;

use OpenApi\Attributes as OA;

use function PHPSTORM_META\type;

#[OA\Schema(
    schema:"singularitiesAmount",
    title:"singularitiesAmount",
    properties:[
        new OA\Property(property:"90_elbow", type:"integer", example:2),
        new OA\Property(property:"60_elbow", type:"integer", example:2),
        new OA\Property(property:"45_elbow", type:"integer", example:2),
        new OA\Property(property:"30_elbow", type:"integer", example:2),
        new OA\Property(property:"90_sharp_elbow", type:"integer", example:2),
        new OA\Property(property:"60_sharp_elbow", type:"integer", example:2),
        new OA\Property(property:"45_sharp_elbow", type:"integer", example:2),
        new OA\Property(property:"30_sharp_elbow", type:"integer", example:2),
        new OA\Property(property:"90_sep_tee", type:"integer", example:2),
        new OA\Property(property:"90_junc_tee", type:"integer", example:2),
        new OA\Property(property:"45_sep_tee", type:"integer", example:2),
        new OA\Property(property:"45_junc_tee", type:"integer", example:2),
        new OA\Property(property:"pressed_reducer", type:"integer", example:2),
        new OA\Property(property:"long_reducer", type:"integer", example:2),
    ]
)]
class Singularity
{
    static $singularities = [
        'circular' => [
            '90_elbow' => [0.4, "90° circular elbow"],
            '60_elbow' => [0.31, "60° circular elbow"],
            '45_elbow' => [0.23, "45° circular elbow"],
            '30_elbow' => [0.17, "30° circular elbow"],
            '90_sharp_elbow' => [1.2, "90° circular sharp elbow"],
            '60_sharp_elbow' => [0.56, "60° circular sharp elbow"],
            '45_sharp_elbow' => [0.32, "45° circular sharp elbow"],
            '30_sharp_elbow' => [0.16, "30° circular sharp elbow"],
            '90_sep_tee' => [2.0, "90° circular separation tee"],
            '90_junc_tee' => [2.27, "90° circular junction tee"],
            '45_sep_tee' => [0.58, "45° circular separation tee"],
            '45_junc_tee' => [1.64, "45° circular junction tee"],
            'pressed_reducer' => [0.35, "pressed circular reducer"],
            'long_reducer' => [0.59, "long circular reducer"],
        ],
        'rectangular' => [
            '90_elbow' => [0.36, "90° rectangular elbow"],
            '60_elbow' => [0.28, "60° rectangular elbow"],
            '45_elbow' => [0.21, "45° rectangular elbow"],
            '30_elbow' => [0.15, "30° rectangular elbow"],
            '90_sharp_elbow' => [1.28, "90° rectangular sharp elbow"],
            '60_sharp_elbow' => [0.59, "60° rectangular sharp elbow"],
            '45_sharp_elbow' => [0.34, "45° rectangular sharp elbow"],
            '30_sharp_elbow' => [0.17, "30° rectangular sharp elbow"],
            '90_sep_tee' => [2.0, "90° rectangular separation tee"],
            '90_junc_tee' => [2.27, "90° rectangular junction tee"],
            '45_sep_tee' => [0.58, "45° rectangular separation tee"],
            '45_junc_tee' => [1.64, "45° rectangular junction tee"],
            'pressed_reducer' => [0.35, "pressed rectangular reducer"],
            'long_reducer' => [0.58, "long rectangular reducer"],
        ]
    ];

    public static function getSingularitiesByShape(string $shape): ?array
    {
        if (self::checkShape($shape)) {
            return null;
        }

        return self::$singularities[$shape];
    }

    public static function getSingularitiesLongNameByShape(string $shape): ?array
    {
        if (self::checkShape($shape)) {
            return null;
        }

        $singularitiesLongName = [];

        foreach (self::$singularities[$shape] as $singularityShortName => $singularity) {
            $singularitiesLongName[$singularityShortName] = $singularity[1];
        }

        return $singularitiesLongName;
    }

    public static function getSingularity(string $shape, string $singularityType): ?float
    {
        if (self::checkShape($shape)) {
            return null;
        }

        if (self::checkSingularityType($shape, $singularityType)) {
            return null;
        }

        return self::$singularities[$shape][$singularityType][0];
    }

    public static function getSingularityLongName(string $shape, string $singularityType): ?string
    {
        return self::$singularities[$shape][$singularityType][1];
    }

    private static function checkShape(string $shape): bool
    {
        if ($shape !== 'circular' && $shape !== 'rectangular') {
            return true;
        }
        return false;
    }

    private static function checkSingularityType(string $shape, string $singularityType): bool
    {
        if (!array_key_exists($singularityType, self::$singularities[$shape])) {
            return true;
        }
        return false;
    }
}