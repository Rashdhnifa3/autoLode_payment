<?php

namespace App;

class Router
{
    public function __construct(private array $routes)
    {
    }

    // Return the controller, action, and any numeric ID argument.
    public function resolve(string $method, string $path): ?array
    {
        foreach ($this->routes as $route => [$controller, $action]) {
            [$routeMethod, $routePath] = explode(' ', $route, 2);
            if ($method !== $routeMethod) {
                continue;
            }
            $pattern = str_replace(
                '\\{id\\}', '([1-9][0-9]*)', preg_quote($routePath, '#')
            );
            if (!preg_match('#^' . $pattern . '$#D', $path, $matches)) {
                continue;
            }
            $arguments = [];
            if (isset($matches[1])) {
                $id = filter_var($matches[1], FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 1],
                ]);
                if ($id === false) {
                    return null;
                }
                $arguments[] = $id;
            }
            return [$controller, $action, $arguments];
        }
        return null;
    }
}
