## Usage

```php
use Rom4eg\PhpTools\Utils\Logger\LoggerFactory;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\IEngine;

$log = LoggerFactory::make(IEngine::STDOUT);

$log->info("Message with INFO badge");
$log->warning("Message with WARNING badge");
$log->error("Message with ERROR badge");
$log->success("Message with SUCCESS badge");
$log->log("Message without badge");

$invoice = [
    "invoice_id" => 12345,
    "title" => "Horns & Hoofs, Ltd.",
    "amount_usd" => 100
];
$log->debug("Debug message", $invoice);

// or log to file
$cfg = [
    "file" => [
        "dir" => "/path/to/logs/dir"
    ]
];
$log = LoggerFactory::make(IEngine::FILE, [$cfg]);
$log->setLevel(ILoggerLevel::WARNING); // logs only errors and warnings

$log->warning("This message will be logged.");
$log->error("This too.");
$log->info("And this one not.");
```
