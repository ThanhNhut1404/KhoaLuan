<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;

class UserModel
{
    public function __construct(private PDO $db)
    {
    }

    public function getPasswordByUsername(string $username): ?string
    {
        $statement = $this->db->prepare(
            'SELECT MAT_KHAU
             FROM nguoi_dung
             WHERE TEN_DANG_NHAP = :username
             LIMIT 1'
        );
        $statement->execute(['username' => $username]);
        $password = $statement->fetchColumn();

        return $password === false ? null : (string) $password;
    }

    public function updatePlainPassword(string $username, string $password): bool
    {
        $statement = $this->db->prepare(
            'UPDATE nguoi_dung
             SET MAT_KHAU = :password
             WHERE TEN_DANG_NHAP = :username'
        );

        return $statement->execute([
            'password' => $password,
            'username' => $username,
        ]) && $statement->rowCount() > 0;
    }

    public function findActiveByPlainCredentials(string $username, string $password): ?array
    {
        $statement = $this->db->prepare(
            "SELECT TEN_DANG_NHAP
             FROM nguoi_dung
             WHERE TEN_DANG_NHAP = :username
               AND MAT_KHAU = :password
               AND TRANG_THAI_ND = 'HOAT_DONG'
             LIMIT 1"
        );
        $statement->execute([
            'username' => $username,
            'password' => $password,
        ]);
        $account = $statement->fetch();

        return $account ?: null;
    }

    public function getFirstRole(string $username): ?array
    {
        $statement = $this->db->prepare(
            'SELECT ndvt.MA_VAI_TRO, vt.TEN_VAI_TRO
             FROM nguoi_dung_vai_tro ndvt
             INNER JOIN vai_tro vt ON vt.MA_VAI_TRO = ndvt.MA_VAI_TRO
             WHERE ndvt.TEN_DANG_NHAP = :username
             ORDER BY ndvt.MA_VAI_TRO
             LIMIT 1'
        );
        $statement->execute(['username' => $username]);
        $role = $statement->fetch();

        return $role ?: null;
    }

    public function getProfileForRole(string $username, string $roleName): ?array
    {
        $profileTables = [
            'ADMIN' => ['table' => 'nha_truong', 'id' => 'MA_TAI_KHOAN'],
            'DOAN_TRUONG' => ['table' => 'doan_truong', 'id' => 'MA_DOAN_TRUONG'],
            'DOAN_KHOA' => ['table' => 'doan_khoa', 'id' => 'MA_DOAN_KHOA'],
            'LIEN_CHI' => ['table' => 'lien_chi_clb', 'id' => 'MA_LIEN_CHI_CLB'],
            'GIANG_VIEN' => ['table' => 'giang_vien', 'id' => 'MA_GV'],
            'CO_VAN_HOC_TAP' => ['table' => 'giang_vien', 'id' => 'MA_GV'],
            'CAN_BO_LOP' => ['table' => 'can_bo_lop', 'id' => 'MA_CAN_BO_LOP'],
            'SINH_VIEN' => ['table' => 'sinh_vien', 'id' => 'MA_SV'],
        ];

        if (!isset($profileTables[$roleName])) {
            return null;
        }

        $profile = $profileTables[$roleName];
        $sql = sprintf(
            'SELECT %s AS MA_HO_SO FROM %s WHERE TEN_DANG_NHAP = :username LIMIT 1',
            $profile['id'],
            $profile['table']
        );

        $statement = $this->db->prepare($sql);
        $statement->execute(['username' => $username]);
        $row = $statement->fetch();

        if (!$row) {
            return null;
        }

        return [
            'TEN_BANG_HO_SO' => $profile['table'],
            'MA_HO_SO' => $row['MA_HO_SO'],
        ];
    }

    public function updateLastLogin(string $username): void
    {
        $statement = $this->db->prepare(
            'UPDATE nguoi_dung
             SET LAN_DANG_NHAP_CUOI = NOW()
             WHERE TEN_DANG_NHAP = :username'
        );
        $statement->execute(['username' => $username]);
    }
}
