<?php

namespace App\Tests\Domain\Apd\Entity;

use App\Domain\Apd\Entity\Project;

class ProjectBuilder
{
    private $id = null;
    private $name = 'project 1';
    private $userId = 1;
    private $generalAltitude = 0;
    private $generalTemperature = 20.0;
    private $ductNetworks = [];

    public function build(): Project
    {
        $id = $this->id ?? mt_rand(0, 500);
        
        $project = new Project($this->name);
        
        $project
            ->setId($id)
            ->setUserId($this->userId)
            ->setGeneralAltitude($this->generalAltitude)
            ->setGeneralTemperature($this->generalTemperature);

        return $project;
    }

    public static function aProject()
    {
        return new ProjectBuilder;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function setGeneralAltitude($generalAltitude)
    {
        $this->generalAltitude = $generalAltitude;

        return $this;
    }

    public function setGeneralTemperature($generalTemperature)
    {
        $this->generalTemperature = $generalTemperature;

        return $this;
    }

    public function setDuctNetworks($ductNetworks)
    {
        $this->ductNetworks = $ductNetworks;

        return $this;
    }
}