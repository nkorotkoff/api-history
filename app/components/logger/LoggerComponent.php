<?php


namespace app\components\logger;


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;

class LoggerComponent {

    const NAME = 'API_CONTENT';

    private $logger;

    public function __construct() {
        $this->logger = new Logger(self::NAME);

        $file = app()->config('file_log');

        if ($file) {
            $this->logger->pushHandler(new StreamHandler($file, Logger::INFO));
        }

        $this->logger->pushHandler(new SyslogHandler(self::NAME, LOG_USER, Logger::INFO));
    }

    public function log(mixed $level, string $message, array $context = []) {
        $this->logger->log($level, $message, $context);
    }

}