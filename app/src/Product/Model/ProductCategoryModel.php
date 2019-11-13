<?php

namespace Product\Model;

use System\Core\AbstractModel;

class ProductCategoryModel extends AbstractModel
{
    /**
     * ProductCategoryModel constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'product_category';
    }

    /**
     * Insert into Product Category table
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function insert($data)
    {
        $qb = $this->createQueryBuilder();

        $query = $qb->insert(array_keys($data))->getQuery();
        $this->db->prepare($query);
        $this->db->execute(array_values($data));

        return $this->db->lastInsertId();
    }

    /**
     * Delete from Product Category table
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function delete($product, $category)
    {
        $qb = $this->createQueryBuilder();

        $query = $qb
            ->delete()
            ->where('product_id = :product')
            ->andWhere('category_id = :category')
            ->getQuery()
        ;

        return $this->db->prepare($query)->execute([
            ':product'  => $product,
            ':category' => $category
        ]);
    }

    /**
     * Delete all by Product
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleteByProduct($id)
    {
        $qb = $this->createQueryBuilder();

        $query = $qb
            ->delete()
            ->where('product_id = :product')
            ->getQuery()
        ;

        return $this->db->prepare($query)->execute([':product'  => $id]);
    }

    /**
     * Delete all by Product
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleteByCategory($id)
    {
        $qb = $this->createQueryBuilder();

        $query = $qb
            ->delete()
            ->where('category_id = :category')
            ->getQuery()
        ;

        return $this->db->prepare($query)->execute([':category'  => $id]);
    }
}