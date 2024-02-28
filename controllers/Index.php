<?php

namespace controllers;

use classes\Core;
use classes\DataBase;
use models\Groups;

class Index
{
    public static function Init(): void
    {
        $GroupsModel = Core::getModel('Groups');
        $ProductsModel = Core::getModel('Products');

        $groups = [];
        $current_group_id = 0;

        if (isset($_GET['group'])) {
            if (empty($current_group = $GroupsModel->getGroupsById((int) $_GET['group'])))
                die('404');
            else
                $current_group_id = current($current_group)['id'];
        }

        /*
            Берем только те категории, что будут видны.
         */
        // Вложенность категорий.
        if ($current_group_id !== 0) {
            $groups = $GroupsModel->getParentsGroupByGroupId($current_group_id);
            foreach ($groups as $key => $group) {
                $groups[$key]['active'] = true;
            }
            $groups = array_replace($GroupsModel->getGroupsByParentId(array_keys($groups)), $groups);
        }
        // Главный категории.
        $groups = array_replace($GroupsModel->getGroupsByParentId(0), $groups);

        /*
            Я бы это на крон повесил, а в базе добавил бы столбец.
            Если база большая, то такое решение нагружать сильно будет.
        */
        // Считаем количество товаров в каждой категории.
        foreach ($groups as $k => $group) {
            $groups[$k]['products_count'] = $GroupsModel->getCountProducts($group);
        }

        $groups_parent_id = [];
        foreach ($groups as $group) {
            $groups_parent_id[$group['id_parent']][] = $group;
        }

        // Продукты.
        $children_groups = $GroupsModel->getChildrenGroupByGroupId($current_group_id);
        $children_groups_ids = array_keys($children_groups);
        $children_groups_ids[] = $current_group_id;
        $products = $ProductsModel->getProductsByGroupId($children_groups_ids, 'name');

        echo Core::getTemplate('index', [
            'groups' => $groups_parent_id,
            'products' => $products
        ]);
    }
}