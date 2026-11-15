<?php

namespace App\Models;

use App\Database\MysqlDatabase;
use PDO;

class User
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? (new MysqlDatabase())->pdo();
    }

    public function all(): array
    {
        return $this->pdo->query(
            'SELECT id, name, email, age FROM users ORDER BY id DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): array|false
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, email, age FROM users WHERE id = ?'
        );
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists(string $email, ?int $exceptId = null): bool
    {
        $sql = 'SELECT id FROM users WHERE email = ?';
        $parameters = [$email];
        if ($exceptId !== null) {
            $sql .= ' AND id <> ?';
            $parameters[] = $exceptId;
        }
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetchColumn() !== false;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (name, email, password, age) VALUES (?, ?, ?, ?)'
        );
        $statement->execute([
            $data['name'], $data['email'], $data['password'], $data['age'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $sql = 'UPDATE users SET name = ?, email = ?, age = ?';
        $parameters = [$data['name'], $data['email'], $data['age']];
        if (isset($data['password'])) {
            $sql .= ', password = ?';
            $parameters[] = $data['password'];
        }
        $sql .= ' WHERE id = ?';
        $parameters[] = $id;
        $this->pdo->prepare($sql)->execute($parameters);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
    }
}
