<?php

namespace UnityCare\Controllers;

use UnityCare\Services\Response;
use UnityCare\Services\View;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        Response::redirect($url, $statusCode);
    }
}
