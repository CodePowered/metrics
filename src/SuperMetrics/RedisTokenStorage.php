<?php declare(strict_types=1);

namespace App\SuperMetrics;

use Psr\Log\LoggerInterface;
use Redis;

class RedisTokenStorage implements TokenStorageInterface
{
    private const SECONDS_TO_LIVE = 3600;

    private Redis $redis;
    private LoggerInterface $logger;

    public function __construct(Redis $redis, LoggerInterface $logger)
    {
        $this->redis = $redis;
        $this->logger = $logger;
    }

    public function get(string $clientId): ?string
    {
        $value = $this->redis->get($clientId);

        if (empty($value)) {
            return null;
        }

        if (! is_string($value)) {
            $this->logger->warning('Invalid token received from Redis', ['value' => $value]);
            $value = null;
        }

        return $value;
    }

    public function set(string $clientId, string $token): void
    {
        $this->redis->setex($clientId, self::SECONDS_TO_LIVE, $token);
    }
}
