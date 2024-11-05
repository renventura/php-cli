<?php

namespace RenVentura\PHP\CLI\Commands;

use RenVentura\PHP\CLI\Concerns\ {
    HasArgs,
    HasOutput
};
use RenVentura\PHP\CLI\Contracts\CommandInterface;
use RenVentura\PHP\CLI\Main;

abstract class Command implements CommandInterface
{
    use HasArgs;
    use HasOutput;

    /**
     * Outputs help documentation about the subcommand.
     * Runs when a subcommand is called with the --flag.
     *
     * @return void
     */
    public function help()
    {
        $sections = $this->getHelp();
        array_map([ $this, 'formatHelpSection' ], $this->getHelp());
    }

    /**
     * Format a helper section to stdout.
     *
     * @param array<mixed> $section Section config.
     *
     * @return void
     */
    private function formatHelpSection($section)
    {
        self::newLine();
        self::printLine(self::bold('NAME'));
        self::printLine($section['name']);

        self::newLine();
        self::printLine(self::bold('DESCRIPTION'));
        self::printLine($section['description']);

        self::newLine();
        self::printLine(self::bold('SYNOPSIS'));
        self::printLine(Main::NAMESPACE . ' ' . $section['synopsis']);

        self::newLine();
        self::printLine(self::bold('(SUB)COMMANDS'));
        $maxLen = self::getMaxLength(array_keys($section['subcommands']));
        foreach ($section['subcommands'] as $command => $desc) {
            echo "\t";
            self::printLine(str_pad($command, $maxLen) . "\t" . $desc);
        }

        self::newLine();
    }

    /**
     * Method for configuring command help/documentation.
     * Return an array of sections that print to stdout.
     *
     * @return array<mixed>
     */
    abstract public function getHelp();

    /**
     * Main callback for the subcommand.
     *
     * @return void
     */
    abstract public function run();
}
