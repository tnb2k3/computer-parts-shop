<?php

namespace App\Core;

class Controller
{
    /**
     * Render a view with data
     */
    protected function view(string $viewPath, array $data = []): void
    {
        View::render($viewPath, $data);
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Get POST data
     */
    protected function getPost(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     */
    protected function getGet(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Check if request is POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Get session data
     */
    protected function getSession(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set session data
     */
    protected function setSession(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Destroy session
     */
    protected function destroySession(): void
    {
        session_destroy();
    }

    /**
     * JSON response
     */
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
