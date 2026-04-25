<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * Add a route
     */
    public function add(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Add a GET route
     */
    public function get(string $path, string $controller, string $action): void
    {
        $this->add('GET', $path, $controller, $action);
    }

    /**
     * Add a POST route
     */
    public function post(string $path, string $controller, string $action): void
    {
        $this->add('POST', $path, $controller, $action);
    }

    /**
     * Dispatch the request
     */
    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Remove base path if exists
        $basePath = '/';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');

        // Find matching route
        foreach ($this->routes as $route) {
            $pattern = $this->convertPathToPattern($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callAction($route['controller'], $route['action'], $matches);
                return;
            }
        }

        // No route found - 404
        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Convert path pattern to regex
     */
    private function convertPathToPattern(string $path): string
    {
        // Convert :param to regex capture group
        $pattern = preg_replace('/\/:([^\/]+)/', '/([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Call the controller action
     */
    private function callAction(string $controllerClass, string $action, array $params = []): void
    {
        $controllerClass = "App\\Controllers\\$controllerClass";
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller not found: $controllerClass");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            throw new \Exception("Action not found: $action in $controllerClass");
        }

        call_user_func_array([$controller, $action], $params);
    }
}
