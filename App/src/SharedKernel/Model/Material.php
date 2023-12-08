<?php

namespace App\SharedKernel\Model;

class Material
{
    static $material = [
        'galvanised_steel' => 0.0001, 
        'aluminium' => 0.000002, 
        'steel' => 0.00005, 
        'cast_iron' => 0.0002, 
        'plastic' => 0.000002, 
        'smooth_concrete' => 0.0003, 
        'ordinary_concrete' => 0.001, 
        'brick' => 0.002, 
        'terracotta' => 0.005
    ];

    public static function getRoughness(string $material): ?float
    {
        if (array_key_exists($material, self::$material)) {
            return self::$material[$material];
        }

        return null;
    }
}