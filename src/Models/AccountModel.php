<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use Throwable;

class AccountModel
{
    public function __construct(private PDO $db)
    {
    }

    public function loadCreateOptions(): array
    {
        return [
            'roles' => $this->db->query(
                "SELECT MA_VAI_TRO, TEN_VAI_TRO
                 FROM vai_tro
                 WHERE TEN_VAI_TRO NOT IN ('ADMIN', 'SINH_VIEN')
                 ORDER BY MA_VAI_TRO"
            )->fetchAll(),
            'classes' => $this->db->query(
                'SELECT MA_LOP, TEN_LOP FROM lop_hoc ORDER BY TEN_LOP'
            )->fetchAll(),
            'departments' => $this->db->query(
                'SELECT MA_KHOA, TEN_KHOA FROM khoa_bo_mon ORDER BY TEN_KHOA'
            )->fetchAll(),
            'students' => $this->db->query(
                'SELECT MA_SV, MSSV, TEN_DANG_NHAP
                 FROM sinh_vien
                 ORDER BY MSSV'
            )->fetchAll(),
            'unions' => $this->db->query(
                'SELECT MA_DOAN_TRUONG, TEN_DOAN_HOI FROM doan_truong ORDER BY TEN_DOAN_HOI'
            )->fetchAll(),
        ];
    }

    public function getRoleById(string $roleId): ?array
    {
        $statement = $this->db->prepare(
            "SELECT MA_VAI_TRO, TEN_VAI_TRO
             FROM vai_tro
             WHERE MA_VAI_TRO = :role_id
               AND TEN_VAI_TRO <> 'ADMIN'
             LIMIT 1"
        );
        $statement->execute(['role_id' => $roleId]);
        $role = $statement->fetch();

        return $role ?: null;
    }

    public function valueExists(string $table, string $column, string $value): bool
    {
        $sql = sprintf('SELECT 1 FROM %s WHERE %s = :value LIMIT 1', $table, $column);
        $statement = $this->db->prepare($sql);
        $statement->execute(['value' => $value]);

        return (bool) $statement->fetchColumn();
    }

    public function accountEmailExists(string $email, string $exceptUsername = ''): bool
    {
        $sql = 'SELECT 1
                FROM nguoi_dung
                WHERE EMAIL_ND = :email';
        $params = ['email' => $email];

        if ($exceptUsername !== '') {
            $sql .= ' AND TEN_DANG_NHAP <> :username';
            $params['username'] = $exceptUsername;
        }

        $sql .= ' LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }

    public function classPositionExists(string $classId, string $position): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM can_bo_lop
             WHERE MA_LOP = :class_id
               AND CHUC_VU_CB = :position
             LIMIT 1'
        );
        $statement->execute([
            'class_id' => $classId,
            'position' => $position,
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function createWithProfile(array $data, array $role): bool
    {
        $username = trim($data['username']);
        $email = trim((string) ($data['email'] ?? ''));
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->db->beginTransaction();

        try {
            $accountStatement = $this->db->prepare(
                "INSERT INTO nguoi_dung (TEN_DANG_NHAP, EMAIL_ND, MAT_KHAU, TRANG_THAI_ND)
                 VALUES (:username, :email, :password, 'HOAT_DONG')"
            );
            $accountStatement->execute([
                'username' => $username,
                'email' => $email !== '' ? $email : null,
                'password' => $hashedPassword,
            ]);

            $roleStatement = $this->db->prepare(
                'INSERT INTO nguoi_dung_vai_tro (TEN_DANG_NHAP, MA_VAI_TRO)
                 VALUES (:username, :role_id)'
            );
            $roleStatement->execute([
                'username' => $username,
                'role_id' => $role['MA_VAI_TRO'],
            ]);

            $this->insertRoleProfile($data, $role, $username);
            $this->db->commit();

            return true;
        } catch (Throwable) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
    }

    public function getAccountRows(): array
    {
        $statement = $this->db->query(
            "SELECT
                nd.TEN_DANG_NHAP AS username,
                nd.EMAIL_ND AS email,
                CASE WHEN nd.TRANG_THAI_ND = 'HOAT_DONG' THEN 'active' ELSE 'inactive' END AS status,
                COALESCE(sv.HO_TEN, gv.TEN_GV, dt.TEN_DOAN_HOI, dk.TEN_DOAN_KHOA, lcc.TEN_LIEN_CHI_CLB, nd.TEN_DANG_NHAP) AS full_name,
                COALESCE(sv.GIO_TINH, '') AS gender,
                COALESCE(sv.SO_DIEN_THOAI, gv.SO_DIEN_THOAI_GV, '') AS phone,
                GROUP_CONCAT(DISTINCT vt.TEN_VAI_TRO ORDER BY vt.MA_VAI_TRO SEPARATOR ', ') AS role
             FROM nguoi_dung nd
             LEFT JOIN nguoi_dung_vai_tro ndvt ON ndvt.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN vai_tro vt ON vt.MA_VAI_TRO = ndvt.MA_VAI_TRO
             LEFT JOIN sinh_vien sv ON sv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN giang_vien gv ON gv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN doan_truong dt ON dt.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN doan_khoa dk ON dk.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN lien_chi_clb lcc ON lcc.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             WHERE NOT EXISTS (
                SELECT 1
                FROM nguoi_dung_vai_tro student_role
                INNER JOIN vai_tro student_vt ON student_vt.MA_VAI_TRO = student_role.MA_VAI_TRO
                WHERE student_role.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
                  AND student_vt.TEN_VAI_TRO = 'SINH_VIEN'
             )
             GROUP BY nd.TEN_DANG_NHAP, nd.EMAIL_ND, nd.TRANG_THAI_ND, sv.HO_TEN, gv.TEN_GV, dt.TEN_DOAN_HOI,
                dk.TEN_DOAN_KHOA, lcc.TEN_LIEN_CHI_CLB, sv.GIO_TINH, sv.SO_DIEN_THOAI, gv.SO_DIEN_THOAI_GV
             ORDER BY nd.TEN_DANG_NHAP"
        );

        return $statement->fetchAll() ?: [];
    }

    public function getRoleOptions(): array
    {
        $statement = $this->db->query(
            "SELECT MA_VAI_TRO AS id, TEN_VAI_TRO AS name
             FROM vai_tro
             WHERE TEN_VAI_TRO <> 'ADMIN'
             ORDER BY MA_VAI_TRO"
        );

        return $statement->fetchAll() ?: [];
    }

    public function getAccountForEdit(string $username): ?array
    {
        $statement = $this->db->prepare(
            "SELECT
                nd.TEN_DANG_NHAP AS username,
                nd.EMAIL_ND AS email,
                CASE WHEN nd.TRANG_THAI_ND = 'HOAT_DONG' THEN 'active' ELSE 'inactive' END AS status,
                COALESCE(sv.HO_TEN, gv.TEN_GV, dt.TEN_DOAN_HOI, dk.TEN_DOAN_KHOA, lcc.TEN_LIEN_CHI_CLB, nd.TEN_DANG_NHAP) AS full_name,
                COALESCE(sv.GIO_TINH, '') AS gender,
                COALESCE(sv.NGAY_SINH, '') AS birthday,
                COALESCE(sv.DIA_CHI, '') AS address,
                COALESCE(sv.SO_DIEN_THOAI, gv.SO_DIEN_THOAI_GV, '') AS phone,
                MIN(vt.MA_VAI_TRO) AS role
             FROM nguoi_dung nd
             LEFT JOIN nguoi_dung_vai_tro ndvt ON ndvt.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN vai_tro vt ON vt.MA_VAI_TRO = ndvt.MA_VAI_TRO
             LEFT JOIN sinh_vien sv ON sv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN giang_vien gv ON gv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN doan_truong dt ON dt.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN doan_khoa dk ON dk.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN lien_chi_clb lcc ON lcc.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             WHERE nd.TEN_DANG_NHAP = :username
             GROUP BY nd.TEN_DANG_NHAP, nd.EMAIL_ND, nd.TRANG_THAI_ND, sv.HO_TEN, gv.TEN_GV, dt.TEN_DOAN_HOI,
                dk.TEN_DOAN_KHOA, lcc.TEN_LIEN_CHI_CLB, sv.GIO_TINH, sv.NGAY_SINH, sv.DIA_CHI,
                sv.SO_DIEN_THOAI, gv.SO_DIEN_THOAI_GV
             LIMIT 1"
        );
        $statement->execute(['username' => $username]);
        $account = $statement->fetch();

        return $account ?: null;
    }

    public function updateAccount(string $currentUsername, array $data): bool
    {
        $email = trim((string) ($data['email'] ?? ''));
        $status = ($data['status'] ?? '') === 'inactive' ? 'KHONG_HOAT_DONG' : 'HOAT_DONG';
        $password = (string) ($data['password'] ?? '');

        $this->db->beginTransaction();

        try {
            if ($password !== '') {
                $statement = $this->db->prepare(
                    'UPDATE nguoi_dung
                     SET EMAIL_ND = :email, TRANG_THAI_ND = :status, MAT_KHAU = :password
                     WHERE TEN_DANG_NHAP = :username'
                );
                $statement->execute([
                    'email' => $email !== '' ? $email : null,
                    'status' => $status,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'username' => $currentUsername,
                ]);
            } else {
                $statement = $this->db->prepare(
                    'UPDATE nguoi_dung
                     SET EMAIL_ND = :email, TRANG_THAI_ND = :status
                     WHERE TEN_DANG_NHAP = :username'
                );
                $statement->execute([
                    'email' => $email !== '' ? $email : null,
                    'status' => $status,
                    'username' => $currentUsername,
                ]);
            }

            $this->updateProfileEmail($currentUsername, $email);
            $this->db->commit();

            return true;
        } catch (Throwable) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
    }

    public function updateStatuses(array $statuses): void
    {
        $statement = $this->db->prepare(
            'UPDATE nguoi_dung
             SET TRANG_THAI_ND = :status
             WHERE TEN_DANG_NHAP = :username'
        );

        foreach ($statuses as $username => $status) {
            $statement->execute([
                'status' => $status === 'inactive' ? 'KHONG_HOAT_DONG' : 'HOAT_DONG',
                'username' => (string) $username,
            ]);
        }
    }

    private function updateProfileEmail(string $username, string $email): void
    {
        $profileEmailColumns = [
            'sinh_vien' => 'EMAIL_SV',
            'giang_vien' => 'EMAIL_GV',
            'doan_truong' => 'EMAIL_DT',
            'doan_khoa' => 'EMAIL_DK',
        ];

        foreach ($profileEmailColumns as $table => $column) {
            $sql = sprintf(
                'UPDATE %s SET %s = :email WHERE TEN_DANG_NHAP = :username',
                $table,
                $column
            );
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'email' => $email !== '' ? $email : null,
                'username' => $username,
            ]);
        }
    }

    private function insertRoleProfile(array $data, array $role, string $username): void
    {
        switch ($role['TEN_VAI_TRO']) {
            case 'SINH_VIEN':
                $statement = $this->db->prepare(
                    'INSERT INTO sinh_vien
                     (MA_LOP, TEN_DANG_NHAP, MSSV, HO_TEN, NGAY_SINH, GIO_TINH, EMAIL_SV, SO_DIEN_THOAI, DIA_CHI, TRANG_THAI_HOC_TAP)
                     VALUES (:class_id, :username, :mssv, :full_name, :birth_date, :gender, :email, :phone, :address, :status)'
                );
                $statement->execute([
                    'class_id' => $data['class_id'],
                    'username' => $username,
                    'mssv' => $username,
                    'full_name' => trim($data['full_name']),
                    'birth_date' => $data['birth_date'],
                    'gender' => $data['gender'],
                    'email' => trim($data['email']),
                    'phone' => trim($data['phone']),
                    'address' => trim($data['address']),
                    'status' => 'Đang học',
                ]);
                break;

            case 'GIANG_VIEN':
            case 'CO_VAN_HOC_TAP':
            case 'BO_MON':
            case 'KHOA':
                $statement = $this->db->prepare(
                    'INSERT INTO giang_vien
                     (MA_KHOA, TEN_DANG_NHAP, TEN_GV, EMAIL_GV, SO_DIEN_THOAI_GV, CHUC_VU_GV)
                     VALUES (:department_id, :username, :full_name, :email, :phone, :position)'
                );
                $statement->execute([
                    'department_id' => $data['department_id'],
                    'username' => $username,
                    'full_name' => trim($data['full_name']),
                    'email' => trim($data['email']),
                    'phone' => trim($data['phone']),
                    'position' => $role['TEN_VAI_TRO'],
                ]);
                break;

            case 'CAN_BO_LOP':
                $statement = $this->db->prepare(
                    'INSERT INTO can_bo_lop
                     (TEN_DANG_NHAP, MA_LOP, CHUC_VU_CB, TRANG_THAI_CB)
                     VALUES (:username, :class_id, :position, :status)'
                );
                $statement->execute([
                    'username' => $username,
                    'class_id' => $data['class_id'],
                    'position' => trim($data['class_position']),
                    'status' => 'HOAT_DONG',
                ]);
                break;

            case 'DOAN_KHOA':
                $statement = $this->db->prepare(
                    'INSERT INTO doan_khoa
                     (TEN_DANG_NHAP, MA_KHOA, TEN_DOAN_KHOA, EMAIL_DK)
                     VALUES (:username, :department_id, :name, :email)'
                );
                $statement->execute([
                    'username' => $username,
                    'department_id' => $data['department_id'],
                    'name' => trim($data['union_faculty_name']),
                    'email' => trim($data['email']),
                ]);
                break;

            case 'LIEN_CHI':
                $statement = $this->db->prepare(
                    'INSERT INTO lien_chi_clb
                     (MA_DOAN_TRUONG, TEN_DANG_NHAP, TEN_LIEN_CHI_CLB, TRANG_THAI_LIEN_CHI_CLB)
                     VALUES (:union_id, :username, :name, :status)'
                );
                $statement->execute([
                    'union_id' => $data['union_id'],
                    'username' => $username,
                    'name' => trim($data['club_name']),
                    'status' => 'HOAT_DONG',
                ]);
                break;

            case 'DOAN_TRUONG':
                $statement = $this->db->prepare(
                    'INSERT INTO doan_truong
                     (TEN_DANG_NHAP, TEN_DOAN_HOI, EMAIL_DT)
                     VALUES (:username, :name, :email)'
                );
                $statement->execute([
                    'username' => $username,
                    'name' => trim($data['union_name']),
                    'email' => trim($data['email']),
                ]);
                break;
        }
    }
}
