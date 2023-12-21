<?php

namespace App\Domain\Apd\UseCase\AddDuctNetwork;

use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\Project;
use App\Domain\Apd\Entity\ProjectRepositoryInterface;
use App\SharedKernel\Model\Material;
use Assert\Assert;
use Assert\LazyAssertionException;

class AddDuctNetwork
{
    private $projectRepository;
    private $ductNetworkRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository,
    )
    {
        $this->projectRepository = $projectRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
    }
    
    public function execute(AddDuctNetworkRequest $request, AddDuctNetworkPresenter $presenter)
    {
        $response = new AddDuctNetworkResponse();
        $project = $this->projectRepository->getProjectById($request->projectId);
        $isValid = $this->checkProjectExist($project, $response);
        $isValid = $isValid && $this->checkRequest($request, $response);

        if ($isValid) {
            $ductNetwork = $this->setDuctNetwork($request, $project);

            $this->ductNetworkRepository->addDuctNetwork($ductNetwork);

            $response->setDuctNetwork($ductNetwork);
        }

        $presenter->present($response);

    }

    private function checkRequest(AddDuctNetworkRequest $request, AddDuctNetworkResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->name, 'name')->notEmpty('Name is empty')->string()
                ->that($request->generalMaterial, 'generalMaterial')->notEmpty('material is empty')->inArray(array_keys(Material::$material))
                ->that($request->additionalApd, 'additionalApd')->integer()->greaterThan(-1, 'AdditionalApd must be positive')
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage(), 422);
            }
            return false;
        }
    }

    private function checkProjectExist(?Project $project, AddDuctNetworkResponse $response)
    {
        if ($project) {
            return true;
        }
        $response->addError('projectId', 'Project doesn\'t exist with this id', 404);
        return false;
    }

    private function setDuctNetwork(AddDuctNetworkRequest $request, Project $project): DuctNetwork
    {
        $ductNetwork = new DuctNetwork(
            $request->name,
            $request->generalMaterial,
            $request->additionalApd
        );
        
        $ductNetwork
            ->setAltitude($project->getGeneralAltitude())
            ->setTemperature($project->getGeneralTemperature())
            ->setProjectId($project->getId());
        
        $project->addDuctNetwork($ductNetwork);
        $this->projectRepository->updateProject($project);

        return $ductNetwork;
    }
}