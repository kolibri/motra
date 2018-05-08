<?php declare(strict_types = 1);

namespace App\Normalizer;

use App\Account\AmountCalculator;
use App\Entity\Account;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AccountNormalizer implements NormalizerInterface
{
    private $router;
    private $calculator;

    public function __construct(RouterInterface $router, AmountCalculator $calculator)
    {
        $this->router = $router;
        $this->calculator = $calculator;
    }

    /**
     * @param Account $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'total' => $this->calculator->calculateTotal($object),
            '_view' => $this->router->generate('api_account_view', ['id' => $object->getId()]),
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return get_class($data) === Account::class && in_array($format, ['json'], true);
    }
}
