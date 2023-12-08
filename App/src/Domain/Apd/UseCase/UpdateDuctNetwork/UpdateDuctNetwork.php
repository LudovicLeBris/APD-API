<?php

namespace App\Domain\Apd\UseCase\UpdateDuctNetwork;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\SharedKernel\Model\Material;
use Assert\Assert;
use Assert\LazyAssertionException;

class UpdateDuctNetwork
{
    private $projectRepository;
    private $ductNetworkRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository, 
        DuctNetworkRepositoryInterface $ductNetworkRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
    }

    public function execute(UpdateDuctNetworkRequest $request, UpdateDuctNetworkPresenter $presenter)
    {
        $response = new UpdateDuctNetworkResponse();
        $isValid = $this->checkRequest($request, $response);

        if ($isValid) {
            $oldDuctNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->id);
            $ductNetwork = $this->updateDuctNetwork($request, $oldDuctNetwork);

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
                }, "GeneralMaterial must be a value of this : ". implode(',',array_keys(Material::$material)))
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

    private function updateDuctNetwork(UpdateDuctNetworkRequest $request, DuctNetwork $oldDuctNetwork): DuctNetwork
    {
        $project = $this->projectRepository->getProjectById($request->projectId);
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
        $ductNetwork->setProjectId($project->getId());
        $project->addDuctNetwork($ductNetwork);
        $this->projectRepository->updateProject($project);

        return $ductNetwork;
    }
}