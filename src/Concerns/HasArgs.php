<?php

namespace RenVentura\PHP\CLI\Concerns;

trait HasArgs
{
    /**
     * Parses command line args and returns array of args and their values.
     *
     * @param boolean   $subcommand     Whether the args are attached to a
     *                                  subcommand of the primary command (default false)
     *
     * @return array<mixed>
     */
    public function parseArgs($subcommand = false)
    {
        global $argv;
        $args = $argv;
        $parsed_args = [];

        $args = array_slice($args, $subcommand ? 2 : 1);
        for ($i = 0; $i < count($args); $i++) {
            switch (substr_count($args[$i], "-", 0, 2)) {
                case 1:
                    foreach (str_split(ltrim($args[$i], "-")) as $a) {
                        $parsed_args[$a] = isset($parsed_args[$a]) ? $parsed_args[$a] + 1 : 1;
                    }
                    break;

                case 2:
                    // phpcs:ignore Generic.Files.LineLength.TooLong
                    $parsed_args[ltrim(preg_replace("/=.*/", '', $args[$i]), '-')] = strpos($args[$i], '=') !== false ? substr($args[$i], strpos($args[$i], '=') + 1) : 1;
                    break;

                default:
                    $parsed_args['positional'][] = $args[$i];
            }
        }

        return $parsed_args;
    }
}
