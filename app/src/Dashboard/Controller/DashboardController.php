<?php

namespace Dashboard\Controller;

use Dashboard\Model\DashboardModel;
use System\Core\AbstractController;

class DashboardController extends AbstractController
{
    public function index()
    {
        $model = new DashboardModel();
        $products = $model->findProducts();

        $this->render('dashboard.html.twig', [
            'title'     => 'Dashboard',
            'products'  => $products,
            'total'     => count($products)
        ]);
    }
}