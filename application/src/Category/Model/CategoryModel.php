<?php

namespace Category\Model;

use Product\Model\ProductCategoryModel;
use System\Core\AbstractModel;

class CategoryModel extends AbstractModel
{
    /**
     * CategoryModel constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'category';
    }

    /**
     * Find all Categories by Product
     * @param $id
     * @return array|false
     * @throws \Exception
     */
    public function findByProduct($id)
    {
        $qb = $this->createQueryBuilder();
        $qb
            ->select([
                'category.id',
                'category.name'
            ])
            ->innerJoin(
                'product_category',
                'product_category.category_id = category.id'
            )
            ->where('product_category.product_id = :id')
        ;

        $this->db->prepare($qb->getQuery())->execute([':id' => $id]);

        return $this->db->fetchAll();
    }

    /**
     * Insert a new Category
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function insert($data)
    {
        $qb = $this->createQueryBuilder();

        $data['created_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        $query = $qb->insert(array_keys($data))->getQuery();
        $this->db->prepare($query);
        $this->db->execute(array_values($data));
        $qb->flush();

        return $this->db->lastInsertId();
    }

    /**
     * Update Category
     * @param $data
     * @throws \Exception
     * @return bool
     */
    public function update($data)
    {
        $qb = $this->createQueryBuilder();

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

        return $this->db->execute(array_values($data));
    }

    /**
     * Delete Category
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

        (new ProductCategoryModel())->deleteByCategory($id);

        return $this->db->prepare($query)->execute([':id' => $id]);
    }
}