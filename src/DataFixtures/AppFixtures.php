<?php declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(EncoderFactoryInterface $encoders)
    {
        $this->encoder = $encoders->getEncoder(User::class);
    }

    public function load(ObjectManager $manager)
    {
        $accounts = [];
        $accounts['bar'] = new Account('Bar');
        $accounts['bank'] = new Account('Bank');

        $transactions = [
            new Transaction('income', 100000, Transaction::TYPE_SAVE, $accounts['bar']),
            new Transaction('lunch', 750, Transaction::TYPE_SPEND, $accounts['bar']),
        ];

        $users = [
            new User('torben@tester.foo', $this->encoder->encodePassword('tester', null)),
            new User('tamara@tester.foo', $this->encoder->encodePassword('tester', null)),
        ];

        foreach (array_merge($accounts, $transactions, $users) as $item) {
            $manager->persist($item);
        }
        
        $manager->flush();
    }
}