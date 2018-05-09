<?php declare(strict_types = 1);

namespace App\Normalizer;

use App\Entity\Transaction;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TransactionNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Transaction $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'title' => $object->getTitle(),
            'amount' => $object->getAmount(),
            'type' => $object->getType(),
            'account' => $this->normalizer->normalize($object->getAccount(), $format),
            'created_at' => $object->getCreatedAt()->format(\DateTime::ISO8601),
            '_view' => $this->router->generate('api_transaction_view', ['id' => $object->getId()]),
            '_delete' => $this->router->generate('api_transaction_delete', ['id' => $object->getId()]),
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return get_class($data) === Transaction::class && in_array($format, ['json'], true);
    }
}
