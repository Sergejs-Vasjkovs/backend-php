<?php

declare(strict_types=1);

namespace App\Core\Cache;

use Predis\Client;

class Redis
{
    private static ?Client $instance = null;
    private static int $ttl = 3600; // 1 hour
    private static bool $isAvailable = true;

    public static function getInstance(): ?Client
    {
        if (!self::$isAvailable) {
            return null;
        }

        if (self::$instance === null) {
            try {
                self::$instance = new Client([
                    'scheme' => $_ENV['REDIS_SCHEME'] ?? 'tcp',
                    'host'   => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
                    'port'   => $_ENV['REDIS_PORT'] ?? 6379,
                ]);
                // Проверяем подключение
                self::$instance->ping();
            } catch (\Exception $e) {
                self::$isAvailable = false;
                return null;
            }
        }

        return self::$instance;
    }

    public static function getTtl(): int
    {
        return self::$ttl;
    }
}
