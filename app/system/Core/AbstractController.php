<?php

namespace System\Core;

use System\Render\TwigRender;

/**
 * Class AbstractController
 * @package System\Core
 */
class AbstractController
{
    protected $render;

    /**
     * AbstractController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $parameters = yaml_parse_file(
            __DIR__ . '../../../config/parameters.yml'
        );

        switch ($parameters['render']) {
            case 'twig':$this->render = new TwigRender();
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