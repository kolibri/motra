<?php declare(strict_types = 1);

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $username;
    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     */
    private $password;
    /** @ORM\Column(type="datetime_immutable") */
    private $createdAt;

    public function __construct(string $email, string $password)
    {
        $this->username = $email;
        $this->password = $password;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
