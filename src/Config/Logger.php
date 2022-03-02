<?php 

namespace App\Config;

class Logger {

    public static function Activate(string $file, int $execution_time)
    {
        set_time_limit($execution_time);
        error_reporting(E_ALL);
        ini_set('ignore_repeated_errors', true);
        ini_set('display_errors', false);
        ini_set('log_errors', true);
        ini_set('error_log', $file);
    }
}