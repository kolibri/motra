<?php declare(strict_types = 1);

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    public function testSavingAndSpendingMoney()
    {
        $this->client = static::createClient();
        $this->client->followRedirects(false);

        $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isRedirection());
        $this->client->followRedirect();

        $this->createAccount('bar');
        $this->assertTotalAmount(0);
        $this->doTransaction(100, 'income', 'save', '1');
        $this->assertTotalAmount(100);
        $this->doTransaction(2.5, 'something', 'spend', '1');
        $this->assertTotalAmount(97.5);
    }

    private function assertTotalAmount(float $amount)
    {
        $crawler = $this->client->getCrawler();
        $amountTag = $crawler->filter('.total_amount');
        $this->assertCount(1, $amountTag);
        $this->assertEquals(number_format($amount, 2, ',', '.'), trim($amountTag->text()));
    }

    private function doTransaction(float $amount, string $title, string $type, string $account)
    {
        $crawler = $this->client->getCrawler();
        $formTag = $crawler->filter('form[name=transaction]');

        $this->assertCount(1, $formTag);

        $form = $formTag->selectButton('transaction.form.button')->form();
        $this->client->submit(
            $form,
            [
                'transaction[amount]' => number_format($amount, 2, ',', '.'),
                'transaction[title]' => $title,
                'transaction[type]' => $type,
                'transaction[account]' => $account,
            ]
        );

        $this->assertTrue($this->client->getResponse()->isRedirection());

        return $this->client->followRedirect();
    }

    private function createAccount(string $name)
    {
        $crawler = $this->client->getCrawler();
        $formTag = $crawler->filter('form[name=account]');

        $this->assertCount(1, $formTag);

        $form = $formTag->selectButton('Create Account')->form();
        $this->client->submit($form, ['account[name]' => $name]);

        $this->assertTrue($this->client->getResponse()->isRedirection());

        return $this->client->followRedirect();
    }
}


