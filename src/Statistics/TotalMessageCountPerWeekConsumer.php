<?php declare(strict_types=1);

namespace App\Statistics;

use App\Provider\Post;

class TotalMessageCountPerWeekConsumer implements ConsumerInterface
{
    private const KEY = 'total_message_count_per_week';

    private array $data = [];

    public function getKey(): string
    {
        return self::KEY;
    }

    public function eat(Post $post): void
    {
        $timeGroup = (string) $post->getCreatedTime()->format('W');
        $this->data[$timeGroup] ??= 0;
        $this->data[$timeGroup]++;
    }

    public function digest(): array
    {
        $result = $this->data;

        // Reset data
        $this->data = [];

        return $result;
    }
}
