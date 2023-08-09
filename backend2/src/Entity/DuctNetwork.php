<?php

namespace App\Entity;

use App\Entity\DuctSection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DuctNetwork
{
    /**
     * Additional apd for the duct network
     *
     * @var integer
     */
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero()]
    private int $additionalApd;
    
    /**
     * The temperature of the air in the duct network
     *
     * @var integer
     */
    #[Assert\Type('integer')]
    private int $temperature;

    /**
     * The altitude of the air in the duct network
     *
     * @var integer
     */
    #[Assert\Type('integer')]
    private int $altitude;

    /**
     * A duct section of the duct network
     *
     */
    // #[ORM\ManyToOne(targetEntity:DuctSection::class, cascade:['persist'])]
    #[Assert\NotBlank]
    private $ductSections;

    public function __construct()
    {
        $this->setAdditionalApd(0);
        $this->setTemperature(20);
        $this->setAltitude(0);
    }
    
    /**
     * Get the value of additionalApd
     *
     */
    public function getAdditionalApd()
    {
        return $this->additionalApd;
    }

    /**
     * Set the valur of additionnalApd
     *
     * @return self
     */
    public function setAdditionalApd($additionalApd)
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    /**
     * Get the temperature of the air in the duct network
     *
     * @return  integer
     */ 
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Set the temperature of the air in the duct network
     *
     * @param  integer  $temperature  The temperature of the air in the duct network
     *
     * @return  self
     */ 
    public function setTemperature($temperature): self
    {
        $this->temperature = $temperature;
        
        return $this;
    }

    /**
     * Get the altitude of the air in the duct network
     *
     * @return  integer
     */ 
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Set the altitude of the air in the duct network
     *
     * @param  integer  $altitude  The altitude of the air in the duct network
     *
     * @return  self
     */ 
    public function setAltitude($altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

    /**
     * Get a duct section of the duct network
     *
     * @return Array
     */ 
    public function getDuctSections(): Array
    {
        return $this->ductSections;
    }

    /**
     * Set a duct section of the duct network
     *
     * @param  DuctSection  $ductSection  A duct section of the duct network
     *
     * @return  self
     */ 
    public function addDuctSections(DuctSection $ductSection): self
    {
        $ductSection->air->setTemperature($this->temperature);
        $ductSection->air->setAltitude($this->altitude);
        $this->ductSections[] = $ductSection;

        return $this;
    }
}