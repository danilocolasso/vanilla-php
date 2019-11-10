<?php

namespace System\Http\Request;

/**
 * Class Request
 * Manage all app requests.
 *
 * @package System\Http
 */
class Request implements RequestInterface
{
    /**
     * Request constructor.
     */
    function __construct()
    {
        $this->bootstrapSelf();
    }

    /**
     * Create an attribute base on $_SERVER values.
     * @return void
     */
    private function bootstrapSelf()
    {
        foreach($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    /**
     * Converts snake_case to CamelCase.
     * @param $string
     * @return string
     */
    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach($matches[0] as $match) {
            $capitalize = str_replace('_', '', strtoupper($match));
            $result     = str_replace($match, $capitalize, $result);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        switch ($this->requestMethod) {
            case 'GET':
                return;
                break;

            case 'POST':
                $body = [];
                foreach ($_POST as $key => $value) {
                    $body[$key] = filter_input(
                        INPUT_POST,
                        $key,
                        FILTER_SANITIZE_SPECIAL_CHARS
                    );
                }
                return $body;
                break;
        }
    }
}