<?php declare(strict_types = 1);

namespace Tests\App\Controller;

use App\Test\FunctionalTestCase;
use Symfony\Component\BrowserKit\Client;

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
        $this->assertAccountList([['name' => 'bar', 'amount' => 0]], $client);
        $this->assertAddTransaction(100, 'income', 'save', 1, 'bar', $client);
        $this->assertAccountList([['name' => 'bar', 'amount' => 10000]], $client);
        $this->assertAddTransaction(2.5, 'something', 'spend', 1, 'bar', $client);
        $this->assertAccountList([['name' => 'bar', 'amount' => 9750]], $client);
    }

    private function assertAccountList(array $expected, Client $client)
    {
        $client->request('GET', '/api/v1/account/list');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertJsonContent($expected, $client);
    }

    private function assertAddAccount(string $name, Client $client)
    {
        $client->request('POST', '/api/v1/account/add', ['account' => ['name' => $name]]);
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent(['name' => $name, 'amount' => 0], $client);
    }

    private function assertAddTransaction(
        float $amount,
        string $title,
        string $type,
        int $accountId,
        string $accountName,
        Client $client
    ) {
        $client->request(
            'POST',
            '/api/v1/transaction/add',
            [
                'transaction' => [
                    'amount' => $amount,
                    'title' => $title,
                    'type' => $type,
                    'account' => $accountId,
                ],
            ]
        );
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();
        $this->assertJsonContent(
            [
                'amount' => (int)($amount * 100),
                'title' => $title,
                'type' => $type,
                'account' => $accountName,
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