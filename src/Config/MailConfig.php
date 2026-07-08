<?php

namespace KhoaLuan\QLDRL\Config;

class MailConfig
{
    public static function all(): array
    {
        return [
            'MAIL_HOST' => self::host(),
            'MAIL_PORT' => self::port(),
            'MAIL_USERNAME' => self::username(),
            'MAIL_PASSWORD' => self::password(),
            'MAIL_FROM_NAME' => self::fromName(),
            'MAIL_FROM_EMAIL' => self::fromEmail(),
        ];
    }

    public static function host(): string
    {
        return Env::get('MAIL_HOST', 'smtp.gmail.com') ?? 'smtp.gmail.com';
    }

    public static function port(): int
    {
        return (int) (Env::get('MAIL_PORT', '587') ?? '587');
    }

    public static function username(): string
    {
        return Env::get('MAIL_USERNAME', '') ?? '';
    }

    public static function password(): string
    {
        return Env::get('MAIL_PASSWORD', '') ?? '';
    }

    public static function fromName(): string
    {
        return Env::get('MAIL_FROM_NAME', 'UniDRL') ?? 'UniDRL';
    }

    public static function fromEmail(): string
    {
        return Env::get('MAIL_FROM_EMAIL', '') ?? '';
    }
}
