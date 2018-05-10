<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(User $user, bool $flush = true)
    {
        $this->entityManager->persist($user);
        $flush && $this->entityManager->flush();
    }
}