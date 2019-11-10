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
}