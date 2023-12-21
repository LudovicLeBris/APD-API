<?php

namespace App\Domain\Apd\UseCase\AddDuctSection;

use Assert\Assert;
use Assert\LazyAssertionException;
use App\Domain\Apd\Entity\DuctNetwork;
use App\Domain\Apd\Entity\DuctSection;
use App\SharedKernel\Model\Singularity;
use App\SharedKernel\Model\CircularDiameters;
use App\Domain\Apd\Factory\DuctSectionFactory;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;

class AddDuctSection
{
    private $ductSectionRepository;
    private $ductNetworkRepository;

    public function __construct(
        DuctSectionRepositoryInterface $ductSectionRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository
    )
    {
        $this->ductSectionRepository = $ductSectionRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
    }

    public function execute(AddDuctSectionRequest $request, AddDuctSectionPresenter $presenter)
    {
        $response = new AddDuctSectionResponse();
        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
        $isValid = $this->checkDuctNetworkExist($ductNetwork, $response);
        $isValid = $isValid && $this->checkRequest($request, $response);

        if ($isValid) {
            $ductSection = $this->setDuctSection($request, $ductNetwork);

            $this->ductSectionRepository->addDuctSection($ductSection);

            $response->setDuctSection($ductSection);
        }

        $presenter->present($response);

    }

    private function checkRequest(AddDuctSectionRequest $request, AddDuctSectionResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->name, 'name')->notEmpty('Name is empty')->string()
                ->that($request->shape, 'shape')->notEmpty('Shape is empty')->inArray(['circular', 'rectangular'])
                ->that($request->flowrate, 'flowrate')->notEmpty('Flowrate is empty')->integer()->greaterThan(0, 'Flowrate must be positive')
                ->that($request->length, 'length')->notEmpty('Length is empty')->float()->greaterThan(0, 'Length must be positive')
                ->that($request->singularities, 'singularities')->isArray('Singularities must be an array')->satisfy(function($values) use($request){
                    if ($request->shape === null) {return true;}
                    $singularities = Singularity::getSingularitiesByShape($request->shape);
                    foreach($values as $key => $value) {
                        if (!array_key_exists($key, $singularities)) {
                            return false;
                        }
                        if (!is_int($value)) {
                            return false;
                        }
                    }
                    return true;
                }, 'In singularities : key invalid or not integer value')
                ->that($request->additionalApd, 'additionalApd')->integer()->greaterThan(-1, 'AdditionalApd must be positive')
                ->that($request->diameter, 'diameter')->satisfy(function($value) use($request){
                    if ($request->shape === 'circular') {
                        return isset($value) && is_int($value) && in_array($value, CircularDiameters::$diameters);
                    }
                }, 'Diameter is not set or not integer value or not a normalized diameter like : '. implode(', ', CircularDiameters::$diameters))
                ->that($request->width, 'width')->satisfy(function($value) use($request){
                    if ($request->shape === 'rectangular') {
                        return isset($value) && is_int($value) && $value > 0;
                    }
                }, 'Width is not set or not a positive integer value')
                ->that($request->height, 'height')->satisfy(function($value) use($request){
                    if ($request->shape === 'rectangular') {
                        return isset($value) && is_int($value) && $value > 0;
                    }
                }, 'Height is not set or not a positive integer value')
                ->verifyNow();
            
            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage(), 422);
            }
            return false;
        }
    }

    private function checkDuctNetworkExist(?DuctNetwork $ductNetwork, AddDuctSectionResponse $response)
    {
        if ($ductNetwork) {
            return true;
        }
        $response->addError('ductNetworkId', 'Duct Network doesn\'t exist with this id', 404);
        return false;
    }

    private function setDuctSection(AddDuctSectionRequest $request, DuctNetwork $ductNetwork): DuctSection
    {
        $technicalDatas = $request->getContent();
        $technicalDatas['air'] = $ductNetwork->getAir();
        $technicalDatas['material'] = $ductNetwork->getGeneralMaterial();

        $ductSectionFactory = new DuctSectionFactory();
        $ductSectionFactory->setSectionTechnicalDatas($technicalDatas);
        $ductSection = $ductSectionFactory->createDuctSection();
        $ductSection->setName($request->name);
        $ductSection->setDuctNetworkId($ductNetwork->getId());

        $ductNetwork->addDuctSection($ductSection);
        $this->ductNetworkRepository->updateDuctNetwork($ductNetwork);

        return $ductSection;
    }
}