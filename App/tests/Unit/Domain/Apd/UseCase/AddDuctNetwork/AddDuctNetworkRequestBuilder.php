<?php

namespace App\Tests\Domain\Apd\UseCase\AddDuctNetwork;

use App\Domain\Apd\UseCase\AddDuctNetwork\AddDuctNetworkRequest;

class AddDuctNetworkRequestBuilder extends AddDuctNetworkRequest
{
    const NAME = 'duct network 1';
    CONST GENERAL_MATERIAL = 'galvanised_steel';
    const ADDITIONAL_APD = 10;

    public static function aRequest()
    {
        $request = new static(1);
        $request->name = self::NAME;
        $request->generalMaterial = self::GENERAL_MATERIAL;
        $request->additionalApd = self::ADDITIONAL_APD;

        return $request;
    }

    public function build()
    {
        $request = new AddDuctNetworkRequest(1);
        $request->name = $this->name;
        $request->generalMaterial = $this->generalMaterial;
        $request->additionalApd = $this->additionalApd;

        return $request;
    }

    public function empty()
    {
        $this->name = null;
        $this->generalMaterial = null;
        $this->additionalApd = null;
    }

    public function setProjectId(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setGeneralMaterial(string $generalMaterial)
    {
        $this->generalMaterial = $generalMaterial;
    }

    public function setAdditionalApd(int $additionalApd)
    {
        $this->additionalApd = $additionalApd;
    }
}