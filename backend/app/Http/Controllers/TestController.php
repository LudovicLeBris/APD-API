<?php

namespace App\Http\Controllers;

use App\Apd\DuctApd;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        $duct1Singularities = [
            "90_elbow" => 2,
        ];
        $duct2Singularities = [
            "90_sep_tee" => 1,
            "90_sharp_elbow" => 2,
            "45_sharp_elbow" => 4
        ];
        $ductSection1 = new DuctApd('circular', 'galvanised steel', 250, 0 , 1000, 1, $duct1Singularities, 50);
        $ductSection2 = new DuctApd('circular', 'galvanised steel', 250, 0 , 1000, 1, $duct2Singularities);
        $sectionLinearApd1 = $ductSection1->getLinearApd();
        $sectionSingularApd1 = $ductSection1->getSingularApd();
        $ductSection2->air->setAltitude(2000);
        $sectionLinearApd2 = $ductSection2->getLinearApd();
        $sectionSingularApd2 = $ductSection2->getSingularApd();
        $totalApd1 = $ductSection1->getTotalApd();
        $totalApd2 = $ductSection2->getTotalApd();
        $optimalSection = DuctApd::getOptimalDimensions('circular', 1500);

        return view('test', [
            'sectionLinearApd1' => $sectionLinearApd1,
            'sectionLinearApd2' => $sectionLinearApd2,
            'sectionSingularApd1' => $sectionSingularApd1,
            'sectionSingularApd2' => $sectionSingularApd2,
            'sectionTotalApd1' => $totalApd1,
            'sectionTotalApd2' => $totalApd2,
            'optimalSection' => $optimalSection,
        ]);
    }
}
