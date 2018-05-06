<?php declare(strict_types = 1);

namespace App\Test;

use App\Kernel;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DependencyInjection\Container;

abstract class FunctionalTestCase extends TestCase
{
    /** @var Kernel */
    protected $kernel;

    /** @var EntityManager */
    protected $entityManager;

    /** @var Container */
    protected $container;

    public function setUp()
    {
        $this->kernel = new Kernel('test', true);
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();

        $this->generateSchema();

        parent::setUp();
    }

    public function tearDown()
    {
        $this->kernel->shutdown();

        parent::tearDown();
    }

    protected function generateSchema()
    {
        $metadatas = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadatas)) {
            $tool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
            $tool->dropSchema($metadatas);
            $tool->createSchema($metadatas);
        }
    }

    protected function getClient(): Client
    {
        return $this->container->get('test.client');
    }
}