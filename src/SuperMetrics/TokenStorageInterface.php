<?php declare(strict_types=1);

namespace App\SuperMetrics;

interface TokenStorageInterface
{
    public function get(string $clientId): ?string;

    public function set(string $clientId, string $token): void;
}
