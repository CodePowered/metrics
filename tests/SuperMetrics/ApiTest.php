<?php declare(strict_types=1);

namespace App\Tests\SuperMetrics;

use App\Provider\Post;
use App\SuperMetrics\Api;
use App\SuperMetrics\GetPostsRequest;
use App\SuperMetrics\GetPostsResponse;
use App\SuperMetrics\GetPostsResponseData;
use App\SuperMetrics\SerializerBuilder;
use App\SuperMetrics\TokenRequest;
use App\SuperMetrics\TokenResponse;
use App\SuperMetrics\TokenResponseData;
use App\Tests\AbstractTest;
use DateTimeImmutable;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ApiTest extends AbstractTest
{
    private array $httpTransactions;

    public function testRegister(): void
    {
        $httpClient = $this->getHttpClient('register_response.json');
        $api = $this->getApiClient($httpClient);

        $request = new TokenRequest('my-id-123456', 'my.email@address.com', 'Nameless');
        $expectedResponse = new TokenResponse(
            new TokenResponseData('my-id-123456', 'my.email@address.com', 'token-371110')
        );

        self::assertEquals($expectedResponse, $api->register($request));
        $this->assertPostRequest('http://api.host/register', 'register_request.json');
    }

    public function testGetPosts(): void
    {
        $httpClient = $this->getHttpClient('posts_response_mini.json');
        $api = $this->getApiClient($httpClient);

        $request = new GetPostsRequest('token-371110', 5);
        $expectedResponse = new GetPostsResponse(
            new GetPostsResponseData([
                new Post(
                    "Britany Heise",
                    "user_4",
                    "climb pattern traction prediction keep spend",
                    new DateTimeImmutable("2021-03-07T03:05:03+00:00"),
                ),
                new Post(
                    "Mandie Nagao",
                    "user_17",
                    "funeral snack west threshold gain highway",
                    new DateTimeImmutable("2021-03-06T21:59:45+00:00"),
                ),
                new Post(
                    "Carly Alvarez",
                    "user_6",
                    "knock integration dirty ecstasy threshold occasion",
                    new DateTimeImmutable("2021-03-06T18:44:57+00:00"),
                ),
            ])
        );

        self::assertEquals($expectedResponse, $api->getPosts($request));
        $this->assertGetRequest('http://api.host/posts?sl_token=token-371110&page=5');
    }

    public function testPageCount(): void
    {
        $api = $this->getApiClient($this->createMock(HttpClient::class));
        self::assertSame(10, $api->getPageCount());
    }

    private function getApiClient(HttpClient $httpClient): Api
    {
        return new Api(
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

    private function assertGetRequest(string $uri): void
    {
        self::assertCount(1, $this->httpTransactions);
        /** @var Request $request */
        $request = reset($this->httpTransactions)['request'];
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals($uri, (string) $request->getUri());
    }

    private function assertPostRequest(string $uri, string $contentFilepath): void
    {
        self::assertCount(1, $this->httpTransactions);
        /** @var Request $request */
        $request = reset($this->httpTransactions)['request'];
        self::assertEquals('POST', $request->getMethod());
        self::assertEquals($uri, (string) $request->getUri());
        self::assertJsonStringEqualsJsonString(
            $this->getResourceContent($contentFilepath),
            $request->getBody()->getContents()
        );
    }
}
