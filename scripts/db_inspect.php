<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=dbkhoaluan;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "-- ROLES --\n";
    $roles = $pdo->query('SELECT MA_VAI_TRO, TEN_VAI_TRO FROM vai_tro ORDER BY MA_VAI_TRO')->fetchAll();
    foreach ($roles as $role) {
        echo $role['MA_VAI_TRO'] . '|' . $role['TEN_VAI_TRO'] . "\n";
    }

    echo "\n-- CHUC_NANG --\n";
    $perms = $pdo->query('SELECT MA_CHUC_NANG, MA_CHUC_NANG_CODE, TEN_CHUC_NANG, PAGE, MODULE, TRANG_THAI_CN, THU_TU FROM chuc_nang ORDER BY MA_CHUC_NANG')->fetchAll();
    foreach ($perms as $perm) {
        echo $perm['MA_CHUC_NANG'] . '|' . $perm['MA_CHUC_NANG_CODE'] . '|' . $perm['TEN_CHUC_NANG'] . '|' . $perm['PAGE'] . '|' . $perm['MODULE'] . '|' . $perm['TRANG_THAI_CN'] . '|' . $perm['THU_TU'] . "\n";
    }

    echo "\n-- ROLE PERMISSIONS FOR ADMIN AND DOAN_TRUONG --\n";
    $stmt = $pdo->prepare('SELECT vt.TEN_VAI_TRO, cn.MA_CHUC_NANG, cn.MA_CHUC_NANG_CODE, cn.PAGE FROM vai_tro_chuc_nang vtcn JOIN vai_tro vt ON vtcn.MA_VAI_TRO = vt.MA_VAI_TRO JOIN chuc_nang cn ON cn.MA_CHUC_NANG = vtcn.MA_CHUC_NANG WHERE vt.TEN_VAI_TRO IN ("ADMIN","DOAN_TRUONG") ORDER BY vt.TEN_VAI_TRO, cn.MA_CHUC_NANG');
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        echo $row['TEN_VAI_TRO'] . '|' . $row['MA_CHUC_NANG'] . '|' . $row['MA_CHUC_NANG_CODE'] . '|' . $row['PAGE'] . "\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
