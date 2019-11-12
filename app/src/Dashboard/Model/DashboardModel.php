<?php

namespace Dashboard\Model;

use System\Core\AbstractModel;

class DashboardModel extends AbstractModel
{
    public function findProducts()
    {
        $qb = $this->getQueryBuilder();

        $qb
            ->select([
                'product.id',
                'product.name',
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

    protected function getQueryBuilder()
    {
        return $this->createQueryBuilder('product');
    }
}