<?php 

namespace Mateodioev\Alchemist\Config;

class Logger {

    /**
     * Activate error log
     */
    public static function Activate(string $file, int $execution_time)
    {
        set_time_limit($execution_time);
        error_reporting(E_ALL);
        ini_set('ignore_repeated_errors', true);
        ini_set('display_errors', false);
        ini_set('log_errors', true);
        ini_set('error_log', $file);
    }

    /**
     * Create dir logs if not exists
     */
    public static function CreateDir(string $dir)
    {
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777, true)) {
                throw new \Exception("Error creating directory $dir");
            }
        }
    }
}