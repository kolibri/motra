<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/** @ORM\Entity() */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;
    /** @ORM\Column() */
    private $title;
    /** @ORM\Column() */
    private $type;
    /** @ORM\Column(type="integer") */
    private $amount;

    public function __construct(string $title, int $total, string $type)
    {
        $this->title = $title;
        $this->amount = $total;
        $this->type = $type;
    }

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getType(): string
    {
        return $this->type;
    }
}