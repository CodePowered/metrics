<?php declare(strict_types=1);

namespace App\Statistics;

use App\Provider\Post;

class MaxMessageLengthPerMonthConsumer implements ConsumerInterface
{
    private const KEY = 'max_message_length_per_month';

    private array $data = [];

    public function getKey(): string
    {
        return self::KEY;
    }

    public function eat(Post $post): void
    {
        $timeGroup = $post->getCreatedTime()->format('Y-m');
        $this->data[$timeGroup] ??= [0];
        $this->data[$timeGroup][] = mb_strlen($post->getMessage());
    }

    public function digest(): array
    {
        $result = [];

        foreach ($this->data as $timeGroup => $collected) {
            $result[$timeGroup] = max($collected);
        }

        // Reset data
        $this->data = [];

        return $result;
    }
}
