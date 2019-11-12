<?php

namespace Product\Controller;

use System\Core\AbstractController;

class ProductController extends AbstractController
{
    public function list()
    {
        $this->render('list.html.twig', ['title' => 'Products']);
    }

    public function new()
    {
        $this->render('new.html.twig', ['title' => 'Add Product ']);
    }
}