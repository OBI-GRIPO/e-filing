<?php
declare(strict_types=1);

namespace App\Test;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

trait ResponseHelper
{
    /**
     * @return Client | \App\Test\Client
     */
    abstract public function getClient() : Client;

    public function assertResponseStatusSuccess(Response $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertGreaterThanOrEqual(200, $response->getStatusCode(), $this->getMessage($response));
        $this->assertLessThan(300, $response->getStatusCode(), $this->getMessage($response));
    }

    public function assertClientResponseStatusSuccess()
    {
        $this->assertResponseStatusSuccess($this->getClient()->getResponse());
    }

    public function assertResponseStatusRedirection(Response $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertGreaterThanOrEqual(300, $response->getStatusCode(), $this->getMessage($response));
        $this->assertLessThan(400, $response->getStatusCode(), $this->getMessage($response));
    }

    public function assertClientResponseStatusClientError()
    {
        $this->assertResponseStatusClientError($this->getClient()->getResponse());
    }

    public function assertResponseStatusClientError(Response $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertGreaterThanOrEqual(400, $response->getStatusCode(), $this->getMessage($response));
        $this->assertLessThan(500, $response->getStatusCode(), $this->getMessage($response));
    }

    public function assertClientResponseStatusRedirection()
    {
        $this->assertResponseStatusRedirection($this->getClient()->getResponse());
    }

    public function assertResponseRedirection(Response $response, $expectedUrl)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseStatusRedirection($response);
        $this->assertEquals($expectedUrl, $response->headers->get('Location'));
    }

    public function assertResponseRedirectionStartsWith(Response $response, $expectedUrl)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseStatusRedirection($response);
        $this->assertStringStartsWith($expectedUrl, urldecode($response->headers->get('Location')));
    }

    public function assertClientResponseRedirectionStartsWith($expectedUrl)
    {
        $this->assertResponseRedirectionStartsWith($this->getClient()->getResponse(), $expectedUrl);
    }

    public function assertClientResponseRedirection($expectedUrl)
    {
        $this->assertResponseRedirection($this->getClient()->getResponse(), $expectedUrl);
    }

    private function getMessage(Response $response)
    {
        if (500 >= $response->getStatusCode() && $response->getStatusCode() < 600) {
            $crawler = new Crawler();
            $crawler->addHtmlContent($response->getContent());

            if ($crawler->filter('.text-exception h1')->count() > 0) {
                $exceptionMessage = trim($crawler->filter('.text-exception h1')->text());

                $trace = '';
                if ($crawler->filter('#traces-0 li')->count() > 0) {
                    list($trace) = explode("\n", trim($crawler->filter('#traces-0 li')->text()));
                }

                return $message = 'Internal Server Error: ' . $exceptionMessage . ' ' . $trace;
            }
        }

        return $response->getContent();
    }

    public function assertClientResponseStatus($expectedStatus)
    {
        $this->assertResponseStatus($this->getClient()->getResponse(), $expectedStatus);
    }

    public function assertResponseContentHtml(Response $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('text/html; charset=UTF-8', $response->headers->get('Content-Type'), $this->getMessage($response));
    }

    public function assertClientResponseContentHtml()
    {
        $this->assertResponseContentHtml($this->getClient()->getResponse());
    }

    public function assertResponseStatus(Response $response, $expectedStatus)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedStatus, $response->getStatusCode(), $this->getMessage($response));
    }

    public function assertClientResponseContentJson()
    {
        $this->assertResponseContentJson($this->getClient()->getResponse());
    }

    public function assertResponseContentJson(Response $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertNotNull(
            $this->getResponseJsonContent($response),
            "Failed to decode content. The content is not valid json: \n\n" . $response->getContent()
        );
    }

    public function assertClientResponseContentJsonSchema()
    {
        $this->assertResponseContentJsonSchema($this->getClient()->getResponse());
    }

    public function assertResponseContentJsonSchema(Response $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('application/schema+json', $response->headers->get('Content-Type'));
        $this->assertNotNull(
            $this->getResponseJsonContent($response),
            "Failed to decode content. The content is not valid json: \n\n" . $response->getContent()
        );
    }

    public function getResponseJsonContent(Response $response, $assoc = false)
    {
        $this->assertInstanceOf(Response::class, $response);

        return json_decode($response->getContent(), $assoc);
    }

    /**
     * @return array | \object
     */
    public function getClientResponseJsonContent($assoc = false)
    {
        return $this->getResponseJsonContent($this->getClient()->getResponse(), $assoc);
    }

    public function assertClientContentHasElement($cssSelector)
    {
        $errorMessage = sprintf(
            'Failed asserting that content has element with selector: %s',
            $cssSelector
        );

        $this->assertGreaterThan(
            0,
            count($this->getClient()->getCrawler()->filter($cssSelector)),
            $errorMessage
        );
    }

    public function assertClientContentHasNotElement($cssSelector)
    {
        $errorMessage = sprintf(
            'Failed asserting that content has not element with selector: %s',
            $cssSelector
        );

        $this->assertEquals(
            0,
            count($this->getClient()->getCrawler()->filter($cssSelector)),
            $errorMessage
        );
    }
}
