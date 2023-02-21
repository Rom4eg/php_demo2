<?php

/**
 * Model
 */

declare(strict_types=1);

namespace Rom4eg\PhpDemo2\Models;

use Rom4eg\PhpTools\Utils\DbAccess;

/**
 * Employee model
 *
 * @package PhpDemo2\Models
 */
class Employee extends AbstractModel
{
    const TABLE = "employee";

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
    public static function bulkSave(array $data): void
    {
        $fields = static::getFields();
        $marks = [];
        $values = [];
        foreach ($data as $row) {
            $data_row = [];
            $marks_row = [];
            foreach ($fields as $fld) {
                if (!array_key_exists($fld, $row) || empty($row[$fld])) {
                    $marks_row[$fld] = "DEFAULT";
                    continue;
                }

                $data_row[$fld] = $row[$fld];
                $marks_row[$fld] = "?";
            }
            $values[] = $data_row;
            $marks[] = $marks_row;
        }

        $marks_sql = implode(",", array_map(fn($x) => "(".implode(",", $x).")", $marks));
        $values_sql = array_reduce($values, fn($prev, $next) => array_merge($prev, array_values($next)), []);
        $fields_sql = implode(",", $fields);

        // var_dump($values_sql);die();
        $sql = "INSERT INTO ".static::TABLE."($fields_sql) VALUES $marks_sql";

        DbAccess::query($sql, $values_sql);
    }

    /**
     * Create table
     *
     * @return void
     */
    public static function createTable(): void
    {
        $q = "CREATE TABLE ".static::TABLE."(
            employee_id int unsigned PRIMARY KEY AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            job varchar(255) NOT NULL DEFAULT '',
            department_id int unsigned,
            employment enum('full', 'part') NOT NULL DEFAULT 'full',
            pay_type enum('salary', 'hourly') NOT NULL default 'salary',
            hours int unsigned NOT NULL DEFAULT 0,
            salary decimal(15,2) NOT NULL DEFAULT 0.00,
            hourly_rate decimal(10,2) NOT NULL DEFAULT 0.00,
            CONSTRAINT FOREIGN KEY (department_id) REFERENCES department(department_id) ON DELETE SET NULL
        ) ENGINE=InnoDb";

        DbAccess::query($q);
    }

    /**
     * Search employee by name
     *
     * @param string $name employee name
     *
     * @return array
     */
    public static function getByName(string $name): array
    {
        $name = "%$name%";
        $sql = "SELECT
                    e.employee_id,
                    e.name,
                    e.job,
                    d.department_id as department_id,
                    d.name as department,
                    e.employment,
                    e.pay_type,
                    e.hours,
                    e.salary,
                    e.hourly_rate
                FROM ".static::TABLE." as e
                LEFT JOIN ".Department::TABLE." as d USING(department_id)
                WHERE e.name like ?";

        $ret = DbAccess::fetch($sql, [$name]);

        if (empty($ret)) {
            $ret = [];
        }

        return $ret;
    }
}
