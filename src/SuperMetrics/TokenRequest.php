<?php declare(strict_types=1);

namespace App\SuperMetrics;

class TokenRequest
{
    private string $clientId;
    private string $email;
    private string $name;

    public function __construct(string $clientId, string $email, string $name)
    {
        $this->clientId = $clientId;
        $this->email = $email;
        $this->name = $name;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
