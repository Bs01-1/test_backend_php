<?php

namespace models;

use classes\DataBase;

class Products extends DataBase
{
    private string $table = 'products';

    public function getProductsByGroupId(int|array $ids, string|array $fields = '*'): array
    {
        return self::Select($this->table, $fields, ['id_group' => $ids]);
    }
}