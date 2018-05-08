<?php declare(strict_types = 1);

namespace Tests\App\Controller;

use App\Test\FunctionalTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends FunctionalTestCase
{
    public function testTheApi()
    {
        $client = $this->getClient();
        $client->followRedirects(false);

        $this->assertAccountList([], $client);
        $this->assertAddAccount('bar', $client);
        $this->assertAddAccount('foo', $client);
        $this->assertAddAccount('baz', $client);
        $this->assertAddAccount('fuu', $client);
        $this->assertAccountList([['name' => 'bar', 'total' => 0]], $client);
        $this->assertAddTransaction(100, 'income', 'save', 1, $client);
        $this->assertAccountList([['name' => 'bar', 'total' => 10000]], $client);
        $this->assertAddTransaction(2.5, 'something', 'spend', 1, $client);
        $this->assertAccountList([['name' => 'bar', 'total' => 9750]], $client);
    }

    private function assertAccountList(array $expected, Client $client)
    {
        $client->request('GET', '/api/v1/account');
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent(['accounts' => $expected], $client);
    }

    private function assertAddAccount(string $name, Client $client)
    {
        $client->request('POST', '/api/v1/account/', [], [], [], json_encode(['name' => $name]));
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent(['account' => ['name' => $name, 'total' => 0], 'transactions' => []], $client);
    }

    private function assertAddTransaction(
        float $amount,
        string $title,
        string $type,
        int $accountId,
        Client $client
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
        dump($response->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent(
            [
                'amount' => (int)($amount * 100),
                'title' => $title,
                'type' => $type,
                'account' => $accountId,
            ],
            $client
        );
    }

    private function assertJsonContent(array $expected, Client $actualClient)
    {
        $jsonContent = json_decode($actualClient->getResponse()->getContent(), true);
        $this->assertArraySubset($expected, $jsonContent);
    }
}