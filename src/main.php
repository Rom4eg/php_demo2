<?php

/**
 * Entry point
 */

declare(strict_types=1);

use Rom4eg\PhpDemo2\Models\Employee;
use Rom4eg\PhpTools\Utils\ArrayUtils;
use Rom4eg\PhpTools\Utils\RequestUtils;
use Rom4eg\PhpTools\Utils\ServerUtils;

require_once __DIR__."/../vendor/autoload.php";

if (php_sapi_name() == "cli") {
    throw new Exception("Not allowed for cli usage");
}

/**
 * Тут должен быть вызов Application, конфиги, маршрутизация
 * и прочие удобности, но в рамках тестового задания - это не делалось
 */

$uri = ServerUtils::get("REQUEST_URI", "/");
if (preg_match("~api\/employee~", $uri)) {
    $name = RequestUtils::get("name", "");
    if (empty($name)) {
        http_response_code(400);
        return ;
    }

    $empl = Employee::getByName($name);
    if (empty($empl)) {
        http_response_code(404);
    }

    echo ArrayUtils::jsonSerialize($empl, true);
    return;
}

http_response_code(404);
