<?php

namespace App\Domain\Apd\UseCase\UpdateDuctNetwork;

use Assert\Assert;
use App\Domain\Apd\Entity\Project;
use Assert\LazyAssertionException;
use App\SharedKernel\Model\Material;
use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class UpdateDuctNetwork
{
    private $projectRepository;
    private $ductNetworkRepository;
    private $ductSectionRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository, 
        DuctNetworkRepositoryInterface $ductNetworkRepository,
        DuctSectionRepositoryInterface $ductSectionRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
        $this->ductSectionRepository = $ductSectionRepository;
    }

    public function execute(UpdateDuctNetworkRequest $request, UpdateDuctNetworkPresenter $presenter)
    {
        $response = new UpdateDuctNetworkResponse();
        $oldDuctNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->id);
        $project = $this->projectRepository->getProjectById($request->projectId);
        $isValid = $this->checkDuctNetworkExist($oldDuctNetwork, $response);
        $isValid = $isValid && $this->checkProjectExist($project, $response);
        $isValid = $isValid && $this->checkDuctNetworkExistInProject($request, $response, $project);
        $isValid = $isValid && $this->checkRequest($request, $response);

        if ($isValid) {
            $ductNetwork = $this->updateDuctNetwork($request, $oldDuctNetwork, $project);

            $this->ductNetworkRepository->updateDuctNetwork($ductNetwork);

            $response->setDuctNetwork($ductNetwork);
        }

        $presenter->present($response);
    }

    private function checkRequest(UpdateDuctNetworkRequest $request, UpdateDuctNetworkResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->name, 'name')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_string($value) && $value !== "";
                    }
                }, 'Name must be a string value')
                ->that($request->altitude, 'altitude')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_int($value);
                    }
                }, 'Altitude must be an integer value')
                ->that($request->temperature, 'temperature')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_float($value);
                    }
                }, 'Temperature must be a float value')
                ->that($request->generalMaterial, 'generalMaterial')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return in_array($value, array_keys(Material::$material));
                    }
                }, "GeneralMaterial must be a value of this : ". implode(', ',array_keys(Material::$material)))
                ->that($request->additionalApd, 'additionalApd')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_int($value);
                    }
                }, 'AdditionalApd must be an integer value')
                ->verifyNow();
            
            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }

    private function checkDuctNetworkExist(?DuctNetwork $ductNetwork, UpdateDuctNetworkResponse $response): bool
    {
        if ($ductNetwork) {
            return true;
        }
        $response->addError('id', 'Duct Network doesn\'t exist with this id');
        return false;
    }

    private function checkProjectExist(?Project $project, UpdateDuctNetworkResponse $response): bool
    {
        if ($project) {
            return true;
        }
        $response->addError('projectId', 'Project doesn\'t exist with this id');
        return false;
    }

    private function checkDuctNetworkExistInProject(UpdateDuctNetworkRequest $request, UpdateDuctNetworkResponse $response, Project $project): bool
    {
        foreach ($project->getDuctNetworks() as $ductNetwork) {
            if ($ductNetwork->getId() === $request->id) {
                return true;
            }
        }

        $response->addError('id', 'Duct network don\'t belong to this project');
        return false;
    }

    private function updateDuctNetwork(UpdateDuctNetworkRequest $request, DuctNetwork $oldDuctNetwork, Project $project): DuctNetwork
    {
        $project->removeDuctNetwork($oldDuctNetwork);

        if (is_null($request->name)) {
            $name = $oldDuctNetwork->getName();
        } else {
            $name = $request->name;
        }

        if (is_null($request->generalMaterial)) {
            $generalMaterial = $oldDuctNetwork->getGeneralMaterial();
        } else {
            $generalMaterial = $request->generalMaterial;
        }
        
        $ductNetwork = new DuctNetwork(
            $name,
            $generalMaterial
        );
        
        $ductNetwork->setId($oldDuctNetwork->getId());

        foreach ($oldDuctNetwork->getDuctSections() as $ductSection) {
            $ductNetwork->addDuctSection($ductSection);
        }

        if (!is_null($request->altitude)) {
            $ductNetwork->setAltitude($request->altitude);
        } else {
            $ductNetwork->setAltitude($project->getGeneralAltitude());
        }
        if (!is_null($request->temperature)) {
            $ductNetwork->setTemperature($request->temperature);
        } else {
            $ductNetwork->setTemperature($project->getGeneralTemperature());
        }
        if (!is_null($request->additionalApd)) {
            $ductNetwork->setAdditionalApd($request->additionalApd);
        } else {
            $ductNetwork->setAdditionalApd($oldDuctNetwork->getAdditionalApd());
        }
        $ductNetwork->calculate();
        
        foreach ($ductNetwork->getDuctSections() as $ductSection) {
            $this->ductSectionRepository->updateDuctSection($ductSection);
        }

        $ductNetwork->setProjectId($project->getId());
        $project->addDuctNetwork($ductNetwork);
        $this->projectRepository->updateProject($project);

        

        return $ductNetwork;
    }
}