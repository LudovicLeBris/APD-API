<?php

namespace App\Domain\Apd\Entity;

class Air
{
    /**
     * Air viscosity property
     *
     * @var float
     */
    private float $viscosity;

    /**
     * Air density property
     *
     * @var float
     */
    private float $density;

    /**
     * Air temperature property
     *
     * @var float
     */
    private float $temperature;

    /**
     * Air altitude property
     *
     * @var int
     */
    private int $altitude;

    /**
     * Class contructor (private)
     * Only the class code can instanciate this class
     */
    public function __construct()
    {
        $this->temperature = 20.0;
        $this->altitude = 0;
        $this->setViscosity();
        $this->setDensity();
    }

    /**
     * Get air viscosity property
     *
     * @return  float
     */ 
    public function getViscosity(): float
    {
        return $this->viscosity;
    }

    /**
     * Set air viscosity property
     *
     * @param  float  $viscosity  Air viscosity property
     *
     * @return  self
     */ 
    private function setViscosity()
    {
        $this->viscosity = (8.8848 * 10 ** (-15) * ($this->temperature + 273.15) ** 3 
            - 3.2398 * 10 ** (-11) * ($this->temperature + 273.15) ** 2 + 6.2657 * 10 ** (-8) 
            * ($this->temperature + 273.15) + 2.3544 * 10 ** (-6)) / (353.05 / ($this->temperature + 273.15));
            
        return $this;
    }
    
    /**
     * Get air density property
     *
     * @return  float
     */ 
    public function getDensity(): float
    {
        return $this->density;
    }

    /**
     * Set air density property
     *
     * @param  float  $density  Air density property
     *
     * @return  self
     */ 
    private function setDensity()
    {
        $atm_pressure = (760.85 * exp((-0.2840437333 * $this->altitude) 
            / (8.31432 * ($this->temperature + 273.15)))) * 133.32;
            
        $this->density = (($atm_pressure * 28.976) / (8.3144621 * ($this->temperature + 273.15))) / 1000;

        return $this;
    }

    /**
     * Get air temperature property
     *
     * @return  float
     */ 
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /**
     * Set air temperature property
     *
     * @param  int  $temperature  Air temperature property
     *
     * @return  self
     */ 
    public function setTemperature(float $temperature)
    {
        $this->temperature = $temperature;
        $this->setDensity();
        $this->setViscosity();

        return $this;
    }

    /**
     * Get air altitude property
     *
     * @return  int
     */ 
    public function getAltitude(): int
    {
        return $this->altitude;
    }

    /**
     * Set air altitude property
     *
     * @param  int  $altitude  Air altitude property
     *
     * @return  self
     */ 
    public function setAltitude(int $altitude)
    {
        $this->altitude = $altitude;
        $this->setDensity();
        $this->setViscosity();

        return $this;
    }




}