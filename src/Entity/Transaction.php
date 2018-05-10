<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\Entity() */
class Transaction
{
    const TYPE_SAVE = 'save';
    const TYPE_SPEND = 'spend';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Account")
     * @Assert\Valid()
     */
    private $account;
    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     */
    private $title;
    /**
     * @ORM\Column()
     * @Assert\Choice({"save", "spend"})
     */
    private $type;
    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     */
    private $amount;
    /** @ORM\Column(type="datetime_immutable") */
    private $createdAt;

    public function __construct(string $title, int $amount, string $type, Account $account)
    {
        $this->title = $title;
        $this->amount = $amount;
        $this->type = $type;
        $this->account = $account;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
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

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}