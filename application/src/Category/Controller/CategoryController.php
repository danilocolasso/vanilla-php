<?php

namespace Category\Controller;

use Category\Model\CategoryModel;
use System\Core\AbstractController;
use System\Http\Request\RequestInterface;

class CategoryController extends AbstractController
{
    /**
     * Category list
     * @throws \Exception
     */
    public function index()
    {
        $categories = (new CategoryModel())->findAll();

        $this->render('index.html.twig', [
            'title'     => 'Categories',
            'categories'=> $categories
        ]);
    }

    /**
     * Category add
     * @param RequestInterface $request
     * @throws \Exception
     */
    public function create(RequestInterface $request)
    {
        if($request->getMethod() == 'POST') {
            (new CategoryModel())->insert($request->getBody());
            return $this->redirect('category');
        }

        $this->render('edit.html.twig', [
            'title' => 'Add Category'
        ]);
    }

    /**
     * Category Update
     * @param RequestInterface $request
     * @throws \Exception
     */
    public function update(RequestInterface $request)
    {
        $id = $request->getParam('id');

        $category = (new CategoryModel())->find($id);

        if($request->getMethod() == 'POST') {
            (new CategoryModel())->update($request->getBody());
            return $this->redirect('category');
        }

        $this->render('edit.html.twig', [
            'title'     => 'Edit Category',
            'category'  => $category
        ]);
    }

    /**
     * Category Delete
     * @param RequestInterface $request
     * @throws \Exception
     */
    public function delete(RequestInterface $request)
    {
        (new CategoryModel())->delete($request->getParam('id'));
        return $this->redirect('category');
    }
}