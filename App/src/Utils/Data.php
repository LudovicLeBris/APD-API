<?php

namespace App\Utils;

define('DIAMETERS', [
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
]);

define('MATERIALS', [
    'galvanised_steel' => 0.0001, 
    'aluminium' => 0.000002, 
    'steel' => 0.00005, 
    'cast_iron' => 0.0002, 
    'plastic' => 0.000002, 
    'smooth_concrete' => 0.0003, 
    'ordinary_concrete' => 0.001, 
    'brick' => 0.002, 
    'terracotta' => 0.005
]);

define('SINGULARITIES', [
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
        'long_reducer' => [0.08, "long rectangular reducer"],
    ]
]);

class Data
{
    public static function getDiameters()
    {
        return DIAMETERS;
    }
    
    public static function getUpperDiameter($diameter)
    {
        $closest = null;
        foreach (DIAMETERS as $item) {
            // if ($closest === null || abs($diameter - $closest) >= abs($item - $diameter)) {
            if (abs($item) >= abs($diameter)) {
                $closest = $item;
                return $closest;
            }
        }
    }

    public static function getMaterials()
    {
        return array_keys(MATERIALS);
    }

    public static function getRoughness($material)
    {
        return MATERIALS[$material];
    }

    public static function getSingularities($shape)
    {
        return SINGULARITIES[$shape];
    }

    public static function getSingularitiesLongName($shape)
    {
        $singularities = [];

        foreach (SINGULARITIES[$shape] as $singularityShortName => $singularity) {
            $singularities[$singularityShortName] = $singularity[1];
        }

        return $singularities;
    }

    public static function getSingularity($shape, $singularityType)
    {
        return SINGULARITIES[$shape][$singularityType][0];
    }

    public static function getSingularityLongName($shape, $singularityType)
    {
        return SINGULARITIES[$shape][$singularityType][1];
    }
}