<?php

/**
 * Logger types
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger\Interfaces;

/**
 * Logger engine enum
 *
 * @package PhpTools\Logger\Interfaces
 */
interface IEngine
{
    /**
     * Logger print to stdout
     *
     * @var string
     */
    const STDOUT = "std";

    /**
     * Logger print to file
     *
     * @var string
     */
    const FILE = "file";
}
