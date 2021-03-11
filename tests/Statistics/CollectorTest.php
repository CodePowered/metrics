<?php declare(strict_types=1);

namespace App\Tests\Statistics;

use App\DependencyInjection\ServiceBuilder;
use App\Provider\Client;
use App\Tests\AbstractTest;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use Redis;

class CollectorTest extends AbstractTest
{
    public function testFullWithExternalServiceMocks(): void
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->expects(self::once())
            ->method('post')
            ->willReturn($this->makeResponse('register_response.json'));
        $httpClient->expects(self::exactly(10))
            ->method('get')
            ->willReturnCallback(
                function () {
                    static $page = 1;
                    return $this->makeResponse(sprintf('posts_response_page_%d.json', $page++));
                }
            );

        $redis = $this->createMock(Redis::class);
        $redis->expects(self::once())
            ->method('get')
            ->willReturn(null);
        $redis->expects(self::once())
            ->method('setex');

        $collector = ServiceBuilder::buildCollector($httpClient, $redis);
        $actual = $collector->collect(new Client('id', 'email', 'name'));
        $expected = [
            'average_message_length_per_month' => [
                '2021-03' => 322.0,
                '2021-02' => 366.1,
                '2021-01' => 352.8,
                '2020-12' => 373.7,
                '2020-11' => 372.9,
                '2020-10' => 377.0,
                '2020-09' => 487.5,
            ],
            'max_message_length_per_month' => [
                '2021-03' => 383,
                '2021-02' => 615,
                '2021-01' => 694,
                '2020-12' => 687,
                '2020-11' => 713,
                '2020-10' => 728,
                '2020-09' => 734,
            ],
            'total_message_count_per_week' => [
                '09' => 3,
                '08' => 4,
                '07' => 5,
                '06' => 4,
                '05' => 4,
                '04' => 4,
                '03' => 4,
                '02' => 4,
                '01' => 4,
                '53' => 5,
                '52' => 4,
                '51' => 4,
                '50' => 4,
                '49' => 4,
                '48' => 4,
                '47' => 4,
                '46' => 4,
                '45' => 5,
                '44' => 4,
                '43' => 4,
                '42' => 4,
                '41' => 4,
                '40' => 4,
                '39' => 4,
                '38' => 2,
            ],
            'average_message_count_per_user_per_month' => [
                '2021-03' => 1.0,
                '2021-02' => 1.5,
                '2021-01' => 1.5,
                '2020-12' => 1.4,
                '2020-11' => 1.5,
                '2020-10' => 1.6,
                '2020-09' => 1.3,
            ],
        ];

        self::assertSame($expected, $actual);
    }

    private function makeResponse(string $filename): Response
    {
        return new Response(200, [], $this->getResourceContent($filename));
    }
}
