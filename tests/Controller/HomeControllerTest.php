<?php declare(strict_types = 1);

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class HomeControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    public function testSavingAndSpendingMoney()
    {
        $this->client = static::createClient();
        $this->client->followRedirects(false);

        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertTotalAmount(0);
        $this->save(100);
        $this->assertTotalAmount(100);
        $this->spend(2.5);
        $this->assertTotalAmount(97.5);
    }

    private function assertTotalAmount(float $amount)
    {
        $crawler = $this->client->getCrawler();
        $amountTag = $crawler->filter('#total_amount');
        $this->assertCount(1, $amountTag);
        $this->assertEquals(number_format($amount, 2, ',', '.'), trim($amountTag->text()));
    }

    private function save(float $amount)
    {
        $crawler = $this->client->getCrawler();
        $formTag = $crawler->filter('form[name=save_money]');

        $this->assertCount(1, $formTag);

        $form = $formTag->selectButton('Save')->form();
        $this->client->submit($form, ['save_money[amount]' => number_format($amount, 2, ',', '.')]);

        $this->assertTrue($this->client->getResponse()->isRedirection());

        return $this->client->followRedirect();
    }

    private function spend(float $amount)
    {
        $crawler = $this->client->getCrawler();
        $formTag = $crawler->filter('form[name=spend_money]');

        $this->assertCount(1, $formTag);

        $form = $formTag->selectButton('Spend')->form();
        $this->client->submit($form, ['spend_money[amount]' => number_format($amount, 2, ',', '.')]);

        $this->assertTrue($this->client->getResponse()->isRedirection());

        return $this->client->followRedirect();
    }
}


