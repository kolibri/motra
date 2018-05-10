<?php declare(strict_types = 1);

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;

class DatabaseCleaner
{
    private $manager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->manager = $entityManager;
    }
    public function clean()
    {
        $metadatas = $this->manager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadatas)) {
            $tool = new \Doctrine\ORM\Tools\SchemaTool($this->manager);
            $tool->dropSchema($metadatas);
            $tool->createSchema($metadatas);
        }
    }
}