<?php

/**
 * Logger engine
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger\Engine;

use Rom4eg\PhpTools\Utils\Logger\Interfaces\IEngine;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILoggerEngine;
use Rom4eg\PhpTools\Utils\ArrayUtils;
use Throwable;

/**
 * Log to stdout
 *
 * @package PhpTools\Logger\Engine
 */
class StdEngine extends AbstractEngine implements ILoggerEngine
{
    /**
     * Engine type
     *
     * @var string
     */
    const TYPE = IEngine::STDOUT;

    /**
     * Progress out
     *
     * @var bool
     */
    protected bool $in_progress = false;

    /**
     * Last progress status
     *
     * @var string
     */
    protected string $last_progress = '';

    /**
     * Symbol for Cariage Return
     *
     * @var string
     */
    private static string $_symbol_cr = "";

    /**
     * Symbol for End of Line
     *
     * @var string
     */
    private static string $_symbol_eol = "";

    /**
     * Error label box color
     * Line must include control sequences if required
     *
     * @var string
     */
    private static string $_symbol_c_err = "";

    /**
     * Warning label box color
     * Line must include control sequences if required
     *
     * @var string
     */
    private static string $_symbol_c_warn = "";

    /**
     * Success label box color
     * Line must include control sequences if required
     *
     * @var string
     */
    private static string $_symbol_c_success = "";

    /**
     * Info label box color
     * Line must include control sequences if required
     *
     * @var string
     */
    private static string $_symbol_c_info = "";

    /**
     * Deprecated info box color
     * Line must include control sequences if required
     *
     * @var string
     */
    private static string $_symbol_c_deprecated = "";

    /**
     * Control sequence to reset all to system defaults
     *
     * @var string
     */
    private static string $_symbol_reset = "";

    /**
     * Error label box text
     *
     * @var string
     */
    private static string $_label_err = "";

    /**
     * Warning label box text
     *
     * @var string
     */
    private static string $_label_warn = "";

    /**
     * Success label box text
     *
     * @var string
     */
    private static string $_label_success = "";

    /**
     * Info label box text
     *
     * @var string
     */
    private static string $_label_info = "";

    /**
     * Deprecated label box text
     *
     * @var string
     */
    private static string $_label_deprecated = "";

    /**
     * Constructor
     *
     * @param array $cfg logger config
     */
    public function __construct(array $cfg = [])
    {
        static::$_symbol_cr = ArrayUtils::path(static::getType().".symbol_cr", $cfg, "\033[2K\r");
        static::$_symbol_eol = ArrayUtils::path(static::getType().".symbol_eol", $cfg, PHP_EOL);
        static::$_symbol_reset = ArrayUtils::path(static::getType().".symbol_reset", $cfg, "\033[0m");
        static::$_symbol_c_err = ArrayUtils::path(static::getType().".color_error", $cfg, "\033[0;41;37m");
        static::$_symbol_c_warn = ArrayUtils::path(static::getType().".color_warning", $cfg, "\033[0;43;37m");
        static::$_symbol_c_success = ArrayUtils::path(static::getType().".color_success", $cfg, "\033[0;42;37m");
        static::$_symbol_c_info = ArrayUtils::path(static::getType().".color_info", $cfg, "\033[0;44;37m");
        static::$_symbol_c_deprecated = ArrayUtils::path(static::getType().".color_deprecated", $cfg, "\033[0;47;37m");
        static::$_label_err = ArrayUtils::path(static::getType().".label_error", $cfg, "error");
        static::$_label_warn = ArrayUtils::path(static::getType().".label_warning", $cfg, "warning");
        static::$_label_success = ArrayUtils::path(static::getType().".label_success", $cfg, "success");
        static::$_label_info = ArrayUtils::path(static::getType().".label_info", $cfg, "info");
        static::$_label_deprecated = ArrayUtils::path(static::getType().".label_deprecated", $cfg, "DEPRECATED");
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
        if ($this->in_progress) {
            printf("%s", static::$_symbol_cr);
        }

        printf("%s%s", $msg, static::$_symbol_eol);

        if ($this->in_progress) {
            $this->progress($this->last_progress);
        }
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
        $_msg = sprintf(
            "%s %s %s %s",
            static::$_symbol_c_err,
            static::$_label_err,
            static::$_symbol_reset,
            $msg,
        );
        if (empty(static::$_symbol_c_err) || empty(static::$_label_err)) {
            $_msg = sprintf(
                "%s%s",
                static::$_symbol_reset,
                $msg,
            );
        }
        $this->log($_msg);
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
        $_msg = sprintf(
            "%s %s %s %s",
            static::$_symbol_c_warn,
            static::$_label_warn,
            static::$_symbol_reset,
            $msg,
        );
        if (empty(static::$_symbol_c_warn) || empty(static::$_label_warn)) {
            $_msg = sprintf(
                "%s%s",
                static::$_symbol_reset,
                $msg,
            );
        }
        $this->log($_msg);
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
        $_msg = sprintf(
            "%s %s %s %s",
            static::$_symbol_c_info,
            static::$_label_info,
            static::$_symbol_reset,
            $msg,
        );
        if (empty(static::$_symbol_c_info) || empty(static::$_label_info)) {
            $_msg = sprintf(
                "%s%s",
                static::$_symbol_reset,
                $msg,
            );
        }
        $this->log($_msg);
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
        $_msg = sprintf(
            "%s %s %s %s",
            static::$_symbol_c_success,
            static::$_label_success,
            static::$_symbol_reset,
            $msg,
        );
        if (empty(static::$_symbol_c_success) || empty(static::$_label_success)) {
            $_msg = sprintf(
                "%s%s",
                static::$_symbol_reset,
                $msg,
            );
        }
        $this->log($_msg);
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
        $title = sprintf("DEBUG in %s%s", $caller, static::$_symbol_eol);
        $_msg = sprintf(
            "%sMessage: %s%sDebug data:%s%s%s",
            $title,
            $msg,
            static::$_symbol_eol,
            static::$_symbol_eol,
            $data_str,
            static::$_symbol_eol,
        );

        $this->log($_msg);
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
        $_msg = sprintf(
            "%s %s %s %s",
            static::$_symbol_c_deprecated,
            static::$_label_deprecated,
            static::$_symbol_reset,
            $msg,
        );
        if (empty(static::$_symbol_c_deprecated) || empty(static::$_label_deprecated)) {
            $_msg = sprintf(
                "%s%s",
                static::$_symbol_reset,
                $msg,
            );
        }
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
        if (!$this->in_progress) {
            printf("%s", static::$_symbol_eol);
        }
        $this->in_progress = true;
        $this->last_progress = $msg;
        printf("%s%s", static::$_symbol_cr, $msg);
    }

    /**
     * Finalize progress.
     * Print latest progress status and release brogress bar.
     *
     * @return void
     */
    public function stopProgress(): void
    {
        $this->in_progress = false;
        $this->last_progress = '';
        printf("%s%s", static::$_symbol_eol, static::$_symbol_eol);
    }

    /**
     * Get path to the current logger location
     *
     * @return string
     */
    public function getLogPath(): string
    {
        return "stdout";
    }
}
