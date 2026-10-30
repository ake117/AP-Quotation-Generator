<?php

/**
 * Lightweight .env file loader for AP Quotation Generator.
 * Parses KEY="VALUE" lines into putenv(), $_ENV, and $_SERVER without external dependencies.
 */
class DotEnv
{
    private static bool $loaded = false;

    /**
     * Load the nearest .env file found in candidates list.
     */
    public static function load(?string $dir = null): void
    {
        if (self::$loaded && $dir === null) {
            return;
        }

        $candidates = array();
        if ($dir !== null) {
            $candidates[] = rtrim($dir, '/\\') . '/.env';
        }
        $candidates[] = dirname(__DIR__, 2) . '/.env';        // code/.env
        $candidates[] = dirname(__DIR__, 3) . '/.env';        // root/.env

        if (defined('FCPATH')) {
            $candidates[] = rtrim(FCPATH, '/\\') . '/../.env';
            $candidates[] = rtrim(FCPATH, '/\\') . '/../../.env';
        }

        foreach ($candidates as $file) {
            if (is_file($file) && is_readable($file)) {
                self::parse($file);
                self::$loaded = true;
                break;
            }
        }
    }

    /**
     * Parse a .env file and set environment variables.
     */
    public static function parse(string $path): void
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name  = trim($name);
                $value = trim($value);

                // Strip surrounding single/double quotes
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }

                // Do not overwrite existing environment variables from Docker / CLI
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv("{$name}={$value}");
                    $_ENV[$name]    = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
}

