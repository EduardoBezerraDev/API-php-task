<?php

namespace App;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Router
{
    private array $routes = [];

    public function get(string $path, callable $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function put(string $path, callable $callback)
    {
        $this->routes['PUT'][$path] = $callback;
    }
    public function delete(string $path, callable $callback)
    {
        $this->routes['DELETE'][$path] = $callback;
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            $callback();
        } else {
            http_response_code(404);
            echo "Endpoint não encontrado";
        }
    }
}
