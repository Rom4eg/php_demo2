<?php

/**
 * Logger manager
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger;

use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILogger;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILoggerLevel;
use Throwable;

/**
 * Manager class
 *
 * @package PhpTools\Logger
 */
final class Logger implements ILogger
{
    /**
     * Engine instance
     *
     * @var ILogger
     */
    protected ILogger $engine;

    /**
     * Logger level
     *
     * @var int
     */
    protected int $level = ILoggerLevel::ERROR;

    /**
     * Constructor
     *
     * @param ILogger $engine logger engine
     */
    public function __construct(ILogger $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Print message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function log(string $msg): void
    {
        $this->engine->log($msg);
    }

    /**
     * Print error message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function error(string $msg): void
    {
        if ($this->level >= ILoggerLevel::ERROR) {
            $this->engine->error($msg);
        }
    }

    /**
     * Print warning message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function warning(string $msg): void
    {
        if ($this->level >= ILoggerLevel::WARNING) {
            $this->engine->warning($msg);
        }
    }

    /**
     * Print info message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function info(string $msg): void
    {
        if ($this->level >= ILoggerLevel::INFO) {
            $this->engine->info($msg);
        }
    }

    /**
     * Print success message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function success(string $msg): void
    {
        if ($this->level >= ILoggerLevel::INFO) {
            $this->engine->success($msg);
        }
    }

    /**
     * Print debug info
     *
     * @param string $msg message
     * @param array $data debug data
     *
     * @return void
     */
    public function debug(string $msg, array $data = []): void
    {
        if ($this->level >= ILoggerLevel::DEBUG) {
            $this->engine->debug($msg, $data);
        }
    }

    /**
     * Print deprecated message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function deprecated(string $msg): void
    {
        if ($this->level >= ILoggerLevel::INFO) {
            $this->engine->deprecated($msg);
        }
    }

    /**
     * Format and print exception
     *
     * @param Throwable $err exception
     *
     * @return void
     */
    public function exception(Throwable $err): void
    {
        $this->engine->exception($err);
    }

    /**
     * Print progress status.
     * This will print a progress bar which is always at the bottom.
     *
     * @param string $msg progress text/bar etc.
     *
     * @return void
     */
    public function progress(string $msg): void
    {
        $this->engine->progress($msg);
    }

    /**
     * Finalize progress.
     * Print latest progress status and release brogress bar
     *
     * @return void
     */
    public function stopProgress(): void
    {
        $this->engine->stopProgress();
    }

    /**
     * Setter.
     * Set logger level
     *
     * @param int $level logger level
     *
     * @return void
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * Get path to the current logger location
     *
     * @return string
     */
    public function getLogPath(): string
    {
        return $this->engine->getLogPath();
    }
}
