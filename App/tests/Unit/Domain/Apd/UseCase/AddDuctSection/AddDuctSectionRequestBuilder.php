<?php

namespace App\Tests\Domain\Apd\UseCase\AddDuctSection;

use App\Domain\Apd\UseCase\AddDuctSection\AddDuctSectionRequest;

class AddDuctSectionRequestBuilder extends AddDuctSectionRequest
{
    const NAME = 'duct section 1';
    const SHAPE = 'circular';
    const FLOWRATE = 5000;
    const LENGTH = 1.0;
    const SINGULARITIES = [
        '90_elbow' => 1
    ];
    CONST ADDITIONALAPD = 10;
    CONST DIAMETER = 500;

    public static function aRequest()
    {
        $request = new static(1);
        $request->name = self::NAME;
        $request->shape = self::SHAPE;
        $request->flowrate = self::FLOWRATE;
        $request->length = self::LENGTH;
        $request->singularities = self::SINGULARITIES;
        $request->additionalApd = self::ADDITIONALAPD;
        $request->diameter = self::DIAMETER;

        return $request;
    }

    public function build()
    {
        $request = new AddDuctSectionRequest(1);
        $request->name = $this->name;
        $request->shape = $this->shape;
        $request->flowrate = $this->flowrate;
        $request->length = $this->length;
        $request->singularities = $this->singularities;
        $request->additionalApd = $this->additionalApd;
        $request->diameter = $this->diameter;

        return $request;
    }

    public function empty()
    {
        $this->name = null;
        $this->shape = null;
        $this->flowrate = null;
        $this->length = null;
        $this->singularities = null;
        $this->additionalApd = null;
        $this->diameter = null;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setShape($shape)
    {
        $this->shape = $shape;

        return $this;
    }

    public function setFlowrate($flowrate)
    {
        $this->flowrate = $flowrate;

        return $this;
    }

    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    public function setSingularities($singularities)
    {
        $this->singularities = $singularities;

        return $this;
    }

    public function setAdditionalApd($additionalApd)
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }

    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }
}