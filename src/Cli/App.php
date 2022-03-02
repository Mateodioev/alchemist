<?php 
namespace App\Cli;

use App\Cli\{Printer, Color};

class App
{
    protected $printer;
    protected $registry = [];

    public function __construct()
    {
        $this->printer = new Printer();
    }

    /**
     * Cli printer
     */
    public function GetPrinter()
    {
        return $this->printer;
    }

    /**
     * Register a command with callable function.
     */
    public function Register($name, $callable): void
    {
        $this->registry[$name] = $callable;
    }

    /**
     * Return all commands
     */
    public function GetAllCmds()
    {
        return $this->registry;
    }

    /**
     * Return callable function if the command is registered.
     */
    public function GetCommand($command)
    {
        return $this->GetAllCmds()[$command] ?? null;
    }

    /**
     * Run registered commands
     */
    public function Run(array $argv)
    {
        $cmd_name = $argv[1] ?? 'help';
        $command = $this->getCommand($cmd_name);

        if ($command === null) {
            // Colors: https://packagist.org/packages/php-parallel-lint/php-console-color
            $this->GetPrinter()->Display(Color::Bg(150, Color::Fg(232, 'Command "'.$cmd_name.'" not found.')));
            exit;
        }
        call_user_func($command, $argv);
    }

}