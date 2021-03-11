<?php declare(strict_types=1);

namespace App\Statistics;

use App\Provider\Post;

interface ConsumerInterface
{
    public function getKey(): string;

    public function eat(Post $post): void;

    /**
     * @return mixed
     */
    public function digest();
}
