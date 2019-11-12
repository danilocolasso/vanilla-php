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

    private $params = [];

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
        $this->request->params = [];
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
        $path   = explode('/', $route);
        $params = [];

        foreach($path as $key => $token) {
            if($token) {
                if ($token[0] === '{' && $token[strlen($token) - 1] === '}') {
                    $token = ltrim(rtrim($token, '}'), '{');
                    array_push($params, $token);
                }
            }
        }

        $this->params[$route] = $params ? $params : false;

        return $result === '' ? '/' : $result;
    }

    /**
     * Map the route and append params to Request Object
     * @param $requestedRoute
     * @return int|string
     */
    private function mapRequestedRoute($requestedRoute)
    {
        $route = rtrim($requestedRoute, '/');

        //Route without params
        if (
            isset($this->params[$requestedRoute])
            && $this->params[$requestedRoute] === false
        ) {
            return $route === '' ? '/' : $route;
        }

        //Route with params
        foreach ($this->params as $key => $value) {
            if (is_array($value)) {
                $append = true;
                $params = [];
                $values = [];
                $path   = explode('/', $key);

                $requestedPath = explode('/', $route);

                for ($i = 1; $i < count($requestedPath); $i++) {
                    if (
                        $path[$i][0] === '{'
                        && $path[$i][strlen($path[$i]) - 1] === '}'
                    ) {
                        $params[] = ltrim(rtrim($path[$i], '}'), '{');
                        $values[] = $requestedPath[$i];
                        continue;

                    } else if ($path[$i] !== $requestedPath[$i]) {
                        $append = false;
                    }
                }

                //Append params to request object
                if ($append && count($requestedPath) === count($path)) {
                    for ($i = 0; $i < count($params); $i++) {
                        $this->request->params[$params[$i]] = $values[$i];
                    }

                    return $key;
                }
            }
        }
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
        $formatedRoute = $this->mapRequestedRoute($this->request->requestUri);
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