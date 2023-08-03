<?php

namespace App\Service;

use App\Util\DuctApd;
use Symfony\Component\HttpFoundation\RequestStack;

class DuctApdService
{
    private $requestStack;
    private $ductApd;

    public function __construct(RequestStack $requestStack, DuctApd $ductApd)
    {
        $this->requestStack = $requestStack;
        $this->ductApd = $ductApd;
    }

    public function getOptimalDuctDimension()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
        $shape = $post['shape'];
        $flowRate = $post['flowRate'];

        if($shape === 'rectangular') {
            $secondsize = $post['width'];
        } else {
            $secondsize = 0;
        }

        if(array_key_exists('flowSpeed', $post)) {
            $idealFlowSpeed = $post['flowSpeed'];
            $optimalDuctDimension = DuctApd::getOptimalDimensions($shape, $flowRate, $secondsize, $idealFlowSpeed);
        } else {
            $optimalDuctDimension = DuctApd::getOptimalDimensions($shape, $flowRate, $secondsize);
        }

        return $optimalDuctDimension;
    }

    public function getDuctSection()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
        $shape = $post['shape'];

        if($shape === 'circular') {
            $firstSize = $post['diameter'];
            $secondsize = 0;
        } elseif ($shape === 'rectangular') {
            $firstSize = $post['width'];
            $secondsize = $post['height'];
        }
        $section = DuctApd::getSection($shape, $firstSize, $secondsize);

        return $section;
    }

    public function getFlowSpeed()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
        $flowRate = $post['flowRate'];
        $shape = $post['shape'];

        if($shape === 'circular') {
            $firstSize = $post['diameter'];
            $secondsize = 0;
        } elseif ($shape === 'rectangular') {
            $firstSize = $post['width'];
            $secondsize = $post['height'];
        }
        $flowSpeed = DuctApd::getFlowSpeed($flowRate, $shape, $firstSize, $secondsize);

        return $flowSpeed;
    }

    public function getSection()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
        $shape = $post['shape'];
        $material = $post['material'];
        if($shape === 'circular') {
            $firstSize = $post['diameter'];
            $secondsize = 0;
        } elseif ($shape === 'rectangular') {
            $firstSize = $post['width'];
            $secondsize = $post['height'];
        }
        $flowRate = $post['flowRate'];
        $length = $post['length'];
        $singularities = $post['singularities'];
        $additionalApd = $post['additionalApd'];

        $this->ductApd->globalSetter($shape, $material, $firstSize, $secondsize, $flowRate, $length, $singularities, $additionalApd);

        if(array_key_exists('temperature', $post)) {
            $temperature = $post['temperature'];
            $this->ductApd->air->setTemperature($temperature);
        }
        if(array_key_exists('altitude', $post)) {
            $altitude = $post['altitude'];
            $this->ductApd->air->setAltitude($altitude);
        }

        $section = [
            'ductSection' => $this->ductApd->section,
            'flowSpeed' => $this->ductApd->flowSpeed,
            'linearApd' => $this->ductApd->getLinearApd(),
            'singularApd' => $this->ductApd->getSingularApd(),
            'totalApd' => $this->ductApd->getTotalApd()
        ];

        return $section;
    }

    public function getSections()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
        $generalAdditionalApd = $post['additionalApd'];
        $sections = $post['sections'];

        $totalLinearApd = 0;
        $totalSingularApd = 0;
        $totalAdditionalApd = 0;

        if(array_key_exists('temperature', $post)) {
            $temperature = $post['temperature'];
            $this->ductApd->air->setTemperature($temperature);
        }
        if(array_key_exists('altitude', $post)) {
            $altitude = $post['altitude'];
            $this->ductApd->air->setAltitude($altitude);
        }

        foreach($sections as $section) {
            if($section['shape'] === 'circular') {
                $firstSize = $section['diameter'];
                $secondsize = 0;
            } elseif($section['shape'] === 'rectangular') {
                $firstSize = $section['width'];
                $secondsize = $section['height'];
            }
            $this->ductApd->globalSetter(
                $section['shape'],
                $section['material'],
                $firstSize,
                $secondsize,
                $section['flowRate'],
                $section['length'],
                $section['singularities'],
                $section['additionalApd']
            );
            $totalLinearApd += $this->ductApd->getLinearApd();
            $totalSingularApd += $this->ductApd->getSingularApd();
            $totalAdditionalApd += $this->ductApd->getAdditionalApd();
        }

        $totalSingularApd = round($totalSingularApd, 3);
        $totalApd = $totalLinearApd + $totalSingularApd + $totalAdditionalApd + $generalAdditionalApd;

        $sections = [
            'totalLinearApd' => $totalLinearApd,
            'totalSingularApd' => $totalSingularApd,
            'totalAdditionalApd' => $totalAdditionalApd,
            'generalAdditionalApd' => $generalAdditionalApd,
            'totalApd' => $totalApd
        ];

        return $sections;
    }
}