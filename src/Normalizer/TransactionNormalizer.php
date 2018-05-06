<?php declare(strict_types = 1);


namespace App\Normalizer;

use App\Entity\Transaction;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TransactionNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return new Transaction($data['title'],$data['amount'], $data['type'],$data['account']);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return Transaction::class === $type;
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
            'account' => $object->getAccount(),
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return in_array($format, ['json'], true);
    }
}