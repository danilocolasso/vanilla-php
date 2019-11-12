<?php

namespace Category\Controller;

use System\Core\AbstractController;

class CategoryController extends AbstractController
{
    public function list($request)
    {
        $this->render('list.html.twig', ['name' => 'test']);
    }

    public function new()
    {
        $this->render('new.html.twig', ['name' => 'test']);
    }
}