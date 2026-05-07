<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    /**
     * Get database connection instance (Singleton pattern)
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                // Load environment variables from .env file (for local development)
                $envFile = __DIR__ . '/../../.env';
                if (file_exists($envFile)) {
                    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        if (strpos(trim($line), '#') === 0) continue;
                        if (strpos($line, '=') === false) continue;
                        list($key, $value) = explode('=', $line, 2);
                        $key = trim($key);
                        $value = trim($value);
                        // Only set if not already set by system environment
                        if (!getenv($key)) {
                            $_ENV[$key] = $value;
                            putenv("$key=$value");
                        }
                    }
                }

                // Priority: system env (cloud) > .env file (local)
                $host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'mysql');
                $dbname = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'computer_shop');
                $username = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'root');
                $password = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? '');
                $charset = 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                throw new PDOException("Connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}

    /**
     * Prevent unserializing of the instance
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
