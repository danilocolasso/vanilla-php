<?php

namespace Product\Model;

use Category\Model\CategoryModel;
use System\Core\AbstractModel;

class ProductModel extends AbstractModel
{
    /**
     * ProductModel constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'product';
    }

    /**
     * Select all products with their categories
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function findProducts($id = null)
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select([
                'product.id',
                'product.name',
                'product.sku',
                'product.description',
                'product.quantity',
                'product.price',
                'category.name AS category'
            ])
            ->leftJoin(
                'product_category',
                'product_category.product_id = product.id'
            )
            ->leftJoin(
                'category',
                'product_category.category_id = category.id'
            );

        $products = $this->db->query($qb->getQuery())->fetchAll();

        $data = [];
        foreach($products as $product) {
            if(!isset($data[$product['id']])) {
                $data[$product['id']] = $product;
                $data[$product['id']]['categories'] = [];
                unset($data[$product['id']]['category']);
            }

            $data[$product['id']]['categories'][] = $product['category'];
        }

        return $data;
    }

    /**
     * Find One Product with categories
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function findProduct($id)
    {
        $product = $this->find($id);
        $product['categories'] = array_column(
            (new CategoryModel())->findByProduct($id),
            'id'
        );

        return $product;
    }

    /**
     * Insert a new product and link it to category
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function insert($data)
    {
        $qb = $this->createQueryBuilder();

        $categories = $data['category'];
        unset($data['category']);

        $data['created_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        $query = $qb->insert(array_keys($data))->getQuery();
        $this->db->prepare($query);
        $this->db->execute(array_values($data));
        $qb->flush();

        $productId = $this->db->lastInsertId();

        //Inset Product Category
        $productCategoryModel = new ProductCategoryModel();
        foreach($categories as $category) {
            $productCategoryModel->insert([
                'product_id'    => $productId,
                'category_id'   => $category,
                'created_at'    => $data['created_at']
            ]);
        }

        return $productId;
    }

    /**
     * Update Product
     * @param $data
     * @throws \Exception
     * @return bool
     */
    public function update($data)
    {
        $qb = $this->createQueryBuilder();

        $categories = array_column(
            (new CategoryModel())->findByProduct($data['id']),
            'id'
        );

        $productCategoryModel = new ProductCategoryModel();
        foreach($categories as $categoryId) {
            $productCategoryModel->delete($data['id'], $categoryId);
        }

        $categories = $data['category'];
        unset($data['category']);

        $columns = $data;
        unset($columns['id']);

        $query = $qb
            ->update(array_keys($columns))
            ->where('id = ?')
            ->getQuery()
        ;
        $this->db->prepare($query);

        //Id at last array position
        $id = $data['id'];
        unset($data['id']);
        $data['id'] = $id;

        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');

        //Inset Product Category
        foreach($categories as $category) {
            $productCategoryModel->insert([
                'product_id'    => $data['id'],
                'category_id'   => $category,
                'created_at'    => $createdAt
            ]);
        }

        return $this->db->execute(array_values($data));
    }

    /**
     * Delete Product
     * @param $id
     * @throws \Exception
     * @return bool
     */
    public function delete($id)
    {
        $qb = $this->createQueryBuilder();

        $query = $qb
            ->delete()
            ->where('id = :id')
            ->getQuery()
        ;

        (new ProductCategoryModel())->deleteByProduct($id);

        return $this->db->prepare($query)->execute([':id' => $id]);
    }
}