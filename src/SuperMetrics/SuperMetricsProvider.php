<?php declare(strict_types=1);

namespace App\SuperMetrics;

use App\Provider\Client;
use App\Provider\ProviderInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class SuperMetricsProvider implements ProviderInterface
{
    private Api $api;
    private TokenStorageInterface $tokenStorage;
    private LoggerInterface $logger;

    public function __construct(Api $api, TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->api = $api;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    public function getPosts(Client $client): iterable
    {
        try {
            $token = $this->getToken($client);
            $pageCount = $this->api->getPageCount();

            for ($i = 1; $i <= $pageCount; $i++) {
                $response = $this->api->getPosts(new GetPostsRequest($token, $i));
                foreach ($response->getData()->getPosts() as $post) {
                    yield $post;
                }
            }
        } catch (Exception|GuzzleException $exception) {
            $this->logger->warning('Failed to receive posts.', ['exception' => $exception]);
        }
    }

    /**
     * @throws GuzzleException
     */
    private function getToken(Client $client): string
    {
        $clientId = $client->getId();
        $token = $this->tokenStorage->get($clientId);

        if ($token === null) {
            $request = new TokenRequest($clientId, $client->getEmail(), $client->getName());
            $token = $this->api->register($request)->getData()->getToken();
            $this->tokenStorage->set($clientId, $token);
        }

        return $token;
    }
}
