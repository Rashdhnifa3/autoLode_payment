<?php
namespace App\Database;

use PDO;

class MysqlDatabase
{
    private PDO $pdo;

    public function __construct()
    {
        // TODO: Use "service container" to singleton and globalize mysql variable
        $config = [
            'host'     => $_ENV['DB_HOST'],
            'dbname'   => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ];

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function insert(string $table, array $data): string|int|null
    {
        $keys = array_keys($data);
        $implodedKeys = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));

        $values = array_values($data);

        $stmt = $this->pdo->prepare(
            "INSERT INTO $table ($implodedKeys) VALUES ($placeholders)"
        );

        $stmt->execute($values);

        return $this->pdo->lastInsertId();
    }
}