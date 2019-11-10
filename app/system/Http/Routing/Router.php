<?php

namespace System\Http\Routing;

use System\Http\Request\RequestInterface;

/**
 * Class Router
 * Manager app routes
 *
 * @package System\Http
 */
class Router
{
    private $request;

    private $supportedHttpMethods = [
        'GET',
        'POST'
    ];

    /**
     * Router constructor.
     * @param RequestInterface $request
     */
    function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Dynamically create an associative array that map routes to callbacks.
     * @param $name
     * @param $args
     * @throws \Exception
     */
    function __call($name, $args)
    {
        list($route, $method) = $args;

        if(!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param string $route
     * @return string
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        return $result === '' ? '/' : $result;
    }

    /**
     * For method not allowed.
     * @throws \Exception
     */
    private function invalidMethodHandler()
    {
        throw new \Exception("Method not allowed.", 405);
    }

    /**
     * For not found
     * @throws \Exception
     */
    private function defaultRequestHandler()
    {
        throw new \Exception("Method not allowed.", 404);
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};

        $formatedRoute = $this->formatRoute($this->request->requestUri);

        $method = $methodDictionary[$formatedRoute];

        if(is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }

        echo call_user_func_array($method, [$this->request]);
    }

    /**
     * Destruct
     */
    function __destruct()
    {
        $this->resolve();
    }
}