<?php

namespace System\Render;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Use twig to render templates
 * Class TwigRender
 * @package System\Render
 */
class TwigRender implements RenderInterface
{
    /**
     * @inheritDoc
     */
    public function render($path, $view, $data)
    {
        $loader = new FilesystemLoader($path);
        $twig = new Environment($loader, ['cache' => __DIR__ . '/cache/twig']);

        echo $twig->render($view, $data);
    }

    /**
     * Generate the template path based on Controller dir
     * @param string $controllerPath
     * @return string
     */
    public function generatePathByController(string $controllerPath)
    {
        $controllerPath = explode('\\', $controllerPath);
        array_pop($controllerPath);
        array_pop($controllerPath);

       $path = array_merge(
           [getcwd(), 'src'],
           $controllerPath,
           ['Resources', 'views', null]
       );

        return implode(DIRECTORY_SEPARATOR, $path);
    }
}