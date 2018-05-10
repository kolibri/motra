<?php declare(strict_types = 1);

namespace Tests\App\Controller;

use App\DataFixtures\DatabaseCleaner;
//use App\DataFixtures\FixtureLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    public function testTheApi()
    {
        $client = static::createClient();
//        /** @var FixtureLoader $loader */
//        $loader = $client->getContainer()->get(FixtureLoader::class);
        /** @var DatabaseCleaner $cleaner */
        $cleaner = $client->getContainer()->get(DatabaseCleaner::class);

        $cleaner->clean();

        $client->followRedirects(false);

        $this->assertAddUser($client, 'torbentester', 'torben@tester.dev', 'test');

        /*
        $this->assertAccountList($client, []);
        $this->assertAddAccount($client, 'bar');
        $this->assertAddAccount($client, 'foo');
        $this->assertAddAccount($client, 'baz');
        $this->assertAddAccount($client, 'fuu');
        $this->assertAccountList($client, [['name' => 'bar', 'total' => 0]]);
        $this->assertAddTransaction($client, 100, 'income', 'save', 1);
        $this->assertAccountList($client, [['name' => 'bar', 'total' => 10000]]);
        $this->assertAddTransaction($client, 2.5, 'something', 'spend', 1);
        $this->assertAccountList($client, [['name' => 'bar', 'total' => 9750]]);
        */
    }

    private function assertAccountList(Client $client, array $expected)
    {
        $client->request('GET', '/api/v1/account');
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent($client, ['accounts' => $expected]);
    }

    private function assertAddAccount(Client $client, string $name)
    {
        $client->request('POST', '/api/v1/account/', [], [], [], json_encode(['name' => $name]));
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent($client, ['account' => ['name' => $name, 'total' => 0], 'transactions' => []]);
    }

    private function assertAddUser(Client $client, string $username, string $email, string $password)
    {
        $client->request(
            'POST',
            '/api/v1/user/',
            [],
            [],
            [],
            json_encode(
                [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                ]
            )
        );
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent($client, ['account' => ['name' => $name, 'total' => 0], 'transactions' => []]);
    }

    private function assertAddTransaction(
        Client $client,
        float $amount,
        string $title,
        string $type,
        int $accountId
    ) {
        $client->request(
            'POST',
            '/api/v1/transaction/',
            [],
            [],
            [],
            json_encode(
                [
                    'amount' => $amount,
                    'title' => $title,
                    'type' => $type,
                    'account' => $accountId,
                ]
            )
        );
        /** @var Response $response */
        $response = $client->getResponse();
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent(
            $client,
            [
                'amount' => (int)($amount * 100),
                'title' => $title,
                'type' => $type,
                'account' => ['id' => $accountId],
            ]
        );
    }

    private function assertJsonContent(Client $actualClient, array $expected)
    {
        $jsonContent = json_decode($actualClient->getResponse()->getContent(), true);
        $this->assertArraySubset($expected, $jsonContent);
    }
}