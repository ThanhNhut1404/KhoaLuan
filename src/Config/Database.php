<?php

namespace KhoaLuan\QLDRL\Config;

use PDO;
use PDOException;

class Database
{
    private const HOST = 'localhost';
    private const DB_NAME = 'dbkhoaluan';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const CHARSET = 'utf8mb4';

    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::HOST,
                self::DB_NAME,
                self::CHARSET
            );

            try {
                self::$connection = new PDO($dsn, self::USERNAME, self::PASSWORD, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $exception) {
                throw new PDOException('Khong the ket noi co so du lieu: ' . $exception->getMessage());
            }
        }

        return self::$connection;
    }
}
