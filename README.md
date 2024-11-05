# PHP-CLI ![Develop branch](https://github.com/renventura/php-cli/actions/workflows/ci.yml/badge.svg?branch=develop) [![GitHub issues](https://img.shields.io/github/issues/renventura/php-cli.svg)](https://github.com/renventura/php-cli/issues) [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/renventura/php-cli/master/LICENSE) 

A lightweight, dependency-free starter template for generating CLI programs using PHP.

## Installation

Fork/clone this repo, `chmod +x bin/mycommands`, `ln -sf bin/mycommands mycommands` file somewhere in your path (call is what you want), and customize!

What does this do? It creates a wrapper executable that you add to your system's `$PATH`. It's a wrapper for the `php` executable, and runs your PHP commands without the need to type something ugly, like `$ php mycommands.php <command>`. Instead, you just run `$ mycommands <command>`. Since it's an executable in your `$PATH`, you also get tab completion!

The wrapper executable ensures that the PHP executable is available on your system, then kicks off your custom commands. It is pretty much just syntactical sugar, and shouldn't need modified unless you have specific reasons. 

## Basic usage

### `RenVentura\PHP\CLI\Main`

The real customizations being in the `src/main.php` file. This is the entry for your command. The idea is that it's where you define basic configurations about the root namespace for your command.

To begin customizing things, start with the defined constants in the `Main` class. They look like this:

```php
public const MIN_PHP_VERSION = '7.3';
public const NAMESPACE = 'mycommands';
```

Define your minimum PHP version and namespace. The namespace is what all your commands and subcommands will begin with. For example: `$ {NAMESPACE} command subcomand`

Next is the `$commands` property. 

```php
/**
 * Registered commands.
 *
 * @var array<string,class-string<Commands\Command>>
 */
private $commands = [
    'jwt' => [
        'description' => 'Generate, validate, and decode JSON Web Tokens (JWTs) via the command line.',
        'class' => Commands\JWT::class
    ]
];
```

This is an array of "commands" to add under your namespace. The keys are the commands, and the values are an array with the command's `description` and class name (`class`).

### Commands

Individual commands are added in the `src/Commands` directory, and extend `RenVentura\PHP\CLI\Commands\Command`. 

For a starter example, see `RenVentura\PHP\CLI\Commands\JWT`.

Each `Command` must include two public methods: `getHelp()` and `run()`. The former is used for documenting the command via the `--help` flag, while the latter is the callback for executing the command.

Configure the `getHelp()` method to return an array with `name`, `description`, `synopsis`, and `subcommands`. This configuration will populate the stdout when the command is invoked with `--flag`.

Write the main logic and processes of the command in the `run()` method. This is where you make your command do things. Create files, send HTTP requests, whatever you wish.
