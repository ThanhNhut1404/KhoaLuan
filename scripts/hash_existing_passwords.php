<?php

require_once __DIR__ . '/../vendor/autoload.php';

use KhoaLuan\QLDRL\Config\Database;

$db = Database::getConnection();
$users = $db->query('SELECT TEN_DANG_NHAP, MAT_KHAU FROM nguoi_dung')->fetchAll();
$updated = 0;
$skipped = 0;

$statement = $db->prepare(
    'UPDATE nguoi_dung
     SET MAT_KHAU = :password
     WHERE TEN_DANG_NHAP = :username'
);

foreach ($users as $user) {
    $password = (string) ($user['MAT_KHAU'] ?? '');

    if (password_get_info($password)['algoName'] !== 'unknown') {
        $skipped++;
        continue;
    }

    $statement->execute([
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'username' => $user['TEN_DANG_NHAP'],
    ]);
    $updated++;
}

echo sprintf("Updated: %d\nSkipped: %d\n", $updated, $skipped);
