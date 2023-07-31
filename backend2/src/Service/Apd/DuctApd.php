<?php

namespace App\Service\Apd;

use App\Repository\DiameterRepository;
use App\Repository\MaterialRepository;
use App\Repository\SingularityRepository;
use App\Service\Apd\Air;

class DuctApd
{
    /**
     * Shape of the duct section : circular or rectangular
     *
     * @var string
     */
    private string $shape;

    /**
     * Material of the duct section
     *
     * @var string
     */
    private string $material;

    /**
     * Diameter of the duct section if this one is circular
     * unit : millimeter (mm)
     * 
     * @var integer
     */
    private int $diameter;

    /**
     * Width of the duct section if this one is rectangular
     * unit : millimeter (mm)
     *
     * @var integer
     */
    private int $width;

    /**
     * Height of the duct section if this one is rectangular
     * unit : millimeter (mm)
     * 
     * @var integer
     */
    private int $height;

    /**
     * Flow rate in the duct section
     * unit : cubic meter per hour (m3/h)
     *
     * @var integer
     */
    private int $flowRate;

    /**
     * Length of the duct section
     * unit : meter (m)
     *
     * @var float
     */
    private float $length;

    /**
     * List of type and number of singularities present in the duct section
     *
     * @var array
     */
    private array $singularities;

    /**
     * Value of additional apd in the duct section
     * unit : Pascal (Pa)
     *
     * @var integer
     */
    private int $additionalApd;

    /**
     * Value of the equivalent diameter for rectangular shape
     * Equal to the diameter when the shape is circular
     * unit : millimeter (mm)
     *
     * @var float
     */
    private float $equivDiameter;

    /**
     * Section of the duct section
     * unit : square meter (m²)
     *
     * @var float
     */
    public float $section;

    /**
     * Flow speed of the air in the duct section
     * unit : meter per second (m/s)
     *
     * @var float
     */
    public float $flowSpeed;

    /**
     * Linear pressure drop of the duct section
     * unit : pascal (Pa)
     *
     * @var float
     */
    private float $linearApd;

    /**
     * Singular pressure drop of the duct section
     * unit : pascal (Pa)
     *
     * @var float
     */
    private float $singularApd;

    /**
     * Total pressure drop of the duct section
     *
     * @var float
     */
    private float $totalApd;

    /**
     * Unique instance of the Air class
     *
     * @var Air
     */
    public Air $air;

    private $diameterRepository;

    private $materialRepository;

    private $singularityRepository;
    
    public function __construct(
        DiameterRepository $diameterRepository,
        MaterialRepository $materialRepository,
        SingularityRepository $singularityRepository
    )
    {    
        $this->air = Air::getInstance();
        
        $this->diameterRepository = $diameterRepository;
        $this->materialRepository = $materialRepository;
        $this->singularityRepository = $singularityRepository;
    }

    /**
     * Global setter for all parameters. Use in priority just after instantiation
     *
     * @param string $shape
     * @param string $material
     * @param integer $firstSize
     * @param integer|null $secondSize
     * @param integer $flowRate
     * @param float $length
     * @param array $singularities
     * @param integer $additionalApd
     * @return void
     */
    public function globalSetter(
        string $shape, 
        string $material, 
        int $firstSize, 
        int $secondSize=null,
        int $flowRate,
        float $length,
        array $singularities = [],
        int $additionalApd=0
    ): void
    {
        $this->shape = $shape;
        $this->material = $material;
        if($shape === 'circular'){
            $this->diameter = $firstSize;
            $this->width = 0;
            $this->height = 0;
        } else {
            $this->diameter = 0;
            $this->width = $firstSize;
            $this->height = $secondSize;
        }
        $this->flowRate = $flowRate;
        $this->length = $length;
        $this->singularities = $singularities;
        $this->additionalApd = $additionalApd;

        $this->setEquiveDiameter();
        $this->setSection();
        $this->setFlowSpeed();
    }

    /**
     * Set the equivalent diameter of the duct section
     *
     * @return boolean
     */
    private function setEquiveDiameter(): bool
    {
        if($this->shape === 'circular'){
            $this->equivDiameter = $this->diameter;
        } elseif ($this->shape === 'rectangular'){
            $this->equivDiameter = round((1.265 * ($this->width * $this->height) ** 0.6) 
                / ($this->width + $this->height) ** 0.2, 3);
        } else {
            $this->equivDiameter = 0;
            return false;
        }
        return true;
    }

    /**
     * Get section of a duct section
     *
     * @param string $shape
     * @param int $firstSize
     * @param int $secondSize
     * @return float
     */
    public static function getSection(string $shape, int $firstSize, int $secondSize=0): float
    {
        if($shape === 'circular'){
            $equivDiameter = $firstSize;
        } elseif ($shape === 'rectangular'){
            $equivDiameter = (1.265 * ($firstSize * $secondSize) ** 0.6) 
                / ($firstSize + $secondSize) ** 0.2;
        } else {
            $equivDiameter = 0;
        }

        return round((pi() * ($equivDiameter/1000)**2)/4, 3);
    }

    /**
     * Set the section of duct shape
     *
     * @return boolean
     */
    private function setSection(): bool
    {
        $this->section = round((pi() * ($this->equivDiameter/1000)**2)/4,3);
        return true;
    }

    /**
     * Get the flowspeed of a duct section
     *
     * @param int $flowRate
     * @param string $shape
     * @param int $firstSize
     * @param int $secondSize
     * @return float
     */
    public static function getFlowSpeed(int $flowRate, string $shape, int $firstSize, int $secondSize=0): float
    {
        $section = self::getSection($shape, $firstSize, $secondSize);

        return round(($flowRate / 3600) / $section, 3);
    }

    /**
     * Set the flowspeed in the duct section
     *
     * @return boolean
     */
    private function setFlowSpeed(): bool
    {
        $this->flowSpeed = round(($this->flowRate / 3600) / $this->section,3);
        return true;
    }

    public function getOptimalDimensions(string $shape, int $flowRate, int $secondSize=0, float $idealFlowSpeed = 7): float
    {
        $optimalSection = ($flowRate / 3600) / $idealFlowSpeed; 

        if($shape === 'circular'){
            $optimalDiameter = sqrt(($optimalSection * 4) / pi()) * 1000;
            $optimalDimension = $this->diameterRepository->findOneByDiameter($optimalDiameter)->getDiameter();
        } elseif ($shape === 'rectangular') {
            $optimalDimension = round(($optimalSection / ($secondSize / 1000)) * 1000);
        }
        
        return round($optimalDimension, 3);
    }

    /**
     * Get linear pressure drop of the duct section
     *
     * @return  float
     */ 
    public function getLinearApd(): float
    {
        $this->setLinearApd();
        return $this->linearApd;
    }

    /**
     * Set the linear apd of the duct section
     *
     * @return boolean
     */
    private function setLinearApd(): bool
    {

        $reynolds = ($this->flowSpeed * ($this->equivDiameter * 10 ** -3)) / $this->air->getViscosity();
        $laminarLambda = 64 / $reynolds;

        if($reynolds <= 2400){
            $this->linearApd = ($laminarLambda * $this->length * $this->air->getDensity() *
            ($this->flowSpeed ** 2)) / (2 * ($this->equivDiameter / 1000));
            return true;
        }

        $roughness = $this->materialRepository->findOneByMaterial($this->material)->getRoughness();
        $b = 2.51 / $reynolds;

        $roughnessLambda = (1 / (-2 * log10($roughness))) ** 2;
        $lambdaList = [(1 / (-2 * log10($roughness + $b * (1 / sqrt($roughnessLambda))))) ** 2];

        for ($i=0; $i < 12 ; $i++) { 
            $tempLambda = (1 / (-2 * log10($roughness + $b * (1 / sqrt(end($lambdaList)))))) ** 2;
            $lambdaList[] = $tempLambda;
        }
        $lambdaList2 = [];
        foreach($lambdaList as $lambda){
            $lambdaList2[] = $lambda - $roughnessLambda;
        }
        $minLambda2 = min(array_slice($lambdaList2, 2));
        $lambdaDef = $lambdaList[array_search($minLambda2, array_slice($lambdaList2, 0, 6))];

        $this->linearApd = round(($lambdaDef * $this->length * $this->air->getDensity() * ($this->flowSpeed ** 2)) /
            (2 * ($this->equivDiameter / 1000)), 3);

        return true;
    }

    /**
     * Get the singular apd of the duct section
     *
     * @return float
     */
    public function getSingularApd(): float
    {
        $this->setSingularApd();
        return $this->singularApd;
    }
    
    /**
     * Set the singular apd of the duct section
     *
     * @return boolean
     */
    private function setSingularApd(): bool
    {
        $totalSingularities = 0;
        foreach($this->singularities as $singularityName => $singularityCount)
        {
            $totalSingularities += $this->singularityRepository->findOneByNameAndShape($singularityName, $this->shape)->getSingularity() * $singularityCount;
        }
        $this->singularApd = round($totalSingularities * $this->air->getDensity() * ($this->flowSpeed ** 2) / 2, 3);
        
        return true;
    }

    /**
     * Get value of additional apd in the duct section
     *
     * @return  integer
     */ 
    public function getAdditionalApd(): int
    {
        return $this->additionalApd;
    }

    /**
     * Get total pressure drop of the duct section
     *
     * @return  float
     */ 
    public function getTotalApd(): float
    {
        $this->setTotalApd();
        return $this->totalApd;
    }

    /**
     * Set total pressure drop of the duct section
     *
     * @return  bool
     */ 
    public function setTotalApd(): bool
    {
        $this->setLinearApd();
        $this->setSingularApd();
        
        $this->totalApd = $this->linearApd + $this->singularApd + $this->additionalApd;

        return true;
    }
}