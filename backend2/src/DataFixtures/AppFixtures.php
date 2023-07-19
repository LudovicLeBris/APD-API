<?php

namespace App\DataFixtures;

use App\Entity\Diameter;
use App\Entity\Material;
use App\Entity\Singularity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ! Diameters
        $diameters = [80, 160, 200, 250, 315, 355, 400, 450, 500, 560, 630, 710, 800, 900, 1000, 1250];

        foreach ($diameters as $diameter) {
            $newDiameter = new Diameter();
            $newDiameter->setDiameter($diameter);
            $manager->persist($newDiameter);
        }

        // ! Materials
        $materials = [
            'galvanised steel' => 0.0001,
            'aluminium' => 2.0e-06,
            'steel' => 5.0e-05,
            'cast iron' => 0.0002,
            'plastic' => 2.0e-06,
            'smooth concrete' => 0.0003,
            'ordinary concrete' => 0.001,
            'brick' => 0.002,
            'terracotta' => 0.005
        ];

        foreach ($materials as $name => $roughness) {
            $newMaterial = new Material();
            $newMaterial->setName($name);
            $newMaterial->setRoughness($roughness);
            $manager->persist($newMaterial);
        }

        // ! Singularities
        $singularities = [
            ['90_elbow', 'circular', 0.4, '90° circular elbow'],
            ['60_elbow', 'circular', 0.31, '60° circular elbow'],
            ['45_elbow', 'circular', 0.23, '45° circular elbow'],
            ['30_elbow', 'circular', 0.17, '30° circular elbow'],
            ['90_sharp_elbow', 'circular', 1.2, '90° circular sharp elbow'],
            ['60_sharp_elbow', 'circular', 0.56, '60° circular sharp elbow'],
            ['45_sharp_elbow', 'circular', 0.32, '45° circular sharp elbow'],
            ['30_sharp_elbow', 'circular', 0.16, '30° circular sharp elbow'],
            ['90_sep_tee', 'circular', 2.0, '90° circular separation tee'],
            ['90_junc_tee', 'circular', 2.27, '90° circular junction tee'],
            ['45_sep_tee', 'circular', 0.58, '45° circular separation tee'],
            ['45_junc_tee', 'circular', 1.64, '45° circular junction tee'],
            ['pressed_reducer',	'circular',	0.35,	'pressed circular reducer'],
            ['long_reducer', 'circular', 0.59, 'long circular reducer'],
            ['90_elbow', 'rectangular', 0.36, '90° rectangular elbow'],
            ['60_elbow', 'rectangular', 0.28, '60° rectangular elbow'],
            ['45_elbow', 'rectangular', 0.21, '45° rectangular elbow'],
            ['30_elbow', 'rectangular', 0.15, '30° rectangular elbow'],
            ['90_sharp_elbow', 'rectangular', 1.28, '90° rectangular sharp elbow'],
            ['60_sharp_elbow', 'rectangular', 0.59, '60° rectangular sharp elbow'],
            ['45_sharp_elbow', 'rectangular', 0.34, '45° rectangular sharp elbow'],
            ['30_sharp_elbow', 'rectangular', 0.17, '30° rectangular sharp elbow'],
            ['90_sep_tee', 'rectangular', 2.0, '90° rectangular separation tee'],
            ['90_junc_tee', 'rectangular', 2.27, '90° rectangular junction tee'],
            ['45_sep_tee', 'rectangular', 0.58, '45° rectangular separation tee'],
            ['45_junc_tee', 'rectangular', 1.64, '45° rectangular junction tee'],
            ['pressed_reducer', 'rectangular', 0.35, 'pressed rectangular reducer'],
            ['long_reducer', 'rectangular', 0.08, 'long rectangular reducer']
        ];

        foreach ($singularities as $singularity) {
            $newSingularity = new Singularity();
            $newSingularity->setName($singularity[0]);
            $newSingularity->setShape($singularity[1]);
            $newSingularity->setSingularity($singularity[2]);
            $newSingularity->setLongName($singularity[3]);
            $manager->persist($newSingularity);
        }


        $manager->flush();
    }
}
