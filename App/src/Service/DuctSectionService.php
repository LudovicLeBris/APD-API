<?php

namespace App\Service;

use App\Utils\Data;
use App\Entity\DuctNetwork;
use App\Entity\DuctSection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

class DuctSectionService
{
    /**
     * RequestStack service
     *
     * @var RequestStack
     */
    private RequestStack $requestStack;

    private SerializerInterface $serializer;

    private DenormalizerInterface $denormalizer;

    private ValidatorInterface $validator;

    public function __construct(
        RequestStack $requestStack, 
        SerializerInterface $serializer, 
        DenormalizerInterface $denormalizer, 
        ValidatorInterface $validator
        )
    {
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    /**
     * Validation and process the optimal duct dimension request
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

        if(array_key_exists('flowSpeed', $post)) {
            if (is_float($post['flowSpeed'])) {
                $idealFlowSpeed = $post['flowSpeed'];
            } else {
                $errors['flowSpeed'][] = 'This value should be a float.';
            }
            $optimalDuctDimension = self::calculateOptimalDuctDimension($shape, $flowRate, $secondsize, $idealFlowSpeed);
        } else {
            $optimalDuctDimension = self::calculateOptimalDuctDimension($shape, $flowRate, $secondsize);
        }

        // TODO add flow speed calculation to response

        if (count($errors) > 0) {
            return [
                'response' => $errors,
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }
        
        return [
            'response' => $optimalDuctDimension,
            'httpResponse' => Response::HTTP_OK
        ];

    }

    /**
     * Calcultate the optimal duct dimension request
     *
     * @param string $shape
     * @param integer $flowRate
     * @param integer $secondSize
     * @param integer $idealFlowSpeed
     * @return integer|null
     */
    public static function calculateOptimalDuctDimension(string $shape, int $flowRate, int $secondSize = 0, float $idealFlowSpeed = 7): ?int
    {
        $optimalSection = ($flowRate / 3600) / $idealFlowSpeed;

        if ($shape === 'circular') {
            $optimalDiameter = sqrt(($optimalSection * 4) / pi()) * 1000;
            $optimalDimension = Data::getUpperDiameter($optimalDiameter);
        } elseif ($shape === 'rectangular') {
            $optimalDimension = round(($optimalSection / ($secondSize / 1000)) * 1000);
        } else {
            return null;
        }

        return $optimalDimension;
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
                $secondSize = 0;
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
                        $secondSize = $post['height'];
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

        $section = self::calculateDuctSection($shape, $firstSize, $secondSize);

        return [
            'response' => $section,
            'httpResponse' => Response::HTTP_OK
        ];
    }

    /**
     * Calculate the duct section request
     *
     * @param string $shape
     * @param integer $firstSize
     * @param integer $secondSize
     * @return float|null
     */
    public static function calculateDuctSection(string $shape, int $firstSize, int $secondSize = 0): ?float
    {
        if($shape === 'circular'){
            $equivDiameter = $firstSize;
        } elseif ($shape === 'rectangular'){
            $equivDiameter = (1.265 * ($firstSize * $secondSize) ** 0.6) 
                / ($firstSize + $secondSize) ** 0.2;
        } else {
            return null;
        }

        return round((pi() * ($equivDiameter/1000)**2)/4, 3);
    }

    /**
     * Validation and process the flow speed request
     *
     * @return void
     */
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

        $flowSpeed = self::calculateFlowSpeed($flowRate, $shape, $firstSize, $secondsize);

        return [
            'response' => $flowSpeed,
            'httpResponse' => Response::HTTP_OK
        ];
    }

    /**
     * Calculate the flow speed request
     *
     * @param integer $flowRate
     * @param string $shape
     * @param integer $firstSize
     * @param integer $secondSize
     * @return float
     */
    public static function calculateFlowSpeed(int $flowRate, string $shape, int $firstSize, int $secondSize = 0): float
    {
        $section = self::calculateDuctSection($shape, $firstSize, $secondSize);

        return round(($flowRate / 3600) / $section, 3);
    }

    /**
     * Validation and process the duct section request
     *
     * @return void
     */
    public function getSection()
    {
        $post = $this->requestStack->getCurrentRequest()->getContent();

        try {
            $ductSection = $this->serializer->deserialize($post, DuctSection::class, 'json', ['groups' => 'request']);
        } catch (NotEncodableValueException $e) {
            return [
                'response' => ['error' => "Invalid json"],
                'httpResponse' => Response::HTTP_BAD_REQUEST
            ];
        } catch (NotNormalizableValueException $e) {
            $path = $e->getPath();
            $currentType = $e->getCurrentType();
            $expectedTypes = $e->getExpectedTypes()[0];
            return [
                'response' => [$path => ['The type of attribute must be '. $expectedTypes]],
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
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

        $ductSection->calculate();

        $section = [
            'ductSection' => $ductSection->getSection(),
            'flowSpeed' => $ductSection->getFlowSpeed(),
            'linearApd' => $ductSection->getLinearApd(),
            'singularApd' => $ductSection->getSingularApd(),
            'totalApd' => $ductSection->getTotalApd()
        ];

        return [
            'response' => $section,
            'httpResponse' => Response::HTTP_OK
        ];
    }

     /**
     * Validation and process the duct network request
     *
     * @return void
     */
    public function getNetwork()
    {
        $post = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        $errors = [];

        $ductNetwork = new DuctNetwork();

        if (array_key_exists('additionalApd', $post)) {
            if (is_int($post['additionalApd']) && $post['additionalApd'] >= 0) {
                $ductNetwork->setAdditionalApd($post['additionalApd']);
            } else {
                $errors['additionalApd'][] = 'This value should be a positive integer.'; 
            }
        }
        if (array_key_exists('temperature', $post)) {
            if (is_int($post['temperature'])) {
                $ductNetwork->setTemperature($post['temperature']);
            } else {
                $errors['temperature'][] = "This value should be an integer.";
            }
        }
        if (array_key_exists('altitude', $post)) {
            if (is_int($post['altitude']) && $post['altitude'] >= 0) {
                $ductNetwork->setAltitude($post['altitude']);
            } else {
                $errors['altitude'][] = "This value should be a positive integer.";
            }
        }
        foreach ($post['ductSections'] as $ductSection) {
            try {
                $ductSection = $this->denormalizer->denormalize($ductSection, DuctSection::class, 'json', ['groups' => 'request']);
            } catch (NotEncodableValueException $e) {
                return [
                    'response' => ['error' => "Invalid json"],
                    'httpResponse' => Response::HTTP_BAD_REQUEST
                ];
            } catch (NotNormalizableValueException $e) {
                $path = $e->getPath();
                $expectedTypes = $e->getExpectedTypes()[0];
                $errors['ductSection'][$path][] = "The type of attribute must be ". $expectedTypes .".";
            }
            $ductSectionErrors = $this->validator->validate($ductSection);
            if (count($ductSectionErrors) > 0) {
                foreach ($ductSectionErrors as $ductSectionError) {
                    $path = $ductSectionError->getPropertyPath();
                    $message = $ductSectionError->getMessage();
                    $errors['ductSection'][$path] = $message;
                }
            } else {
                $ductNetwork->addDuctSection($ductSection);
            }
        }

        if (count($errors) > 0) {
            return [
                'response' => $errors,
                'httpResponse' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        $ductNetwork->calculate();

        $network = [
            'totalLinearApd' => $ductNetwork->getTotalLinearApd(),
            'totalSingularApd' => $ductNetwork->getTotalSingularApd(),
            'totalAdditionalApd' => $ductNetwork->getTotalAdditionalApd(),
            'generalAdditionalApd' => $ductNetwork->getTotalAllAdditionalApd(),
            'totalApd' => $ductNetwork->getTotalApd()
        ];

        return [
            'response' => $network,
            'httpResponse' => Response::HTTP_OK
        ];
    }
}