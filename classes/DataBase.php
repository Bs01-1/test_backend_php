<?php

namespace classes;

class DataBase
{

    private static string $host = '';
    private static string $username = '';
    private static string $password = '';
    private static string $dbname = '';
    private static ?\PDO $PDO = null;
    public static function Init(): void
    {
        try {
            self::$PDO = new \PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbname, self::$username, self::$password);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function Select(string $table, string|array $select = '*', array|null $where = null): array
    {
        $sql = 'SELECT {select} FROM `'.$table.'` {where}';

        // Заполняем where
        $where_sql = '';
        if (!empty($where)) {
            foreach ($where as $k => $values) {
                // Добавляем OR или AND
                if (preg_match('/^(OR|AND):(.*)/', $k, $matches)) {
                    list(,$splitter, $new_key) = $matches;
                    unset($where[$k]);
                    $k = $new_key;
                    $where[$k] = $values;
                    if ($where_sql !== '')
                        $where_sql .= ' '.$splitter.' ';
                } else if ($where_sql !== '')
                    $where_sql .= ' AND ';

                // Строим IN
                if (is_array($values)) {
                    $where_sql .= $k. ' IN( {in} )';

                    $i = 0;
                    $in_sql = [];
                    foreach ($values as $item) {
                        $in_sql[$k.$i++] = $item;
                    }
                    unset($where[$k]);
                    $where_sql = str_replace('{in}', ':'.implode(',:', array_keys($in_sql)), $where_sql);
                    $where = array_merge($where, $in_sql);
                } else {
                    $where_sql .= $k. '=:' . $k;
                }
            }
            $where_sql = ' WHERE '.$where_sql;
        }
        $sql = str_replace('{where}', $where_sql, $sql);

        // Заполняем select
        $select_str = $select;
        if (is_array($select))
            $select_str = implode(',', $select);
        $sql = str_replace('{select}', $select_str, $sql);

        $request = self::$PDO->prepare($sql);
        $request->execute($where);

        $result = [];
        while ($row = $request->fetch()) {
            if (isset($row['id']))
                $result[$row['id']] = $row;
            else
                $result[] = $row;
        }

        return $result;
    }
}