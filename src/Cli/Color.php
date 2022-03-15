<?php 

namespace Mateodioev\Alchemist\Cli;

use PHP_Parallel_Lint\PhpConsoleColor\ConsoleColor;

/**
 * Color
 * 
 * @package CLI
 * @subpackage color
 * @author Mateo <https://github.com/mateodioev>
 */
class Color {
    
    public static function Apply($style, $text)
    {
        $consoleColor = new ConsoleColor();
        return $consoleColor->apply($style, $text);
    }

    /**
     * Background colors
     * @param integer $color Color code (0-255)
     */
    public static function Bg(int $color, string $text)
    {
        return self::Apply('bg_color_' . $color, $text);
    }

    /**
     * Foreground colors
     * @param integer $color Color code (0-255)
     */
    public static function Fg(int $color, string $text)
    {
        return self::Apply('color_' . $color, $text);
    }
}