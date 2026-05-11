<?php

namespace Core;

class SimpleRouter
{
    private $routes = [];
    private $notFoundCallback;

    public function get($uri, $callback)
    {
        $this->routes['GET'][trim($uri, '/')] = $callback;
    }

    public function post($uri, $callback)
    {
        $this->routes['POST'][trim($uri, '/')] = $callback;
    }

    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $callback = $this->routes[$method][$uri];

            if (is_callable($callback)) {
                call_user_func($callback);
            } elseif (is_array($callback)) {
                [$controller, $method] = $callback;
                $instance = new $controller();
                $instance->$method();
            }
        } else {
            // 404
            if ($this->notFoundCallback) {
                call_user_func($this->notFoundCallback);
            } else {
                echo "404 - Page Not Found";
            }
        }
    }
}
