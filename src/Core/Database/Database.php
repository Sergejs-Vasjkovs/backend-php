<?php

declare(strict_types=1);

namespace App\Core\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                    $_ENV['DB_HOST'],
                    $_ENV['DB_PORT'],
                    $_ENV['DB_DATABASE'],
                    $_ENV['DB_CHARSET'] ?? 'utf8mb4'
                );

                self::$connection = new PDO(
                    $dsn,
                    $_ENV['DB_USERNAME'],
                    $_ENV['DB_PASSWORD'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$connection;
    }
}
