<?php declare(strict_types=1);

namespace App\SuperMetrics;

use App\Provider\Post;

class GetPostsResponseData
{
    /**
     * @var Post[] $posts
     */
    private array $posts;

    public function __construct(array $posts)
    {
        $this->posts = $posts;
    }

    public function getPosts(): array
    {
        return $this->posts;
    }
}
