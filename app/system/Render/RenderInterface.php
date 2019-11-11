<?php

namespace System\Render;

/**
 * Interface RenderInterface
 * @package System\Render
 */
interface RenderInterface
{
    /**
     * Render a template
     * @param string $path Template Path
     * @param string $view View name
     * @param array $data Data to send to view
     * @return mixed
     */
    public function render($path, $view, $data);
}