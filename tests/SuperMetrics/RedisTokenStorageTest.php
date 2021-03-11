<?php declare(strict_types=1);

namespace App\Tests\SuperMetrics;

use App\SuperMetrics\RedisTokenStorage;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Redis;

class RedisTokenStorageTest extends TestCase
{
    private RedisTokenStorage $storage;
    private Redis $redis;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->redis = $this->createMock(Redis::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this>$this->storage = new RedisTokenStorage($this->redis, $this->logger);
    }

    /**
     * @dataProvider provideGetCases
     *
     * @param mixed $redisValue
     */
    public function testGet($redisValue, ?string $expectedResult, int $expectedWarnings): void
    {
        $this->redis->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('client-ID-string'))
            ->willReturn($redisValue);
        $this->logger->expects(self::exactly($expectedWarnings))
            ->method('warning');

        self::assertSame($expectedResult, $this->storage->get('client-ID-string'));
    }

    public function provideGetCases(): array
    {
        return [
            'false-null' => [false, null, 0],
            'null-null' => [null, null, 0],
            'empty-null' => ['', null, 0],
            'string-string' => ['some-TOKEN', 'some-TOKEN', 0],
            'object-warning' => [(object)[], null, 1],
        ];
    }

    public function testSet(): void
    {
        $this->redis->expects(self::once())
            ->method('setex')
            ->with(
                self::identicalTo('other-client-ID'),
                self::identicalTo(3600),
                self::identicalTo('seven-TEKKEN'),
            );

        $this->storage->set('other-client-ID', 'seven-TEKKEN');
    }
}
