<?php

namespace CoffeeMachine\Utils;

use Throwable;

final class ConsoleUtils
{
    /**
     * Writes a line to the console output.
     *
     * @param string $string    The text to be output.
     * @return void
     */
    public static function writeLine(string $string): void
    {
        print PHP_EOL;

        printf('> %s', $string);

        print PHP_EOL;
    }

    public static function writeException(Throwable $e): void
    {
        print PHP_EOL;

        printf('> [%s]: %s', get_class($e), $e->getMessage());

        print PHP_EOL;
    }
}