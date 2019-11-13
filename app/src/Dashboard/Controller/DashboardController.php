<?php

namespace Dashboard\Controller;

use Product\Model\ProductModel;
use System\Core\AbstractController;

class DashboardController extends AbstractController
{
    public function index()
    {
        $products = (new ProductModel())->findProducts();

        $this->render('dashboard.html.twig', [
            'title'     => 'Dashboard',
            'products'  => $products,
            'total'     => count($products)
        ]);
    }
}