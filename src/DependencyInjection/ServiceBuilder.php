<?php declare(strict_types=1);

namespace App\DependencyInjection;

use App\Provider\ProviderInterface;
use App\Statistics\AverageMessageCountPerUserPerMonthConsumer;
use App\Statistics\AverageMessageLengthPerMonthConsumer;
use App\Statistics\Collector;
use App\Statistics\MaxMessageLengthPerMonthConsumer;
use App\Statistics\TotalMessageCountPerWeekConsumer;
use App\SuperMetrics\Api;
use App\SuperMetrics\RedisTokenStorage;
use App\SuperMetrics\SerializerBuilder;
use App\SuperMetrics\SuperMetricsProvider;
use GuzzleHttp\Client as HttpClient;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Redis;

class ServiceBuilder
{
    public static function buildCollector(HttpClient $httpClient = null, Redis $redis = null): Collector
    {
        return new Collector(
            self::buildProvider($httpClient, $redis),
            [
                new AverageMessageLengthPerMonthConsumer(),
                new MaxMessageLengthPerMonthConsumer(),
                new TotalMessageCountPerWeekConsumer(),
                new AverageMessageCountPerUserPerMonthConsumer(),
            ],
        );
    }

    private static function buildProvider(?HttpClient $httpClient, ?Redis $redis): ProviderInterface
    {
        $logger = self::buildLogger();

        return new SuperMetricsProvider(
            new Api(
                SerializerBuilder::build(),
                $httpClient ?? new HttpClient(),
                'https://api.supermetrics.com/assignment/',
            ),
            new RedisTokenStorage(
                $redis ?? self::buildRedis(),
                $logger,
            ),
            $logger,
        );
    }

    /**
     * @codeCoverageIgnore - runtime services builder class.
     */
    private static function buildLogger(): LoggerInterface
    {
        return new Logger(
            'main',
            [
                (new StreamHandler('php://stdout'))->setFormatter(new LineFormatter()),
            ]
        );
    }

    /**
     * @codeCoverageIgnore - runtime services builder class.
     */
    private static function buildRedis(): Redis
    {
        $redis = new Redis();
        $redis->connect(
            getenv('REDIS_HOST') ?: 'localhost',
            getenv('REDIS_PORT') ?: 6379,
        );

        return $redis;
    }
}
