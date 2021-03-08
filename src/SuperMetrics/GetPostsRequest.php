<?php declare(strict_types=1);

namespace App\SuperMetrics;

class GetPostsRequest
{
    private string $token;
    private int $page;

    public function __construct(string $token, int $page)
    {
        $this->token = $token;
        $this->page = $page;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
