<?php declare(strict_types=1);

namespace App\Tests\SuperMetrics;

use App\SuperMetrics\Client;
use App\SuperMetrics\SerializerBuilder;
use App\SuperMetrics\TokenRequest;
use App\SuperMetrics\TokenResponse;
use App\SuperMetrics\TokenResponseData;
use App\Tests\AbstractTest;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ClientTest extends AbstractTest
{
    private array $httpTransactions;

    public function testRegister(): void
    {
        $httpClient = $this->getHttpClient('register_response.json');
        $client = $this->getApiClient($httpClient);

        $request = new TokenRequest('my-id-123456', 'my.email@address.com', 'Nameless');
        $expectedResponse = new TokenResponse(
            new TokenResponseData('my-id-123456', 'my.email@address.com', 'token-371110')
        );

        self::assertEquals($expectedResponse, $client->register($request));
        $this->assertRequest('register_request.json');
    }

    private function getApiClient(HttpClient $httpClient): Client
    {
        return new Client(
            SerializerBuilder::build(),
            $httpClient,
            'http://api.host/'
        );
    }

    private function getHttpClient(string $responseFilepath): HttpClient
    {
        $this->httpTransactions = [];
        $history = Middleware::history($this->httpTransactions);

        $mock = new MockHandler([
            new Response(200, [], $this->getResourceContent($responseFilepath)),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        return new HttpClient(['handler' => $handlerStack]);
    }

    private function assertRequest(string $filepath): void
    {
        self::assertCount(1, $this->httpTransactions);
        /** @var Request $request */
        $request = reset($this->httpTransactions)['request'];
        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('http://api.host/register', (string) $request->getUri());
        self::assertJsonStringEqualsJsonString(
            $this->getResourceContent($filepath),
            $request->getBody()->getContents()
        );
    }
}
