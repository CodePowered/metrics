<?php declare(strict_types=1);

namespace App\SuperMetrics;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Serializer\SerializerInterface;

class Client
{
    private const REGISTER = 'register';

    private const FORMAT = 'json';

    private static array $headers = [
        'Content-Type' => 'application/json',
    ];

    private SerializerInterface $serializer;
    private HttpClient $httpClient;
    private string $apiUrl;

    public function __construct(SerializerInterface $serializer, HttpClient $httpClient, string $apiUrl)
    {
        $this->serializer = $serializer;
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @throws GuzzleException
     */
    public function register(TokenRequest $request): TokenResponse
    {
        return $this->handleRequest($request, TokenResponse::class);
    }

    /**
     * @throws GuzzleException
     */
    private function handleRequest(object $request, string $responseType)
    {
        $response = $this->httpClient->post(
            $this->apiUrl . self::REGISTER,
            $this->buildOptions($request)
        );

        return $this->serializer->deserialize($response->getBody()->getContents(), $responseType, self::FORMAT);
    }

    private function buildOptions(object $request): array
    {
        return [
            'headers' => self::$headers,
            'body' => $this->serializer->serialize($request, self::FORMAT)
        ];
    }
}
