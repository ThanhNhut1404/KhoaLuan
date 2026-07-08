<?php

namespace KhoaLuan\QLDRL\Config;

class Env
{
    private static array $values = [];
    private static bool $loaded = false;

    public static function load(?string $path = null): void
    {
        $envPath = $path ?? dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env';

        self::$values = [];
        self::$loaded = true;

        if (!is_file($envPath) || !is_readable($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if ($key === '') {
                continue;
            }

            self::$values[$key] = self::normalizeValue($value);
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$values[$key] ?? $default;
    }

    private static function normalizeValue(string $value): string
    {
        $length = strlen($value);

        if ($length >= 2) {
            $first = $value[0];
            $last = $value[$length - 1];

            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                return substr($value, 1, -1);
            }
        }

        return $value;
    }
}
