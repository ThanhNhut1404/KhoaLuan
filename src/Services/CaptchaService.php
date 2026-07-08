<?php

namespace KhoaLuan\QLDRL\Services;

final class CaptchaService
{
    private const WIDTH = 180;
    private const HEIGHT = 54;
    private const LENGTH = 5;
    private const UPPER_CHARS = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    private const LOWER_CHARS = 'abcdefghjkmnpqrstuvwxyz';
    private const DIGIT_CHARS = '23456789';
    private const CHARS = self::UPPER_CHARS . self::LOWER_CHARS . self::DIGIT_CHARS;

    public static function render(string $sessionKey): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=UTF-8');
            echo 'GD extension is not enabled.';
            exit;
        }

        $code = self::generateCode();
        $_SESSION[$sessionKey] = $code;

        $image = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imageantialias($image, true);

        $background = imagecolorallocate($image, 245, 249, 255);
        $lineColor = imagecolorallocate($image, 180, 204, 238);
        $dotColor = imagecolorallocate($image, 132, 164, 210);
        $textColor = imagecolorallocate($image, 19, 78, 165);
        $shadowColor = imagecolorallocate($image, 185, 210, 245);

        imagefilledrectangle($image, 0, 0, self::WIDTH, self::HEIGHT, $background);

        for ($i = 0, $lines = random_int(3, 5); $i < $lines; $i++) {
            imagesetthickness($image, 1);
            imageline(
                $image,
                random_int(0, self::WIDTH),
                random_int(6, self::HEIGHT - 7),
                random_int(0, self::WIDTH),
                random_int(6, self::HEIGHT - 7),
                $lineColor
            );
        }

        for ($i = 0, $dots = random_int(80, 120); $i < $dots; $i++) {
            imagesetpixel($image, random_int(0, self::WIDTH - 1), random_int(0, self::HEIGHT - 1), $dotColor);
        }

        $font = self::findFont();
        $x = 16;

        for ($i = 0; $i < self::LENGTH; $i++) {
            $char = $code[$i];

            if ($font !== null) {
                $angle = random_int(-10, 10);
                $y = random_int(34, 41);
                imagettftext($image, 25, $angle, $x + 2, $y + 2, $shadowColor, $font, $char);
                imagettftext($image, 25, $angle, $x, $y, $textColor, $font, $char);
                $x += random_int(29, 33);
            } else {
                imagestring($image, 5, $x + 2, random_int(20, 24), $char, $shadowColor);
                imagestring($image, 5, $x, random_int(18, 22), $char, $textColor);
                $x += random_int(28, 32);
            }
        }

        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        imagepng($image);
        imagedestroy($image);
        exit;
    }

    private static function generateCode(): string
    {
        $chars = [
            self::randomChar(self::UPPER_CHARS),
            self::randomChar(self::LOWER_CHARS),
            self::randomChar(self::DIGIT_CHARS),
        ];

        while (count($chars) < self::LENGTH) {
            $chars[] = self::randomChar(self::CHARS);
        }

        for ($i = count($chars) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$chars[$i], $chars[$j]] = [$chars[$j], $chars[$i]];
        }

        return implode('', $chars);
    }

    private static function randomChar(string $chars): string
    {
        return $chars[random_int(0, strlen($chars) - 1)];
    }

    private static function findFont(): ?string
    {
        $candidates = [
            'C:\\Windows\\Fonts\\arialbd.ttf',
            'C:\\Windows\\Fonts\\arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
        ];

        foreach ($candidates as $font) {
            if (is_file($font)) {
                return $font;
            }
        }

        return null;
    }
}
