<?php

namespace RenVentura\PHP\CLI;

// phpcs:ignoreFile PSR1.Files.SideEffects.FoundWithSymbols

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Entrypoint for mycommands CLI command
class Main extends Commands\Command
{

    public const MIN_PHP_VERSION = '7.3';
    public const NAMESPACE = 'mycommands';

    /**
     * Registered commands.
     *
     * @var array<string,array<mixed>>
     */
    private $commands = [
        'jwt' => [
            'description' => 'Generate, validate, and decode JSON Web Tokens (JWTs) via the command line.',
            'class' => Commands\JWT::class
        ]
    ];

    public function __construct()
    {

        // This command should be invoked correctly.
        if (trim(shell_exec('echo "$CALLED_CORRECTLY"')) !== 'yes') {
            exit;
        }

        // Minimum PHP version
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            // phpcs:ignore Generic.Files.LineLength.TooLong
            self::printLine('This package requires PHP v' . self::MIN_PHP_VERSION . '; currently running v' . PHP_VERSION);
            exit;
        }
    }

    /**
     * Method for configuring command help/documentation.
     * Return an array of sections that print to stdout.
     *
     * @return array<mixed>
     */
    public function getHelp() {
        $commands = [];

        foreach ( $this->commands as $command => $config ) {
            $commands[$command] = $config['description'];
        }

        return [
            [
                'name' => self::NAMESPACE,
                'description' => 'My custom CLI commands.',
                'synopsis' => '<command> [--help]',
                'subcommands' => $commands,
                'extra' => 'Run "' . self::NAMESPACE . ' <command> --help" to get more information on a specific command.'
            ]
        ];
    }

    /**
     * Run the main command.
     *
     * @return void
     */
    public function run()
    {
        $args = $this->parseArgs();

        // CLI subcommands
        $calledCommand = $args['positional'][0] ?? null;

        // Help on main command
        if ( empty( $calledCommand ) && ! empty( $args['help'] ) ) {
            $this->help();
            return;
        }

        if (! array_key_exists($calledCommand, $this->commands)) {
            self::printLine('Command does not exist.', 'red');
            return;
        }

        $subcommand = new $this->commands[$calledCommand]['class']();

        if (! empty($args['help'])) {
            $subcommand->help();
            return;
        }

        $subcommand->run();
    }
}

$command = new Main();
$command->run();
