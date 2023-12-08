<?php

namespace App\Domain\Apd\UseCase\AddDuctSection;

use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSection;
use Assert\Assert;
use Assert\LazyAssertionException;
use App\Domain\Apd\Factory\DuctSectionFactory;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use App\SharedKernel\Model\Singularity;

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
        $isValid = $this->checkRequest($request, $response);

        if ($isValid) {
            $ductSection = $this->setDuctSection($request);

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
                    if ($request->shape === 'circular') {return isset($value) && is_int($value);}
                }, 'Diameter is not set or not integer value')
                ->that($request->width, 'diameter')->satisfy(function($value) use($request){
                    if ($request->shape === 'rectangular') {return isset($value) && is_int($value);}
                }, 'Width is not set or not integer value')
                ->that($request->height, 'diameter')->satisfy(function($value) use($request){
                    if ($request->shape === 'rectangular') {return isset($value) && is_int($value);}
                }, 'Height is not set or not integer value')
                ->verifyNow();
            
            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }

    private function setDuctSection(AddDuctSectionRequest $request): DuctSection
    {
        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);

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