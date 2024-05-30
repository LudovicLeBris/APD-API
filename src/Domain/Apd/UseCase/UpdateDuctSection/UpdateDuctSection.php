<?php

namespace App\Domain\Apd\UseCase\UpdateDuctSection;

use App\Domain\Apd\Entity\DuctNetwork;
use Assert\Assert;
use Assert\LazyAssertionException;
use App\SharedKernel\Model\Material;
use App\Domain\Apd\Entity\DuctSection;
use App\Domain\Apd\Factory\DuctSectionFactory;
use App\Domain\Apd\Entity\DuctNetworkRepositoryInterface;
use App\Domain\Apd\Entity\DuctSectionRepositoryInterface;
use App\SharedKernel\Model\CircularDiameters;
use App\SharedKernel\Model\Singularity;

class UpdateDuctSection
{
    private $ductSectionRepository;
    private $ductNetworkRepository;

    public function __construct(
        DuctSectionRepositoryInterface $ductSectionRepository,
        DuctNetworkRepositoryInterface $ductNetworkRepository)
    {
        $this->ductSectionRepository = $ductSectionRepository;
        $this->ductNetworkRepository = $ductNetworkRepository;
    }

    public function execute(UpdateDuctSectionRequest $request, UpdateDuctSectionPresenter $presenter)
    {
        $response = new UpdateDuctSectionResponse();
        $oldDuctSection = $this->ductSectionRepository->getDuctSectionById($request->id);
        $ductNetwork = $this->ductNetworkRepository->getDuctNetworkById($request->ductNetworkId);
        $isValid = $this->checkDuctNetworkExist($ductNetwork, $response);
        $isValid = $isValid && $this->checkDuctSectionExist($oldDuctSection, $response);
        $isValid = $isValid && $this->checkDuctSectionExistInDuctNetwork($request, $response, $ductNetwork);
        $isValid = $isValid && $this->checkRequest($request, $response, $oldDuctSection);

        if ($isValid) {
            $ductSection = $this->updateDuctSection($request, $oldDuctSection, $ductNetwork);

            $this->ductSectionRepository->updateDuctSection($ductSection);

            $response->setDuctSection($ductSection);
        }

        $presenter->present($response);
    }

    private function checkRequest(UpdateDuctSectionRequest $request, UpdateDuctSectionResponse $response, DuctSection $oldDuctSection)
    {
        try {
            Assert::lazy()
                ->that($request->name, 'name')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_string($value);
                    }
                }, 'Name must be a string value')

                ->that($request->shape, 'shape')->satisfy(function($value){
                    if (!is_null($value)) {
                        return in_array($value, ['circular', 'rectangular']) && is_string($value);
                    }
                }, 'Shape must be a string with "circular" or "rectangular" value')

                ->that($request->material, 'material')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_string($value) && in_array($value, array_keys(Material::$material));
                    }
                }, "Material must be a value like : ". implode(', ', array_keys(Material::$material)))

                ->that($request->flowrate, 'flowrate')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_int($value) && $value > 0;
                    }
                }, 'Flowrate must be a positive integer value')

                ->that($request->length, 'length')->satisfy(function($value) {
                    if(!is_null($value)) {
                        return (is_float($value) || is_int($value)) && $value > 0;
                    }
                }, 'Length must be a positive integer or float value')

                ->that($request->singularities, 'singularities')->satisfy(function($values) use($request, $oldDuctSection){
                    if(is_null($request->shape)) {
                        $singularities = Singularity::getSingularitiesByShape($oldDuctSection->getShape());
                    } else {
                        $singularities = Singularity::getSingularitiesByShape($request->shape);
                    }
                    if (!is_null($request->singularities)) {
                        if (!is_array($request->singularities)) {return false;}
                        foreach($values as $key => $value) {
                            if (!array_key_exists($key, $singularities)) {
                                return false;
                            }
                            if (!is_int($value) || $value < 1) {
                                return false;
                            }
                        }
                        return true;
                    }
                }, 'In singularities : key invalid or not integer value equal or greather than 1')

                ->that($request->additionalApd, 'additionalApd')->satisfy(function($value) {
                    if (!is_null($value)) {
                        return is_int($value) && $value >= 0;
                    }
                }, 'AdditionalApd must be a positive integer value')

                ->that($request->diameter, 'diameter')->satisfy(function($value) use($request, $oldDuctSection){
                    if (is_null($request->shape)) {
                        if (!is_null($value)) {
                            return is_int($value) && in_array($value, CircularDiameters::$diameters);
                        }
                    } else {
                        if ($request->shape === 'circular' && $oldDuctSection->getShape() === 'rectangular') {
                            return isset($value) && is_int($value) && in_array($value, CircularDiameters::$diameters);
                        }
                    }
                }, 'Diameter is missing or not an integer value or not a normalized diameter like : '. implode(', ', CircularDiameters::$diameters))

                ->that($request->width, 'width')->satisfy(function($value) use($request, $oldDuctSection){
                    if (is_null($request->shape)) {
                        if (!is_null($value)) {
                            return is_int($value) && $value > 0;
                        }
                    } else {
                        if ($request->shape === 'rectangular' && $oldDuctSection->getShape() === 'circular') {
                            return isset($value) && is_int($value) && $value > 0;
                        }
                    }
                }, 'Width is missing or not positive integer value')

                ->that($request->height, 'height')->satisfy(function($value) use($request, $oldDuctSection){
                    if (is_null($request->shape)) {
                        if (!is_null($value)) {
                            return is_int($value) && $value > 0;
                        }
                    } else {
                        if ($request->shape === 'rectangular' && $oldDuctSection->getShape() === 'circular') {
                            return isset($value) && is_int($value) && $value > 0;
                        }
                    }
                }, 'Height is missing or not positive integer value')
                
            ->verifyNow();
            
            return true;

        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage(), 422);
            }
            return false;
        }
    }

    private function checkDuctSectionExist(?DuctSection $ductSection, UpdateDuctSectionResponse $response): bool
    {
        if ($ductSection) {
            return true;
        }
        $response->addError('id', 'Duct section doesn\'t exist with this id', 404);
        return false;
    }

    private function checkDuctNetworkExist(?DuctNetwork $ductNetwork, UpdateDuctSectionResponse $response): bool
    {
        if ($ductNetwork) {
            return true;
        }
        $response->addError('ductNetworkId', 'Duct Network doesn\'t exist with this id', 404);
        return false;
    }

    private function checkDuctSectionExistInDuctNetwork(UpdateDuctSectionRequest $request, UpdateDuctSectionResponse $response, DuctNetwork $ductNetwork): bool
    {
        foreach ($ductNetwork->getDuctSections() as $ductSection) {
            if ($ductSection->getId() === $request->id) {
                return true;
            }
        }

        $response->addError('id', 'Duct section don\'t belong to this duct network', 404);
        return false;
    }

    private function updateDuctSection(UpdateDuctSectionRequest $request, DuctSection $oldDuctSection, DuctNetwork $ductNetwork): DuctSection
    {
        $ductNetwork->removeDuctSection($oldDuctSection);

        $technicalDatas = $request->getContent();
        $technicalDatas['air'] = $ductNetwork->getAir();
        if (is_null($technicalDatas['shape'])) {
            $technicalDatas['shape'] = $oldDuctSection->getShape();
        }
        if (is_null($technicalDatas['material'])) {
            $technicalDatas['material'] = $ductNetwork->getGeneralMaterial();
        }
        if (is_null($technicalDatas['flowrate'])) {
            $technicalDatas['flowrate'] = $oldDuctSection->getFlowrate();
        }
        if (is_null($technicalDatas['length'])) {
            $technicalDatas['length'] = $oldDuctSection->getLength();
        }
        if (is_null($technicalDatas['singularities'])) {
            $technicalDatas['singularities'] = $oldDuctSection->getSingularities();
        }
        if (is_null($technicalDatas['additionalApd'])) {
            $technicalDatas['additionalApd'] = $oldDuctSection->getAdditionalApd();
        }
        if (is_null($technicalDatas['diameter']) && $oldDuctSection->getShape() === 'circular') {
            $technicalDatas['diameter'] = $oldDuctSection->getDiameter();
        }
        if (is_null($technicalDatas['width']) && $oldDuctSection->getShape() === 'rectangular') {
            $technicalDatas['width'] = $oldDuctSection->getWidth();
        }
        if (is_null($technicalDatas['height']) && $oldDuctSection->getShape() === 'rectangular') {
            $technicalDatas['height'] = $oldDuctSection->getHeight();
        }

        $ductSectionFactory = new DuctSectionFactory();
        $ductSectionFactory->setSectionTechnicalDatas($technicalDatas);
        $ductSection = $ductSectionFactory->createDuctSection();
        $ductSection->setDuctNetworkId($ductNetwork->getId());
        $ductSection->setId($oldDuctSection->getId());

        if (is_null($technicalDatas['name'])) {
            $ductSection->setName($oldDuctSection->getName());
        } else {
            $ductSection->setName($technicalDatas['name']);
        }

        $ductNetwork->addDuctSection($ductSection);
        $this->ductNetworkRepository->updateDuctNetwork($ductNetwork);

        return $ductSection;
    }
}