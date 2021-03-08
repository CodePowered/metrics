<?php declare(strict_types=1);

namespace App\SuperMetrics;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class Api
{
    private const REGISTER = 'register';
    private const POSTS = 'posts';

    private const FORMAT = 'json';
    private const PAGES = 10;

    private static array $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * @var SerializerInterface|NormalizerInterface
     */
    private SerializerInterface $serializer;
    private HttpClient $httpClient;
    private string $apiUrl;

    public function __construct(SerializerInterface $serializer, HttpClient $httpClient, string $apiUrl)
    {
        if (!$serializer instanceof NormalizerInterface) {
            throw new InvalidArgumentException('Serializer must support normalization.');
        }

        $this->serializer = $serializer;
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @throws GuzzleException
     */
    public function register(TokenRequest $request): TokenResponse
    {
        return $this->handlePostRequest(TokenResponse::class, self::REGISTER, $request);
    }

    /**
     * @throws GuzzleException
     */
    public function getPosts(GetPostsRequest $request): GetPostsResponse
    {
        return $this->handleGetRequest(GetPostsResponse::class, self::POSTS, $request);
    }

    /**
     * @throws GuzzleException
     */
    private function handleGetRequest(string $responseType, string $action, object $request): ResponseEnvelope
    {
        $response = $this->httpClient->get(
            $this->buildUri($action),
            $this->buildGetOptions($request)
        );

        return $this->deserializeResponse($response, $responseType);
    }

    /**
     * @throws GuzzleException
     */
    private function handlePostRequest(string $responseType, string $action, object $request): ResponseEnvelope
    {
        $response = $this->httpClient->post(
            $this->buildUri($action),
            $this->buildPostOptions($request)
        );

        return $this->deserializeResponse($response, $responseType);
    }

    private function buildUri(string $action): string
    {
        return $this->apiUrl . $action;
    }

    private function buildGetOptions(object $request): array
    {
        return [
            'headers' => self::$headers,
            'query' => $this->serializer->normalize($request, self::FORMAT)
        ];
    }

    private function buildPostOptions(object $request): array
    {
        return [
            'headers' => self::$headers,
            'body' => $this->serializer->serialize($request, self::FORMAT)
        ];
    }

    private function deserializeResponse(ResponseInterface $response, string $responseType): ResponseEnvelope
    {
        return $this->serializer->deserialize($response->getBody()->getContents(), $responseType, self::FORMAT);
    }

    public function getPageCount(): int
    {
        return self::PAGES;
    }
}
