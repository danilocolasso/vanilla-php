<?php

namespace Category\Controller;

use System\Core\AbstractController;

class CategoryController extends AbstractController
{
    public function list($request)
    {
        $this->render('list.html.twig', ['title' => 'Categories']);
    }

    public function new()
    {
        $this->render('new.html.twig', ['title' => 'Add Category']);
    }
}