<?php

namespace App\Tests\Domain\Apd\UseCase\UpdateProject;

use App\Domain\Apd\UseCase\UpdateProject\UpdateProjectRequest;

class UpdateProjectRequestBuilder extends UpdateProjectRequest
{
    const APPUSER_ID = 1;
    const PROJECT_ID = 1;
    const NAME = 'project 1';
    const GENERAL_ALTITUDE = 2000;
    const GENERAL_TEMPERATURE = 0.0;

    public static function aRequest()
    {
        $request = new static(self::APPUSER_ID, self::PROJECT_ID);
        $request->name = self::NAME;
        $request->generalAltitude = self::GENERAL_ALTITUDE;
        $request->generalTemperature = self::GENERAL_TEMPERATURE;

        return $request;
    }

    public function build()
    {
        $request = new UpdateProjectRequest($this->userId, $this->projectId);
        $request->name = $this->name;
        $request->generalAltitude = $this->generalAltitude;
        $request->generalTemperature = $this->generalTemperature;

        return $request;
    }

    public function empty()
    {
        $this->name = null;
        $this->generalAltitude = null;
        $this->generalTemperature = null;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;

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

    public function setGeneralAltitude(int $generalAltitude)
    {
        $this->generalAltitude = $generalAltitude;

        return $this;
    }

    public function setGeneralTemperature(float $generalTemperature)
    {
        $this->generalTemperature = $generalTemperature;

        return $this;
    }
}