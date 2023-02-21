<?php

/**
 * Db Connector
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils;

use Exception;
use PDO;

final class DbAccess
{
    protected static PDO $db;

    public static function query(string $query, array $params = []): void
    {
        $statement = static::getConnector()->prepare($query);
        if (empty($statement)) {
            throw new Exception("Unable to prepare query.");
        }

        $statement->execute($params);

        if ((int)$statement->errorCode() > 0) {
            var_dump($query, $params, $statement->errorInfo());die();
        }
    }

    public static function fetch(string $query, array $params = []): array
    {
        $statement = static::getConnector()->prepare($query);
        if (empty($statement)) {
            throw new Exception("Unable to prepare query.");
        }

        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function getConnector(): PDO
    {
        if (empty(static::$db)) {
            $con_str = "mysql:host=localhost;port=3307;dbname=php_demo";
            $pdo = new PDO($con_str, "php_demo", "123");
            static::$db = $pdo;
        }

        return static::$db;
    }
}
