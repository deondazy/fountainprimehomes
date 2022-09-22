<?php

namespace Okoye\Core;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function add($message, $type, array $args = [])
    {
        // create a log channel
        $log = new Logger('Log');
        $log->pushHandler(new StreamHandler($this->config->debug->logPath, Logger::DEBUG));

        // add records to the log
        $log->$type($message, $args);
    }

    public function info($message, array $args = [])
    {
        $this->add($message, 'info', $args);
    }

    public function notice($message, array $args = [])
    {
        $this->add($message, 'notice', $args);
    }

    public function warning($message, array $args = [])
    {
        $this->add($message, 'warning', $args);
    }

    public function error($message, array $args = [])
    {
        $this->add($message, 'error', $args);
    }
}
