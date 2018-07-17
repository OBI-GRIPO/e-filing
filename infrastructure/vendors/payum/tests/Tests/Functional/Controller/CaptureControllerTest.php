<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Test\WebTestCase;
use App\Util\UUID;
use Makasim\Yadm\Storage;
use App\Model\GatewayConfig;
use App\Model\Payment;
use App\Test\ResponseHelper;

class CaptureControllerTest extends WebTestCase
{
    use ResponseHelper;

    public function testShouldAllowChooseGateway()
    {
        $gatewayConfigStorage = $this->getGatewayConfigStorage();

        /** @var GatewayConfig $gatewayConfig */
        $gatewayConfig = $gatewayConfigStorage->create();
        $gatewayConfig->setFactoryName('offline');
        $gatewayConfig->setGatewayName('FooGateway');
        $gatewayConfig->setConfig(['factory' => 'offline']);
        $gatewayConfigStorage->insert($gatewayConfig);

        /** @var GatewayConfig $gatewayConfig */
        $gatewayConfig = $gatewayConfigStorage->create();
        $gatewayConfig->setFactoryName('offline');
        $gatewayConfig->setGatewayName('BarGateway');
        $gatewayConfig->setConfig(['factory' => 'offline']);
        $gatewayConfigStorage->insert($gatewayConfig);

        /** @var Storage $storage */
        $storage = $this->getPaymentStorage();

        /** @var Payment $payment */
        $payment = $storage->create();
        $payment->setGatewayName(null);
        $payment->setId(UUID::generate());

        $storage->insert($payment);

        $token = $this->getPayum()
            ->getTokenFactory()
            ->createCaptureToken('', $payment, getenv('PAYUM_HTTP_HOST'));

        $crawler = $this->getClient()->request('GET', $token->getTargetUrl());

        $this->assertClientResponseStatus(200);
        $this->assertClientResponseContentHtml();

        $this->assertGreaterThan(0, count($crawler->filter('.payum-choose-gateway')));
        $this->assertContains('Foo Gateway', $crawler->text());
        $this->assertContains('Bar Gateway', $crawler->text());

        $form = $crawler->filter('form')->form();
        $form['gatewayName'] = 'BarGateway';

        $crawler = $this->getClient()->submit($form);

        $this->assertClientResponseStatus(302);
        $this->assertClientResponseRedirectionStartsWith(getenv('PAYUM_HTTP_HOST') . '?payum_token=');
    }

    public function testShouldObtainMissingDetails()
    {
        $gatewayConfigStorage = $this->getGatewayConfigStorage();

        /** @var GatewayConfig $gatewayConfig */
        $gatewayConfig = $gatewayConfigStorage->create();
        $gatewayConfig->setFactoryName('be2bill_offsite');
        $gatewayConfig->setGatewayName('be2bill');
        $gatewayConfig->setConfig([
            'factory' => 'be2bill_offsite',
            'identifier' => 'foo',
            'password' => 'bar',
            'sandbox' => true,
        ]);
        $gatewayConfigStorage->insert($gatewayConfig);

        /** @var Storage $storage */
        $storage = $this->getPaymentStorage();

        /** @var Payment $payment */
        $payment = $storage->create();
        $payment->setGatewayName('be2bill');
        $payment->setId(UUID::generate());

        $storage->insert($payment);

        $token = $this->getPayum()
            ->getTokenFactory()
            ->createCaptureToken('be2bill', $payment, getenv('PAYUM_HTTP_HOST') . '');

        $crawler = $this->getClient()->request('GET', $token->getTargetUrl());

        $this->assertClientResponseStatus(200);
        $this->assertClientResponseContentHtml();

        $this->assertGreaterThan(0, count($crawler->filter('.payum-obtain-missing-details')));

        $form = $crawler->filter('form')->form();
        $form['payer[email]'] = 'foo@example.com';

        $crawler = $this->getClient()->submit($form);

        $this->assertClientResponseStatus(200);
        $this->assertClientResponseContentHtml();

        $this->assertContains('Redirecting to payment page...', $crawler->text());
    }
}
