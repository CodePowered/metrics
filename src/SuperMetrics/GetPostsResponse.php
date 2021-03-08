<?php declare(strict_types=1);

namespace App\SuperMetrics;

class GetPostsResponse extends ResponseEnvelope
{
    private GetPostsResponseData $data;

    public function __construct(GetPostsResponseData $data)
    {
        $this->data = $data;
    }

    public function getData(): GetPostsResponseData
    {
        return $this->data;
    }
}
