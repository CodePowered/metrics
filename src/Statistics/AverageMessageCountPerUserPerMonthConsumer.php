<?php declare(strict_types=1);

namespace App\Statistics;

use App\Provider\Post;

class AverageMessageCountPerUserPerMonthConsumer implements ConsumerInterface
{
    private const KEY = 'average_message_count_per_user_per_month';

    private array $data = [];

    public function getKey(): string
    {
        return self::KEY;
    }

    public function eat(Post $post): void
    {
        $timeGroup = $post->getCreatedTime()->format('Y-m');
        $this->data[$timeGroup] ??= ['count' => 0, 'users' => []];
        $this->data[$timeGroup]['count']++;
        $this->data[$timeGroup]['users'][$post->getAuthorId()] = 1; // just storing as unique keys
    }

    public function digest(): array
    {
        $result = [];

        foreach ($this->data as $timeGroup => $collected) {
            $result[$timeGroup] = round($collected['count'] / count($this->data[$timeGroup]['users']), 1);
        }

        // Reset data
        $this->data = [];

        return $result;
    }
}
