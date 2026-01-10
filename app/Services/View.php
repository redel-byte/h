<?php

namespace UnityCare\Services;

class View
{
    public static function render(string $view, array $data = []): void
    {
        $root = dirname(__DIR__, 2);
        $path = $root . '/resources/views/' . ltrim($view, '/');

        if (substr($path, -4) !== '.php') {
            $path .= '.php';
        }

        if (!file_exists($path)) {
            http_response_code(500);
            echo 'View not found';
            exit;
        }

        extract($data, EXTR_SKIP);

        require $path;
    }
}
