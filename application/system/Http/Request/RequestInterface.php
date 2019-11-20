<?php

namespace System\Http\Request;

/**
 * Interface RequestInterface
 * @package System\Http
 */
interface RequestInterface
{
    /**
     * Retrieves data from the request body.
     * @return array|void
     */
    public function getBody();

    /**
     * Get request method
     * @return string
     */
    public function getMethod();

    /**
     * Get all parameters
     * @return mixed
     */
    public function getParams();

    /**
     * Get a specific parameter
     * @param $name
     * @return mixed
     */
    public function getParam($name);
}