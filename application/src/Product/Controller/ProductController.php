<?php

namespace Product\Controller;

use Category\Model\CategoryModel;
use Product\Model\ProductModel;
use System\Core\AbstractController;
use System\Http\Request\RequestInterface;

class ProductController extends AbstractController
{
    /**
     * Product list
     * @throws \Exception
     */
    public function index()
    {
        $products = (new ProductModel())->findProducts();

        $this->render('index.html.twig', [
            'title'     => 'Products',
            'products'  => $products
        ]);
    }

    /**
     * Product add
     * @param RequestInterface $request
     * @throws \Exception
     */
    public function create(RequestInterface $request)
    {
        $categories = (new CategoryModel())->findAll();

        if($request->getMethod() == 'POST') {
            (new ProductModel())->insert($request->getBody());
            return $this->redirect('product');
        }

        $this->render('edit.html.twig', [
            'title'     => 'Add Product',
            'categories'=> $categories
        ]);
    }

    /**
     * Product Update
     * @param RequestInterface $request
     * @throws \Exception
     */
    public function update(RequestInterface $request)
    {
        $id = $request->getParam('id');

        $product    = (new ProductModel())->findProduct($id);
        $categories = (new CategoryModel())->findAll();

        if($request->getMethod() == 'POST') {
            (new ProductModel())->update($request->getBody());
            return $this->redirect('product');
        }

        $this->render('edit.html.twig', [
            'title'     => 'Edit Product',
            'product'   => $product,
            'categories'=> $categories
        ]);
    }

    /**
     * Product Delete
     * @param RequestInterface $request
     * @throws \Exception
     */
    public function delete(RequestInterface $request)
    {
        (new ProductModel())->delete($request->getParam('id'));
        return $this->redirect('product');
    }
}