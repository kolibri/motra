<?php declare(strict_types = 1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;

class FixtureLoader
{
    private $fixturesLoader;
    private $manager;

    public function __construct(SymfonyFixturesLoader $fixturesLoader, ObjectManager $manager)
    {
        $this->fixturesLoader = $fixturesLoader;
        $this->manager = $manager;
    }

    public function load(array $fixtures = [])
    {
        $purger = new ORMPurger($this->manager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($this->manager, $purger);
        $executor->execute($fixtures);
    }

    public function loadDefault()
    {
        $fixtures = $this->fixturesLoader->getFixtures();
        if (!$fixtures) {
            throw new \InvalidArgumentException(
                'Could not find any fixture services to load.'
            );
        }
        $this->load($fixtures);
    }
}
