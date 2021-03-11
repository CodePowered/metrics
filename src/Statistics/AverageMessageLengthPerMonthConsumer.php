<?php declare(strict_types=1);

namespace App\Statistics;

use App\Provider\Post;

class AverageMessageLengthPerMonthConsumer implements ConsumerInterface
{
    private const KEY = 'average_message_length_per_month';

    private array $data = [];

    public function getKey(): string
    {
        return self::KEY;
    }

    public function eat(Post $post): void
    {
        $timeGroup = $post->getCreatedTime()->format('Y-m');
        $this->data[$timeGroup] ??= ['value' => 0, 'count' => 0];
        $this->data[$timeGroup]['count']++;
        $this->data[$timeGroup]['value'] += mb_strlen($post->getMessage());
    }

    public function digest(): array
    {
        $result = [];

        foreach ($this->data as $timeGroup => $collected) {
            $result[$timeGroup] = round($collected['value'] / $collected['count'], 1);
        }

        // Reset data
        $this->data = [];

        return $result;
    }
}
