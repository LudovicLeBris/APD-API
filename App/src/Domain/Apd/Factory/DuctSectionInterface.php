<?php

namespace App\Domain\Apd\Factory;

interface DuctSectionInterface
{
    // public function getDuctSectionDatas();
    public function getId();
    public function getName();
    public function getDuctNetworkId();
    public function getAir();
    public function getShape();
    public function getMaterial();
    public function getFlowrate();
    public function getLength();
    public function getSingularities();
    public function getAdditionalApd();
    public function getDuctSectionsSection();
    public function getFlowspeed();
    public function getLinearApd();
    public function getSingularApd();
    public function getTotalApd();
    
    public function calculate();
    public function setEquivDiameter();
    public function setDuctSectionsSection();
    public function setFlowspeed();
    public function setLinearApd();
    public function setSingularApd();
    public function setTotalApd();
}