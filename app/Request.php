<?php
namespace App;

class Request
{
    public function method(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            $override = $_POST['_method'] ?? '';
            if (is_string($override)) {
                $override = strtoupper($override);
                if (in_array($override, ['PUT', 'DELETE'], true)) {
                    return $override;
                }
            }
        }
        return $method;
    }

    public function path(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function post(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;
        return is_string($value) ? trim($value) : $default;
    }

    public function get(string $key, string $default = ''): string
    {
        $value = $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $default;
    }
}
