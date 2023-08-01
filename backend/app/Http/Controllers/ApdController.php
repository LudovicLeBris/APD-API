<?php

namespace App\Http\Controllers;

use App\Apd\DuctApd;
use App\Models\Diameter;
use App\Models\Material;
use App\Models\Singularity;
use Illuminate\Http\Request;

class ApdController extends Controller
{
    /**
     * Optimal dimension for a duct section calculation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function optimalDuctDimension(Request $request)
    {
        $shape = $request->shape;
        $flowRate = $request->flowRate;
        
        if($shape === 'rectangular'){
            $secondSize = $request->width;
        } else {
            $secondSize = 0;
        }
        
        if($request->has('flowSpeed')){
            $idealFlowSpeed = $request->flowSpeed;
            $optimalDuctDimension = DuctApd::getOptimalDimensions($shape, $flowRate, $secondSize, $idealFlowSpeed);          
        } else {
            $optimalDuctDimension = DuctApd::getOptimalDimensions($shape, $flowRate, $secondSize);
        }

        return response()->json($optimalDuctDimension, 200);
    }
    
    /**
     * section of a duct section calculation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ductSection(Request $request)
    {
        $shape = $request->shape;
        if($shape === 'circular'){
            $firstSize = $request->diameter;
            $secondSize = 0;
        } elseif ($shape === 'rectangular'){
            $firstSize = $request->width;
            $secondSize = $request->height;
        }
        $section = DuctApd::getSection($shape, $firstSize, $secondSize);

        return response()->json($section, 200);
    }

    /**
     * Flow speed in a duct section calculation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function flowSpeed(Request $request)
    {
        $flowRate = $request->flowRate;
        $shape = $request->shape;
        if($shape === 'circular'){
            $firstSize = $request->diameter;
            $secondSize = 0;
        } elseif ($shape === 'rectangular'){
            $firstSize = $request->width;
            $secondSize = $request->height;
        }
        $flowSpeed = DuctApd::getFlowSpeed($flowRate, $shape, $firstSize, $secondSize);
    
        return response()->json($flowSpeed, 200);
    }

    /**
     * Retrieve list of all diameters
     *
     * @return JsonResponse
     */
    public function listDiameters()
    {
        $diameters = Diameter::all();

        return response()->json($diameters, 200);
    }

    /**
     * Retrieve list of all duct materials
     *
     * @return JsonResponse
     */
    public function listMaterials()
    {
        $materials = Material::all();

        return response()->json($materials, 200);
    }

    /**
     * Retrieve list of singularities by duct shape
     *
     * @param string $shape
     * @return JsonResponse
     */
    public function listSingularities(string $shape)
    {
        if(!in_array($shape, ['circular', 'rectangular'])){
            return response()->json(null, 422);
        }

        $singularities = Singularity::where('shape', $shape)->get();

        return response()->json($singularities, 200);
    }

    /**
     * Air pressure drop calculation for a duct section
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setSection(Request $request)
    {
        $shape = $request->shape;
        $material = $request->material;
        if($shape === 'circular'){
            $firstSize = $request->diameter;
            $secondSize = 0;
        } elseif ($shape === 'rectangular'){
            $firstSize = $request->width;
            $secondSize = $request->height;
        }
        $flowRate = $request->flowRate;
        $length = $request->length;
        $singularities = $request->singularities;
        $additionalApd = $request->additionalApd;

        $ductSection = new DuctApd(
            $shape, $material, $firstSize, $secondSize, $flowRate, $length, $singularities, $additionalApd
        );

        if($request->has('temperature')){
            $temperature = $request->temperature;
            $ductSection->air->setTemperature($temperature);
        }
        if($request->has('altitude')){
            $altitude = $request->altitude;
            $ductSection->air->setAltitude($altitude);
        }

        $response = [
            'ductSection' => $ductSection->section,
            'flowSpeed' => $ductSection->flowSpeed,
            'linearApd' => $ductSection->getLinearApd(),
            'singularApd' => $ductSection->getSingularApd(),
            'additionalApd' => $ductSection->getAdditionalApd(),
            'totalApd' => $ductSection->getTotalApd()
        ];

        return response()->json($response, 200);
    }

    /**
     * Air pressure drop calculation for all duct section
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setSections(Request $request)
    {
        $generalAdditionalApd = $request->additionalApd;
        $sections = $request->sections;

        $totalLinearApd = 0;
        $totalSingularApd = 0;
        $totalAdditionalApd = 0;
        
        foreach($sections as $section){
            if($section['shape'] === 'circular'){
                $firstSize = $section['diameter'];
                $secondSize = 0;
            } elseif ($section['shape'] === 'rectangular'){
                $firstSize = $section['width'];
                $secondSize = $section['height'];
            }
            $ductSection = new DuctApd(
                $section['shape'],
                $section['material'],
                $firstSize,
                $secondSize,
                $section['flowRate'],
                $section['length'],
                $section['singularities'],
                $section['additionalApd']
            );
            if($request->has('temperature')){
                $temperature = $request->temperature;
                $ductSection->air->setTemperature($temperature);
            }
            if($request->has('altitude')){
                $altitude = $request->altitude;
                $ductSection->air->setAltitude($altitude);
            }
    
            $totalLinearApd += $ductSection->getLinearApd();
            $totalSingularApd += $ductSection->getSingularApd();
            $totalAdditionalApd += $ductSection->getAdditionalApd();
        }

        $totalApd = $totalLinearApd + $totalSingularApd + $totalAdditionalApd + $generalAdditionalApd;

        $response = [
            'totalLinearApd' => $totalLinearApd,
            'totalSingularApd' => $totalSingularApd,
            'totalAdditionalApd' => $totalAdditionalApd,
            'generalAdditionalApd' => $generalAdditionalApd,
            'totalApd' => $totalApd
        ];

        return response()->json($response, 200);
    }
    
}
