<?php declare(strict_types=1);

namespace App\SuperMetrics;

class TokenResponse
{
    private TokenResponseData $data;

    public function __construct(TokenResponseData $data)
    {
        $this->data = $data;
    }

    public function getData(): TokenResponseData
    {
        return $this->data;
    }
}
