<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

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
    /** @ORM\ManyToOne(targetEntity="Account") */
    private $account;
    /** @ORM\Column() */
    private $title;
    /** @ORM\Column() */
    private $type;

    /** @ORM\Column(type="integer") */
    private $amount;

    public function __construct(string $title, int $amount, string $type, Account $account)
    {
        $this->title = $title;
        $this->amount = $amount;
        $this->type = $type;
        $this->account = $account;
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
}