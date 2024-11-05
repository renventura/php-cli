<?php

namespace RenVentura\PHP\CLI\Contracts;

interface CommandInterface
{
    /**
     * Outputs help documentation about the command.
     * Runs when a command is called with the --flag.
     *
     * @return void
     */
    public function help();

    /**
     * Main callback for the command.
     *
     * @return void
     */
    public function run();
}
