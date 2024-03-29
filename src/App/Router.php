<?php

namespace App;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->setCorsHeaders();
    }

    private function setCorsHeaders(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    }

    public function get(string $path, callable $callback): void
    {
        $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, callable $callback): void
    {
        $this->addRoute('POST', $path, $callback);
    }

    public function put(string $path, callable $callback): void
    {
        $this->addRoute('PUT', $path, $callback);
    }

    public function delete(string $path, callable $callback): void
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    public function getById(string $path, callable $callback): void
    {
        $this->addRoute('GET', $path, $callback, true);
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($method === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        $this->handleRoute($method, $path);
    }

    private function handleRoute(string $method, string $path): void
{
    foreach ($this->routes[$method] as $route) {
        [$routePath, $callback, $isGetById] = $route;
        if ($routePath === $path) {
            $callback();
            return;
        }
    }

    $this->handleNotFound();
}

    public function addRoute(string $method, string $path, callable $callback, bool $isGetById = false): void
    {
        $this->routes[$method][] = [$path, $callback, $isGetById];
    }

    private function handleNotFound(): void
    {
        http_response_code(404);
        echo "Endpoint não encontrado";
    }
}
