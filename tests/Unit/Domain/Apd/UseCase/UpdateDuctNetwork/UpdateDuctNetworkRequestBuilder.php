<?php

namespace App\Tests\Domain\Apd\UseCase\UpdateDuctNetwork;

use App\Domain\Apd\UseCase\UpdateDuctNetwork\UpdateDuctNetworkRequest;

class UpdateDuctNetworkRequestBuilder extends UpdateDuctNetworkRequest
{
    const DUCTNETWORK_ID = 1;
    const PROJECT_ID = 1;
    const NAME = 'duct network n°1';
    const ALTITUDE = 2000;
    const TEMPERATURE = 0.0;
    const GENERAL_MATERIAL = 'aluminium';
    const ADDITIONAL_APD = 30;

    public static function aRequest()
    {
        $request = new static(self::PROJECT_ID, self::DUCTNETWORK_ID);
        $request->name = self::NAME;
        $request->altitude = self::ALTITUDE;
        $request->temperature = self::TEMPERATURE;
        $request->generalMaterial = self::GENERAL_MATERIAL;
        $request->additionalApd = self::ADDITIONAL_APD;

        return $request;
    }

    public function build()
    {
        $request = new UpdateDuctNetworkRequest($this->projectId, $this->id);
        $request->name = $this->name;
        $request->altitude = $this->altitude;
        $request->temperature = $this->temperature;
        $request->generalMaterial = $this->generalMaterial;
        $request->additionalApd = $this->additionalApd;

        return $request;
    }

    public function empty()
    {
        $this->name = null;
        $this->altitude = null;
        $this->temperature = null;
        $this->generalMaterial = null;
        $this->additionalApd = null;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    public function setProjectId(int $projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function setAltitude(string $altitude)
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function setTemperature(string $temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function setGeneralMaterial(string $generalMaterial)
    {
        $this->generalMaterial = $generalMaterial;

        return $this;
    }

    public function setAdditionalApd(string $additionalApd)
    {
        $this->additionalApd = $additionalApd;

        return $this;
    }
}