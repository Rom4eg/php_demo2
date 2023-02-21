<?php

/**
 * Employee import module
 */

declare(strict_types=1);

namespace Rom4eg\PhpDemo2\Modules\Employee;

use Exception;
use Rom4eg\PhpDemo2\Models\Department;
use Rom4eg\PhpDemo2\Models\Employee;
use Rom4eg\PhpTools\Utils\File\File;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\IEngine;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILogger;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILoggerLevel;
use Rom4eg\PhpTools\Utils\Logger\LoggerFactory;

/**
 * Import Employee module
 *
 * @package PhpDemo2\Modules\Employee
 */
final class Import
{
    /**
     * Path to file to import from
     *
     * @var string
     */
    protected string $target;

    /**
     * Logger singletone
     *
     * @var ILogger
     */
    protected ILogger $log;

    /**
     * Recreate tables if exists
     *
     * @var bool
     */
    protected bool $force_tables;

    /**
     * Input data format.
     * This is hardcoded because we only support one exact import format
     */
    protected $file_format = [
        "name",
        "job",
        "department",
        "employment",
        "pay_type",
        "hours",
        "salary",
        "hourly_rate"
    ];

    /**
     * Constructor
     *
     * @param sttring $file Path to file to import from
     * @param bool $force_tables Recreate tables if exists
     */
    public function __construct(string $file, bool $force_tables = false)
    {
        $this->target = $file;
        $this->force_tables = $force_tables;
    }

    /**
     * Run import
     *
     * @return void
     */
    public function startImport(): void
    {
        $full = realpath($this->target);
        if (empty($full)) {
            throw new Exception("Cannot access file $full");
        }

        $this->prepareSchema();
        $this->getLogger()->info("Импорт из файла $full");

        $this->processDepratments($full);
        $this->processEmployee($full);
    }

    /**
     * Prepare db tables
     *
     * @return void
     */
    protected function prepareSchema(): void
    {
        $this->getLogger()->info("Подготовка таблиц");

        if ($this->force_tables) {
            $this->getLogger()->info("Пересоздание таблиц");
            Employee::dropTable();
            Department::dropTable();

            Department::createTable();
            Employee::createTable();
        } else {
            $this->getLogger()->info("Создание таблиц");
            $dep_exists = Department::tableExists();
            $emp_exists = Employee::tableExists();

            if (!$dep_exists) {
                Department::createTable();
            }

            if (!$emp_exists) {
                Employee::createTable();
            }
        }
    }

    /**
     * Processing departments
     *
     * @param string $file path to file
     *
     * @return void
     */
    protected function processDepratments(string $file): void
    {
        $this->getLogger()->info("Обработка Department");
        $reader = File::readFrom($file);

        $dep_idx = array_search("department", $this->file_format);
        if (empty($dep_idx)) {
            throw new Exception("Corrupted format definition");
        }

        $deps = [];
        foreach ($reader->rowGenerator() as $idx => $row) {
            // skip first line
            if (empty($idx)) {
                continue;
            }

            $row = trim($row);
            if (empty($row)) {
                continue;
            }

            $parsed = str_getcsv($row);
            if (empty($parsed)) {
                continue;
            }

            $deps[] = $parsed[$dep_idx];
            if (!($idx % 1000)) {
                $deps = array_unique($deps);
            }
        }
        $deps = array_unique($deps);
        $this->getLogger()->info("Обработка Department - завершено, всего ".count($deps));

        Department::createBulk($deps);
    }

    /**
     * Employee processing
     *
     * @param string $file path to file
     *
     * @return void
     */
    protected function processEmployee(string $file): void
    {
        $this->getLogger()->info("Обработка Employee");
        $reader = File::readFrom($file);

        $data_list = [];
        $deps_map = [];

        $replace_values_func = function ($data_list) use ($deps_map) {
            $deps = array_map(fn($x) => $x["department"], $data_list);
            $deps_data = Department::filterByName($deps);
            foreach ($deps_data as $dep) {
                if (!array_key_exists($dep["department_id"], $deps_map)) {
                    $deps_map[$dep["alias"]] = $dep["department_id"];
                }
            }

            foreach ($data_list as &$_row) {
                $dep_alias = Department::generateAlias($_row["department"]);
                $dep_id = array_key_exists($dep_alias, $deps_map)? $deps_map[$dep_alias] : null;
                $_row["department_id"] = $dep_id;
                unset($_row["department"]);
                $_row["employment"] = strtolower($_row["employment"]) == "f"? "full" : "part";
                $_row["pay_type"] = strtolower($_row["pay_type"]) == "salary"? "salary" : "hourly";
            }

            return $data_list;
        };

        foreach ($reader->rowGenerator() as $idx => $row) {
            $data_row = [];

            if (empty($idx)) {
                continue;
            }

            $row = trim($row);
            if (empty($row)) {
                continue;
            }

            $parsed = str_getcsv($row);
            if (empty($parsed)) {
                continue;
            }

            $this->getLogger()->progress("Обработано Employee: $idx");

            foreach ($this->file_format as $idx => $field) {
                $data_row[$field] = $parsed[$idx];
            }
            $data_list[] = $data_row;

            if (count($data_list) >= 100) {
                $data_list = $replace_values_func($data_list);
                Employee::bulkSave($data_list);
                $data_list = [];
            }
        }

        $this->getLogger()->stopProgress();

        if (!empty($data_list)) {
            $data_list = $replace_values_func($data_list);
            Employee::bulkSave($data_list);
        }
    }

    /**
     * Get logger instance
     *
     * @return ILogger
     */
    protected function getLogger(): ILogger
    {
        if (empty($this->log)) {
            $log = LoggerFactory::make(IEngine::STDOUT);
            $log->setLevel(ILoggerLevel::DEBUG);
            $this->log = $log;
        }

        return $this->log;
    }
}
