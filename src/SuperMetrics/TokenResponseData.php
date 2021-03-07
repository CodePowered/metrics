<?php declare(strict_types=1);

namespace App\SuperMetrics;

class TokenResponseData
{
    private string $clientId;
    private string $email;
    private string $token;

    public function __construct(string $clientId, string $email, string $token)
    {
        $this->clientId = $clientId;
        $this->email = $email;
        $this->token = $token;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
