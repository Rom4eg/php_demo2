<?php

/**
 * Command
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Cli\Command;

use Exception;
use Rom4eg\PhpDemo2\Models\Employee;
use Rom4eg\PhpDemo2\Modules\Employee\Import;
use Rom4eg\PhpTools\Utils\ArrayUtils;
use Rom4eg\PhpTools\Utils\Cli\Interfaces\ICommand;

/**
 * Retrieve data about employee
 *
 * @package Rom4eg\PhpTools\Utils\Cli\Command
 */
final class EmployeeCommand extends AbstractCommand implements ICommand
{
    /**
     * Alias to use in console
     *
     * @var string
     */
    const ALIAS = "emp";

    /**
     * Command description.
     * Shows in help message
     *
     * @vart string
     */
    const DESCRIPTION = "Employee info";

    /**
     * Long options for getopt() function
     *
     * @var array
     */
    protected array $long_opt = ["name:"];

    /**
     * Print command usage
     *
     * @return void
     */
    public function help(): void
    {
        echo "Employee info".PHP_EOL;
        echo PHP_EOL."Usage: demo ".static::getAlias()." --name=<name> [args]".PHP_EOL;

        echo PHP_EOL."Options: ".PHP_EOL;
        echo str_pad("\t--name=<employee name>", 25)."Full or partial employee name. Example: JASON M".PHP_EOL;
    }

    /**
     * Execute command
     *
     * @return void
     */
    public function execute(): void
    {
        $name = $this->getOption("name", '');
        if (empty($name)) {
            $this->help();
            return;
        }

        $empl = Employee::getByName($name);
        if (empty($empl)) {
            echo "Not found".PHP_EOL;
            return;
        }

        echo ArrayUtils::jsonSerialize($empl, true);
    }
}
