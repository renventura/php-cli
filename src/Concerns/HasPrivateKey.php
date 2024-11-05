<?php

namespace RenVentura\PHP\CLI\Concerns;

trait HasPrivateKey
{
    /**
     * Get the string of a private key file.
     *
     * @return string
     */
    private function getPrivateKey()
    {
        return file_get_contents(dirname(dirname(__DIR__)) . '/private-key');
    }
}
