<?php

namespace RenVentura\PHP\CLI\Commands;

use RenVentura\PHP\CLI\Concerns\HasPrivateKey;

class JWT extends Command
{
    use HasPrivateKey;

    /**
     * Method for configuring command help/documentation.
     * Return an array of sections that print to stdout.
     *
     * @return array<mixed>
     */
    public function getHelp()
    {
        return [
            [
                'name' => 'jwt',
                'description' => 'Generate, validate, and decode JSON Web Tokens (JWTs) via the command line.',
                'synopsis' => 'jwt <subcommand>',
                'subcommands' => [
                    'create' => 'Create a JWT.',
                    'validate' => 'Validates a JWT signature against a private key.',
                    'decode' => 'Decodes a valid JWT.',
                ]
            ]
        ];
    }

    /**
     * Main callback for the subcommand.
     *
     * @return void
     */
    public function run()
    {

        // CLI sub-subcommands
        $args = $this->parseArgs(true);

        if (! empty($args['positional'][0])) {
            switch ($args['positional'][0]) {
                case 'create':
                    $now = $this->gmtTimeToLocalTime();
                    $token = $this->createToken([
                        'iss' => 'Signing Issuer',
                        'iat' => strtotime($now),
                        'exp' => strtotime($now) + 86400, // 24 hours
                        'temp_domain' => $args['temp_domain']
                    ]);
                    self::printLine($token, 'black', 'white');
                    break;

                case 'validate':
                    if ($this->validateToken($args['positional'][1])) {
                        self::printLine('Signature is valid', 'green');
                        break;
                    }
                    self::printLine('Signature is NOT valid', 'red');
                    break;

                case 'decode':
                    $decoded = $this->decodeToken($args['positional'][1]);
                    if (! empty($decoded)) {
                        print_r($decoded);
                        echo 'Created: ' . date('F j, Y H:i:s', $decoded['iat']) . PHP_EOL;
                        echo 'Expires: ' . date('F j, Y H:i:s', $decoded['exp']) . PHP_EOL;
                        break;
                    }

                    self::printLine('Invalid token.');
                    break;

                default:
                    self::printLine('Invalid subcommand.');
                    break;
            }
        }
    }

    /**
     * base64 Encoding.
     *
     * @param string $data json_encoded data for payload.
     *
     * @return string
     */
    private function encode($data)
    {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }

    /**
     * base64 Decoding.
     *
     * @param string $data base64_encoded data.
     *
     * @return string
     */
    private function decode($data)
    {
        $base64 = strtr($data, '-_', '+/');
        $base64Padded = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($base64Padded);
    }

    /**
     * Create a JSON Web Token.
     *
     * @param array<mixed> $payload Payload data.
     *
     * @return string
     */
    private function createToken($payload)
    {
        $header = $this->encode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));
        $payload = $this->encode(json_encode($payload));
        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->getPrivateKey(), true);
        $signature = $this->encode($signature);
        return "{$header}.{$payload}.{$signature}";
    }

    /**
     * Validate a JWT.
     *
     * @param string $token JWT
     *
     * @return boolean True when the token validates against the given private key.
     */
    private function validateToken($token)
    {
        list( $header, $payload, $signature ) = explode('.', $token);

        $signature = $this->decode($signature);
        $expectedSignature = hash_hmac('sha256', $header . '.' . $payload, $this->getPrivateKey(), true);

        return hash_equals($signature, $expectedSignature);
    }

    /**
     * Decode a JWT.
     *
     * @param string $token JWT
     *
     * @return array<mixed> Payload data
     */
    private function decodeToken($token)
    {
        $decoded = [];
        if ($this->validateToken($token)) {
            list(, $payload, ) = explode('.', $token);
            $payload = $this->decode($payload);
            $decoded = json_decode($payload, true);
        }
        return $decoded;
    }

    /**
     * Convert UTC time to local timezone.
     *
     * @param string $to_timezone Localized timezone
     *
     * @return string
     */
    private function gmtTimeToLocalTime($to_timezone = 'America/New_York')
    {
        date_default_timezone_set('UTC');
        $new_date = new \DateTime();
        $new_date->setTimeZone(new \DateTimeZone($to_timezone));
        return $new_date->format("Y-m-d h:i:s");
    }
}
