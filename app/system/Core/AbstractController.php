<?php

namespace System\Core;

use System\Render\RenderInterface;
use System\Render\TwigRender;

/**
 * Class AbstractController
 * @package System\Core
 */
class AbstractController
{
    /**
     * @var RenderInterface
     */
    protected $render;

    /**
     * All app routes
     * @var array
     */
    protected $routes;

    /**
     * AbstractController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $parameters = yaml_parse_file(
            __DIR__ . '../../../config/parameters.yml'
        );

        $this->routes = yaml_parse_file(
            __DIR__ . '../../../config/routing.yml'
        );

        switch ($parameters['render']) {
            case 'twig':
                $this->render = new TwigRender($this->routes);
                break;

            default:
                throw new \Exception(sprintf(
                    'There is no render called %s. Change it on config.yml',
                    $parameters['render']
                ));
                break;
        }
    }

    /**
     * @param $view
     * @param array $data
     */
    protected function render($view, $data = [])
    {
        $path = $this->render->generatePathByController(static::class);
        $this->render->render($path,$view, $data);
    }
}