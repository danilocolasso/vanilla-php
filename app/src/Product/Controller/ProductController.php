<?php

namespace Product\Controller;

use System\Core\AbstractController;

class ProductController extends AbstractController
{
    public function list()
    {
        $this->render('list.html.twig', ['name' => 'test']);
    }
}