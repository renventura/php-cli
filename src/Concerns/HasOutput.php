<?php

namespace RenVentura\PHP\CLI\Concerns;

trait HasOutput
{
    /**
     * Escape character
     */
    public const ANSI_ESC = "\033";

    /**
     * Clear all ANSI styling
     */
    public const ANSI_CLOSE = self::ANSI_ESC . "[0m";

    /**
     * ANSI colours
     */
    public const ANSI_BLACK = self::ANSI_ESC . "[30m";
    public const ANSI_BLUE = self::ANSI_ESC . "[34m";
    public const ANSI_CYAN = self::ANSI_ESC . "[36m";
    public const ANSI_GREEN = self::ANSI_ESC . "[32m";
    public const ANSI_MAGENTA = self::ANSI_ESC . "[35m";
    public const ANSI_RED = self::ANSI_ESC . "[31m";
    public const ANSI_WHITE = self::ANSI_ESC . "[37m";
    public const ANSI_YELLOW = self::ANSI_ESC . "[33m";

    /**
     * ANSI background colours
     */
    public const ANSI_BG_BLACK = self::ANSI_ESC . "[40m";
    public const ANSI_BG_BLUE = self::ANSI_ESC . "[44m";
    public const ANSI_BG_CYAN = self::ANSI_ESC . "[46m";
    public const ANSI_BG_GREEN = self::ANSI_ESC . "[42m";
    public const ANSI_BG_MAGENTA = self::ANSI_ESC . "[45m";
    public const ANSI_BG_RED = self::ANSI_ESC . "[41m";
    public const ANSI_BG_WHITE = self::ANSI_ESC . "[47m";
    public const ANSI_BG_YELLOW = self::ANSI_ESC . "[43m";

    /**
     * ANSI styles
     */
    public const ANSI_BOLD = self::ANSI_ESC . "[1m";
    public const ANSI_ITALIC = self::ANSI_ESC . "[3m";
    public const ANSI_UNDERLINE = self::ANSI_ESC . "[4m";
    public const ANSI_STRIKETHROUGH = self::ANSI_ESC . "[9m";

    /**
     * Formats text against a colored background.
     *
     * @param string $color Background color (see ANSI_BG_* color constants)
     * @param string $text Text to format
     *
     * @return string
     */
    public static function bg($color, $text)
    {
        $color = ! empty($color) && defined('self::ANSI_BG_' . strtoupper($color))
                ? constant('self::ANSI_BG_' . strtoupper($color))
                : '';
        $close = $color ? self::ANSI_CLOSE : '';
        return $color . $text . $close;
    }

    /**
     * Formats text with a specified color.
     *
     * @param string $color Text color (see ANSI_* color constants)
     * @param string $text Text to format
     *
     * @return string
     */
    public static function colorize($color, $text)
    {
        $color = ! empty($color) && defined('self::ANSI_' . strtoupper($color))
                ? constant('self::ANSI_' . strtoupper($color))
                : '';
        $close = $color ? self::ANSI_CLOSE : '';
        return $color . $text . $close;
    }

    /**
     * Formats bold text.
     *
     * @param string $text Text to format
     *
     * @return string
     */
    public static function bold($text)
    {
        return self::ANSI_BOLD . $text . self::ANSI_CLOSE;
    }

    /**
     * Prints text to stdout.
     *
     * @param string $color Text color
     * @param string $bg Background color
     * @param string $text Text to print
     *
     * @return void
     */
    public static function printLine($text, $color = '', $bg = '')
    {
        echo self::bg($bg, self::colorize($color, $text)) . PHP_EOL;
    }

    /**
     * Prints a new line.
     *
     * @return void
     */
    public static function newLine()
    {
        echo PHP_EOL;
    }

    /**
     * Gets the longest string from an array of strings.
     *
     * @param array<string> $strings Strings to compare.
     *
     * @return int
     */
    public static function getMaxLength($strings)
    {
        $max_len = 0;
        foreach ($strings as $str) {
            $len = strlen($str);
            if ($len > $max_len) {
                $max_len = $len;
            }
        }

        return $max_len;
    }
}
