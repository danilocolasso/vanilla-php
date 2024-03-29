<?php

namespace System\Render;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Use twig to render templates
 * Class TwigRender
 * @package System\Render
 */
class TwigRender implements RenderInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $routes;

    /**
     * @return Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * TwigRender constructor.
     * @param array $routes
     */
    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @inheritDoc
     */
    public function render($path, $view, $data)
    {
        $paths = [
            'template'  => ['resources', 'views'],
            'cache'     => ['cache', 'twig']
        ];

        foreach($paths as $key => $value) {
            $paths[$key] = implode(DIRECTORY_SEPARATOR, $value);
        }

        //TODO create 404 page

        $loader     = new FilesystemLoader([$path, $paths['template']], getcwd());
        $this->twig = new Environment($loader);
        //$twig   = new Environment($loader, ['cache' => $paths['cache']]);

        $this->createTemplateFunctions();

        echo $this->twig->render($view, $data);
    }

    /**
     * Create some functions to use in twig templates
     * Functions: asset, path
     */
    public function createTemplateFunctions()
    {
        $this->twig->addFunction(new TwigFunction('asset', function ($asset) {
            return sprintf('/resources/assets/%s', ltrim($asset, '/'));
        }));

        $this->twig->addFunction(
            new TwigFunction('route', function ($path, $params = []) {
                $route = $this->routes[$path]['path'];

                foreach($params as $key => $value) {
                    $route = str_replace('{' . $key . '}', $value, $route);
                }

                return $route;
            })
        );

        $this->twig->addFilter(new TwigFilter('truncate',
            function($text, $max = 50, $ellipsis = '...') {
                return strlen($text) <= $max
                    ? $text
                    : substr($text, 0, $max - strlen($ellipsis)) . $ellipsis
                ;
            }
        ));
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