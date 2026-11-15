<?php

function view(string $name, array $data = [], int $status = 200): void
{
    http_response_code($status);
    extract($data);
    require __DIR__ . "/views/$name.php";
}

function redirect(string $url): never
{
    header("Location: $url");
    exit;
}

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
