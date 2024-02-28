<?php

namespace models;

use classes\Core;
use classes\DataBase;

class Groups extends DataBase
{
    private string $table = 'groups';
    public array $count_products = [];

    public function getGroupsByParentId(int|array $ids = 0, string|array $fields = '*'): array
    {
        return self::Select($this->table, $fields, ['id_parent' => $ids]);
    }

    public function getGroupsById(int|array $ids, string|array $fields = '*'): array
    {
        return self::Select($this->table, $fields, ['id' => $ids]);
    }

    public function getParentsGroupByGroupId(int $id, string|array $fields = '*'): array
    {
        $groups = [];
        $group = self::Select($this->table, $fields, ['id' => $id]);
        if (!empty($group)) {
            $group = current($group);
            $groups[$group['id']] = $group;
            $groups = array_replace($groups, self::getParentsGroupByGroupId($group['id_parent']));
        }

        return $groups;
    }

    public function getChildrenGroupByGroupId(int $id, string|array $fields = '*'): array
    {
        $result = [];
        $groups = self::Select($this->table, $fields, ['id_parent' => $id]);
        if (!empty($groups)) {
            foreach ($groups as $key => $group) {
                $result[$key] = $group;
                $result = array_replace($result, self::getChildrenGroupByGroupId($key));
            }
        }

        return $result;
    }

    public function getCountProducts(array $group): int
    {
        $count = 0;
        if (!isset($this->count_products[$group['id']])) {
            $parents = $this->getGroupsByParentId($group['id'], ['id', 'id_parent']);

            $ProductsModel = Core::getModel('Products');
            $products = $ProductsModel->getProductsByGroupId($group['id'], 'id');
            if (!empty($products))
                $count += count($products);

            if (!empty($parents)) {
                foreach ($parents as $parent) {
                    $count += $this->getCountProducts($parent);
                }
            }
        } else {
            $count = $this->count_products[$group['id']];
        }


        $this->count_products[$group['id']] = $count;
        return $count;
    }
}