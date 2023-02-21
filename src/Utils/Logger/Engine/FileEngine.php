<?php

/**
 * Logger engine
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger\Engine;

use Exception;
use Rom4eg\PhpTools\Utils\File\File;
use Rom4eg\PhpTools\Utils\File\Interfaces\IFileWriter;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\IEngine;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILoggerEngine;
use Rom4eg\PhpTools\Utils\ArrayUtils;
use Rom4eg\PhpTools\Utils\FsUtils;
use Throwable;

/**
 * Log to file
 *
 * @package PhpTools\Logger\Engine
 */
class FileEngine extends AbstractEngine implements ILoggerEngine
{
    /**
     * Logger type
     *
     * @var string
     */
    const TYPE = IEngine::FILE;

    /**
     * Log datetime format.
     * https://www.php.net/manual/en/datetime.format.php
     *
     * @var string
     */
    protected static string $time_format = "";

    /**
     * Logs dir
     *
     * @var string
     */
    protected static string $logs_dir = "";

    /**
     * File writer
     *
     * @var IFileWriter
     */
    private IFileWriter $_writer;

    /**
     * Lable for error message
     *
     * @var string
     */
    private static string $_label_error = "";

    /**
     * Lable for warning message
     *
     * @var string
     */
    private static string $_label_warning = "";

    /**
     * Lable for success message
     *
     * @var string
     */
    private static string $_label_success = "";

    /**
     * Lable for info message
     *
     * @var string
     */
    private static string $_label_info = "";

    /**
     * Label for deprecated message
     *
     * @var string
     */
    private static string $_label_deprecated = "";

    /**
     * Add prefix to each file name
     *
     * @var string
     */
    private static string $_file_prefix = "";

    /**
     * Current log file
     *
     * @var string
     */
    private string $_log_file = "";

    /**
     * Constructor
     *
     * @param array $cfg logger configuration
     */
    public function __construct(array $cfg = [])
    {
        static::$time_format = ArrayUtils::path(static::getType().".time_format", $cfg, "Y-m-d H:i:s P");
        static::$logs_dir = ArrayUtils::path(static::getType().".dir", $cfg, "");
        static::$_label_error = ArrayUtils::path(static::getType().".label_error", $cfg, "Error");
        static::$_label_warning = ArrayUtils::path(static::getType().".label_warning", $cfg, "Warning");
        static::$_label_success = ArrayUtils::path(static::getType().".label_success", $cfg, "Success");
        static::$_label_info = ArrayUtils::path(static::getType().".label_info", $cfg, "Info");
        static::$_label_deprecated = ArrayUtils::path(static::getType().".label_deprecated", $cfg, "DEPRECATED");
        static::$_file_prefix = ArrayUtils::path(static::getType().".prefix", $cfg, "");


        if (empty(static::$logs_dir)) {
            throw new Exception("In order to use the file logger, you must set the logs directory.");
        }

        $this->_log_file = sprintf(
            "%s%s%s%s.log",
            static::$logs_dir,
            DIRECTORY_SEPARATOR,
            (static::$_file_prefix),
            date("Y_m_d")
        );
        $this->_writer = File::writeTo($this->_log_file, IFileWriter::MODE_APPEND);
    }

    /**
     * Destructor
     * Close opened file
     */
    public function __destruct()
    {
        $this->_writer->finalize();
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
        $msg = sprintf("[%s] %s%s", date(static::$time_format), $msg, PHP_EOL);
        $this->_writer->write($msg);
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
        if (!empty(static::$_label_error)) {
            $msg = sprintf("%s: %s", static::$_label_error, $msg);
        }
        $this->log($msg);
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
        if (!empty(static::$_label_warning)) {
            $msg = sprintf("%s: %s", static::$_label_warning, $msg);
        }
        $this->log($msg);
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
        if (!empty(static::$_label_info)) {
            $msg = sprintf("%s: %s", static::$_label_info, $msg);
        }
        $this->log($msg);
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
        if (!empty(static::$_label_success)) {
            $msg = sprintf("%s: %s", static::$_label_success, $msg);
        }
        $this->log($msg);
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
        if (!empty(static::$_label_deprecated)) {
            $msg = sprintf("%s: %s", static::$_label_deprecated, $msg);
        }
        $this->log($msg);
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
        $data_str = ArrayUtils::jsonSerialize($data, true);
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $caller = "";
        if (count($backtrace) >= 2) {
            $file = ArrayUtils::path("1.file", $backtrace, "");
            $line = ArrayUtils::path("1.line", $backtrace, "");
            $caller = sprintf("%s:%s", $file, $line);
        }
        $title = sprintf("DEBUG in %s%s", $caller, PHP_EOL);
        $_msg = sprintf(
            "%sMessage: %s%sDebug data:%s%s%s",
            $title,
            $msg,
            PHP_EOL,
            PHP_EOL,
            $data_str,
            PHP_EOL,
        );

        $this->log($_msg);
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
        $msg = $this->_formatException($err);
        $this->error($msg);
    }

    /**
     * Recursive formatting of all errors.
     *
     * @param Throwable $err exception
     *
     * @return string
     */
    private function _formatException(Throwable $err): string
    {
        $pattern = "[%s] %s %s:%s\nTraceback:\n%s";
        $prev = $err->getPrevious();
        $code = $err->getCode();
        $text = $err->getMessage();
        $file = $err->getFile();
        $line = $err->getLine();
        $trace = $err->getTrace();
        $msg = sprintf($pattern, $code, $text, $file, $line, ArrayUtils::jsonSerialize($trace, true));

        if (!empty($prev)) {
            $msg .= PHP_EOL.PHP_EOL.$this->_formatException($prev);
        }

        return $msg;
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
        // progress cannot be writed to file
    }

    /**
     * Finalize progress.
     * Print latest progress status and release brogress bar
     *
     * @return void
     */
    public function stopProgress(): void
    {
        // progress cannot be writed to file
    }

    /**
     * Get path to the current logger location
     *
     * @return string
     */
    public function getLogPath(): string
    {
        return $this->_log_file;
    }
}
