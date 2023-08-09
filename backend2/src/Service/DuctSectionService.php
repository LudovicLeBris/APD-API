<?php

namespace App\Service;

use App\Entity\DuctNetwork;
use App\Entity\DuctSection;
use App\Repository\DiameterRepository;
use App\Repository\MaterialRepository;
use App\Repository\SingularityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DuctSectionService
{
    /**
     * RequestStack service
     *
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * Validator service
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * Serializer service
     *
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * Denormalizer service
     *
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;

    /**
     * Diameter Repository object
     *
     * @var DiameterRepository
     */
    private DiameterRepository $diameterRepository;

    /**
     * Material repository object
     *
     * @var MaterialRepository
     */
    private MaterialRepository $materialRepository;

    /**
     * Singularity repository object
     *
     * @var SingularityRepository
     */
    private SingularityRepository $singularityRepository;


    public function __construct(
        RequestStack $requestStack, 
        ValidatorInterface $validator,
        DiameterRepository $diameterRepository,
        MaterialRepository $materialRepository,
        SingularityRepository $singularityRepository,
        SerializerInterface $serializer,
        DenormalizerInterface $denormalizer
        )
    {
        $this->requestStack = $requestStack;
        $this->validator = $validator;
        $this->diameterRepository = $diameterRepository;
        $this->materialRepository = $materialRepository;
        $this->singularityRepository = $singularityRepository;
        $this->serializer = $serializer;
        $this->denormalizer = $denormalizer;
    }

    
    /**
     * Validation and process the optimal duct section request
     *
     * @return array
     */
    public function getOptimalDuctDimension(): array
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if (!$post) {
            return [
                'response' => ['error' => "Invalid json"],
                'httpResponse' => Response::HTTP_BAD_REQUEST
            ];
        }

        $errors = [];
        
        if (array_key_exists('shape', $post)) {
            $shape = $post['shape'];
            if ($shape === 'rectangular') {
                if (array_key_exists('width', $post)) {
                    if (is_int($post['width'])) {
                        $secondsize = $post['width'];
                    } else {
                        $errors['width'][] = 'This value should be an integer.';
                    }
                } else {
                    $errors['width'][] = 'This value should not be blank when shape is rectangular.';
                }
            } elseif ($shape === 'circular') {
                $secondsize = 0;
            } else {
                $errors['shape'][] = "The shape must be 'circular' or 'rectangular'.";
            }
        } else {
            $errors['shape'][] = 'This value should not be blank.';
        }
        
        if (array_key_exists('flowRate', $post)) {
            if (is_int($post['flowRate'])) {
                $flowRate = $post['flowRate'];
            } else {
                $errors['flowRate'][] = 'This value should be an integer.';
            }
        } else {
            $errors['flowRate'][] = 'This value should not be blank.';
        }

        if (count($errors) > 0) {
            return [
                'response' => $errors,
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        if(array_key_exists('flowSpeed', $post)) {
            if (is_float($post['flowSpeed'])) {
                $idealFlowSpeed = $post['flowSpeed'];
            } else {
                $errors['flowSpeed'][] = 'This value should be a float.';
            }
            $optimalDuctDimension = DuctSection::getOptimalDimensions($this->diameterRepository, $shape, $flowRate, $secondsize, $idealFlowSpeed);
        } else {
            $optimalDuctDimension = DuctSection::getOptimalDimensions($this->diameterRepository, $shape, $flowRate, $secondsize);
        }

        return [
            'response' => $optimalDuctDimension,
            'httpResponse' => Response::HTTP_OK
        ];
    }

    /**
     * Validation and process the duct section request
     *
     * @return array
     */
    public function getDuctSection(): array
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if (!$post) {
            return [
                'response' => ['error' => "Invalid json"],
                'httpResponse' => Response::HTTP_BAD_REQUEST
            ];
        }

        $errors = [];
        
        if (array_key_exists('shape', $post)) {
            $shape = $post['shape'];
            if($shape === 'circular') {
                if (array_key_exists('diameter', $post)) {
                    if (is_int($post['diameter'])) {
                        $firstSize = $post['diameter'];
                    } else {
                        $errors['diameter'][] = 'This value should be an integer.';
                    }
                } else {
                    $errors['diameter'][] = 'This value should not be blank.';
                }
                $secondsize = 0;
            } elseif ($shape === 'rectangular') {
                if (array_key_exists('width', $post)) {
                    if (is_int($post['width'])) {
                        $firstSize = $post['width'];
                    } else {
                        $errors['width'][] = 'This value should be an integer.';
                    }
                } else {
                    $errors['width'][] = 'This value should not be blank when shape is rectangular.';
                }
                if (array_key_exists('height', $post)) {
                    if (is_int($post['height'])) {
                        $secondsize = $post['height'];
                    } else {
                        $errors['height'][] = 'This value should be an integer.';
                    }
                } else {
                    $errors['height'][] = 'This value should not be blank when shape is rectangular.';
                }
            } else {
                $errors['shape'][] = "The shape must be 'circular' or 'rectangular'.";
            }
        } else {
            $errors['shape'][] = 'This value should not be blank.';
        }

        if (count($errors) > 0) {
            return [
                'response' => $errors,
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        $section = DuctSection::getSection($shape, $firstSize, $secondsize);

        return [
            'response' => $section,
            'httpResponse' => Response::HTTP_OK
        ];
    }

    public function getFlowSpeed()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if (!$post) {
            return [
                'response' => ['error' => "Invalid json"],
                'httpResponse' => Response::HTTP_BAD_REQUEST
            ];
        }

        $errors = [];

        if (array_key_exists('shape', $post)) {
            $shape = $post['shape'];
            if($shape === 'circular') {
                if (array_key_exists('diameter', $post)) {
                    if (is_int($post['diameter'])) {
                        $firstSize = $post['diameter'];
                    } else {
                        $errors['diameter'][] = 'This value should be an integer.';
                    }
                } else {
                    $errors['diameter'][] = 'This value should not be blank.';
                }
                $secondsize = 0;
            } elseif ($shape === 'rectangular') {
                if (array_key_exists('width', $post)) {
                    if (is_int($post['width'])) {
                        $firstSize = $post['width'];
                    } else {
                        $errors['width'][] = 'This value should be an integer.';
                    }
                } else {
                    $errors['width'][] = 'This value should not be blank when shape is rectangular.';
                }
                if (array_key_exists('height', $post)) {
                    if (is_int($post['height'])) {
                        $secondsize = $post['height'];
                    } else {
                        $errors['height'][] = 'This value should be an integer.';
                    }
                } else {
                    $errors['height'][] = 'This value should not be blank when shape is rectangular.';
                }
            } else {
                $errors['shape'][] = "The shape must be 'circular' or 'rectangular'.";
            }
        } else {
            $errors['shape'][] = 'This value should not be blank.';
        }
        
        if (array_key_exists('flowRate', $post)) {
            if (is_int($post['flowRate'])) {
                $flowRate = $post['flowRate'];
            } else {
                $errors['flowRate'][] = 'This value should be an integer.';
            }
        } else {
            $errors['flowRate'][] = 'This value should not be blank.';
        }

        if (count($errors) > 0) {
            return [
                'response' => $errors,
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        $flowSpeed = DuctSection::getFlowSpeed($flowRate, $shape, $firstSize, $secondsize);

        return [
            'response' => $flowSpeed,
            'httpResponse' => Response::HTTP_OK
        ];
    }

    public function getSection()
    {
        $post = $this->requestStack->getCurrentRequest()->getContent();

        try {
            $ductSection = $this->serializer->deserialize($post, DuctSection::class, 'json');
        } catch (NotEncodableValueException $e) {
            return [
                'response' => ['error' => "Invalid json"],
                'httpResponse' => Response::HTTP_BAD_REQUEST
            ];
        }

        $errors = $this->validator->validate($ductSection);
        
        if(count($errors) > 0) {
            $dataErrors = [];

            foreach($errors as $error) {
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }

            return [
                'response' => $dataErrors,
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        $ductSection->setCalculation();
        
        $section = [
            'ductSection' => $ductSection->section,
            'flowSpeed' => $ductSection->flowSpeed,
            'linearApd' => $ductSection->getLinearApd($this->materialRepository),
            'singularApd' => $ductSection->getSingularApd($this->singularityRepository),
            'totalApd' => $ductSection->getTotalApd($this->materialRepository, $this->singularityRepository)
        ];

        return [
            'response' => $section,
            'httpResponse' => Response::HTTP_OK
        ];
    }

    public function getSections()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        $errors = [];

        $ductNetwork = new DuctNetwork();
        if (array_key_exists('additionalApd', $post)) {
            $ductNetwork->setAdditionalApd($post['additionalApd']);
        }
        if (array_key_exists('temperature', $post)) {
            $ductNetwork->setTemperature($post['temperature']);
        }
        if (array_key_exists('altitude', $post)) {
            $ductNetwork->setAltitude($post['altitude']);
        }
        foreach ($post['ductSections'] as $ductSection) {
            try {
                $ductSection = $this->denormalizer->denormalize($ductSection, DuctSection::class);
            } catch (NotEncodableValueException $e) {
                return [
                'response' => ['error' => "Invalid json"],
                'httpResponse' => Response::HTTP_BAD_REQUEST
                ];
            }
            $errors[] = $this->validator->validate($ductSection);
            $ductNetwork->addDuctSections($ductSection);
        }

        $errors[] = $this->validator->validate($ductNetwork);

        $dataErrors = [];
        foreach ($errors as $error) {
            if(count($error) > 0) {
                foreach($error as $violation) {
                    $dataErrors[$violation->getPropertyPath()][] = $violation->getMessage();
                }
            }
        }
        
        if (count($dataErrors) > 0) {
            return [
                'response' => $dataErrors,
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        $totalLinearApd = 0;
        $totalSingularApd = 0;
        $totalAdditionalApd = 0;
        $generalAdditionalApd = $ductNetwork->getAdditionalApd();

        $ductSections = $ductNetwork->getDuctSections();
        foreach($ductSections as $ductSection) {

            $ductSection->setCalculation();

            $totalLinearApd += $ductSection->getLinearApd($this->materialRepository);
            $totalSingularApd += $ductSection->getSingularApd($this->singularityRepository);
            $totalAdditionalApd += $ductSection->getAdditionalApd();
        }

        $totalSingularApd = round($totalSingularApd, 3);
        $totalApd = $totalLinearApd + $totalSingularApd + $totalAdditionalApd + $generalAdditionalApd;

        $sections = [
            'totalLinearApd' => $totalLinearApd,
            'totalSingularApd' => $totalSingularApd,
            'totalAdditionalApd' => $totalAdditionalApd,
            'generalAdditionalApd' => $generalAdditionalApd,
            'totalApd' => $totalApd
        ];

        return [
            'response' => $sections,
            'httpResponse' => Response::HTTP_OK
        ];
    }
}