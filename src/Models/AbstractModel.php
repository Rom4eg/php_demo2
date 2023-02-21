<?php

/**
 * Model
 */

declare(strict_types=1);

namespace Rom4eg\PhpDemo2\Models;

use Exception;
use Rom4eg\PhpTools\Utils\DbAccess;

/**
 * Abstract db model
 *
 * @package PhpDemo2\Models
 */
abstract class AbstractModel
{
    const TABLE = "undefined";

    /**
     * Если описываем модель для ORM
     * тогда здесь должно быть описание поле
     * с типами, ключами и пр.
     *
     * Я не использую ORM, поэтому пропущу
     */

    /**
     * Create table
     *
     * @return void
     */
    abstract public static function createTable(): void;

    /**
     * Get table fields
     *
     * @return array
     */
    public static function getFields(): array
    {
        $q="desc ".static::TABLE;
        $data = DbAccess::fetch($q);

        if (empty($data)) {
            throw new Exception("Something went wrong. Check that table ".static::TABLE." exists");
        }
        $fields = array_map(fn($x) => $x["Field"], $data);
        return $fields;
    }

    /**
     * Check if table exists
     *
     * @return bool
     */
    public static function tableExists(): bool
    {
        $q = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = ?";
        $data = DbAccess::fetch($q, [static::TABLE]);

        return !empty($data);
    }

    /**
     * Drop table
     *
     * @return void
     */
    public static function dropTable(): void
    {
        $q = "DROP TABLE IF EXISTS ".static::TABLE;
        DbAccess::query($q);
    }
}
