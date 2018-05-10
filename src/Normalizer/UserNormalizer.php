<?php declare(strict_types = 1);


namespace App\Normalizer;

use App\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param User $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'email' => $object->getUsername(),
            '_view' => $this->router->generate('api_user_view', ['id' => $object->getId()]),
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return get_class($data) === User::class && in_array($format, ['json'], true);
    }
}
