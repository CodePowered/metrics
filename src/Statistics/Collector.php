<?php declare(strict_types=1);

namespace App\Statistics;

use App\Provider\Client;
use App\Provider\ProviderInterface;

class Collector
{
    private ProviderInterface $provider;

    /**
     * @var ConsumerInterface[]
     */
    private array $consumers;

    public function __construct(ProviderInterface $provider, array $consumers)
    {
        $this->provider = $provider;
        $this->consumers = [];

        foreach ($consumers as $consumer) {
            $this->addConsumer($consumer);
        }
    }

    private function addConsumer(ConsumerInterface $consumer): void
    {
        $this->consumers[] = $consumer;
    }

    public function collect(Client $client): array
    {
        foreach ($this->provider->getPosts($client) as $post) {
            foreach ($this->consumers as $consumer) {
                $consumer->eat($post);
            }
        }

        $statistics = [];
        foreach ($this->consumers as $consumer) {
            $statistics[$consumer->getKey()] = $consumer->digest();
        }

        return $statistics;
    }
}
