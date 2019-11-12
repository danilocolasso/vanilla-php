<?php

namespace Dashboard\Controller;

use System\Core\AbstractController;

class DashboardController extends AbstractController
{
    public function index()
    {
        $this->render('dashboard.html.twig', ['title' => 'Dashboard']);
    }
}