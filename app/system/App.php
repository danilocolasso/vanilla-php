<?php

namespace System;

use System\Http\Request\Request;
use System\Http\Routing\Router;

/**
 * Class App
 * Init some features needed for app architecture.
 * @package System
 */
class App
{
    /**
     * Init the app with all it needs
     */
    public function init()
    {
        $this->createRoutes();
    }

    /**
     * Create all routes based on routing.yml
     * @return void
     */
    protected function createRoutes()
    {
        $routes = yaml_parse_file(__DIR__ . '/../config/routing.yml');
        $router = new Router(new Request);

        foreach($routes as $route) {
            foreach($route['methods'] as $method) {
                $controller = new $route['controller']();
                $method     = strtolower($method);
                $action     = $route['action'];

                /*
                * Use closure function for callback
                * because we want to send controller and action.
                */
                $callback = function($request) use ($controller, $action) {
                    $controller->$action($request);
                };

                $router->{$method}($route['path'], $callback);
            }
        }
    }
}