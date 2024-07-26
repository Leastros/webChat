<?php

class DB
{
    public static function connect()
    {
        // dev environment only
        if (getenv("ENVIRONMENT") != 'prod') {

            // je to ošimetné, závisí na cestě DB.php, ale jinde .env varables potřebovat nejspíš nebudeme
            $envFile = dirname(__DIR__) . '/.env';

            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) {
                        continue; // Skip comments
                    }
                    list($name, $value) = explode('=', $line, 2);
                    $name = trim($name);
                    $value = trim($value);
                    if (!empty($name)) {
                        $_ENV[$name] = $value;   // Set the variable in $_ENV superglobal
                    }
                }
            }
        } else {
            $_ENV['DB_HOST'] ??= getenv("DB_HOST");
            $_ENV['DB_USER'] ??= getenv("DB_USER");
            $_ENV['DB_PASSWORD'] ??= getenv("DB_PASSWORD");
            $_ENV['DB_NAME'] ??= getenv("DB_NAME");
            $_ENV['DB_PORT'] ??= getenv("DB_PORT");
            $_ENV['DB_SOCKET'] ??= getenv("DB_SOCKET");
        }

        // connect
        $mysqli = new mysqli(
            $_ENV['DB_HOST'] ?? ini_get("mysqli.default_host"),
            $_ENV['DB_USER'] ?? ini_get("mysqli.default_user"),
            $_ENV['DB_PASSWORD'] ?? ini_get("mysqli.default_pw"),
            $_ENV['DB_NAME'] ?? "",
            $_ENV['DB_PORT'] ?? ini_get("mysqli.default_port"),
            $_ENV['DB_SOCKET'] ?? ini_get("mysqli.default_socket")
        );

        return $mysqli;

        /*
        try {
            return $conn;
        }
        catch (Exception $ex) {
            return null;
        }
        */
    }

    public static function prepare($query, $paramTypes, ...$params): mysqli_stmt
    {
        $conn = self::connect();
        $stmt = $conn->prepare($query);
        $stmt->bind_param($paramTypes, ...$params);
        return $stmt;
    }

    public static function execute(mysqli_stmt $stmt)
    {
        $stmt->execute();
    }
}
