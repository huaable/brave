<?php

namespace Brave;

class Router
{
    public $method; //GET、POST
    public $path;
    public $callback;

    public static function get($path, $callback)
    {
        $route = new self();
        $route->addRoute($path, $callback, 'GET');
        return $route;
    }

    public static function post($path, $callback)
    {
        $route = new self();
        $route->addRoute($path, $callback, 'POST');
        return $route;
    }

    public static function any($path, $callback)
    {
        $route = new self();
        $route->addRoute($path, $callback);
        return $route;
    }

    protected function addRoute($path, $params, $method = null)
    {
        $this->path = $path;
        $this->callback = $params;
        $this->method = $method;
        if (isset(App::$routes[$this->path])) {
            exit($this->path . '路由已存在');
        }
        App::$routes[$this->path] = $this;
    }

    public static function matchRoute(Request $request, Router $route)
    {
        if ($request->getPathInfo() == $route->path) {
            return $route->callback;
        }
        return null;
    }
}