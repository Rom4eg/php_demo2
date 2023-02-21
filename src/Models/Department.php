<?php

/**
 * Model
 */

declare(strict_types=1);

namespace Rom4eg\PhpDemo2\Models;

use Rom4eg\PhpTools\Utils\DbAccess;

/**
 * Department model
 *
 * @package PhpDemo2\Models
 */
class Department extends AbstractModel
{
    const TABLE = "department";

    /**
     * Если описываем модель для ORM
     * тогда здесь должно быть описание поле
     * с типами, ключами и пр.
     *
     * Я не использую ORM, поэтому пропущу
     */

    /**
     * Bulk save many items in one query
     *
     * @param array $data array of rows e.g. [["filed_name" => 1], ["filed_name" => 2]]
     *
     * @return void
     */

    /**
     * Create table
     *
     * @return void
     */
    public static function createTable(): void
    {
        $q = "CREATE TABLE ".static::TABLE."(
                department_id int unsigned PRIMARY KEY AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                alias varchar(255) NOT NULL,
                UNIQUE KEY (alias)
            ) ENGINE=InnoDb";

        DbAccess::query($q);
    }

    /**
     * Create list of departmens
     *
     * @param array $names list of deprtment names
     *
     * @return void
     */
    public static function createBulk(array $names): void
    {
        if (empty($names)) {
            return ;
        }

        $data = [];
        foreach ($names as $name) {
            $alias = static::generateAlias($name);
            $insert_data = [
                "name" => $name,
                "alias" => $alias
            ];

            $data[] = $insert_data;
        }

        $fields = array_keys($data[0]);

        $marks = [];
        $values = [];
        foreach ($data as $row) {
            $_row_marks = implode(",", array_fill(0, count($row), "?"));
            $marks[] = "($_row_marks)";
            $values = array_merge($values, array_values($row));
        }

        $marks_sql = implode(",", $marks);
        $q = "INSERT INTO ".static::TABLE."(".implode(',', $fields).") VALUES $marks_sql";

        DbAccess::query($q, $values);
    }

    /**
     * Generate department alias from it's name
     *
     * @param string $name department name
     *
     * @return string
     */
    public static function generateAlias(string $name): string
    {
        $_alias_name = strtolower($name);
        $alias = str_replace(["&", " ", "'", "-"], ["", "_", "", "_"], $_alias_name);
        $alias = str_replace("__", "_", $alias);
        return $alias;
    }

    public static function filterByName($name): array
    {
        if (!is_array($name)) {
            $name = [$name];
        }

        $values = array_map(fn($x) => static::generateAlias($x), $name);
        $marks = implode(",", array_fill(0, count($name), "?"));
        $q = "SELECT * FROM ".static::TABLE." WHERE alias in ($marks) GROUP BY alias";
        $ret = DbAccess::fetch($q, $values);
        if (empty($ret)) {
            $ret = [];
        }

        return $ret;
    }
}
