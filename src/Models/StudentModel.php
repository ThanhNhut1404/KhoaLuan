<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;
use Throwable;

class StudentModel
{
    public function __construct(private PDO $db)
    {
    }

    public function loadCreateOptions(): array
    {
        $departments = $this->db->query(
            'SELECT MA_KHOA AS id, TEN_KHOA AS name
             FROM khoa_bo_mon
             ORDER BY TEN_KHOA'
        )->fetchAll();

        $majors = $this->db->query(
            'SELECT MA_NGANH AS id, MA_KHOA AS department_id, TEN_NGANH AS name
             FROM nganh_hoc
             ORDER BY TEN_NGANH'
        )->fetchAll();

        $academicYears = $this->db->query(
            'SELECT MA_NIEN_KHOA AS id, TEN_NIEN_KHOA AS name
             FROM nien_khoa
             ORDER BY MA_NIEN_KHOA DESC'
        )->fetchAll();

        $classes = $this->db->query(
            'SELECT lh.MA_LOP AS id,
                    lh.TEN_LOP AS name,
                    lh.MA_KHOA AS department_id,
                    lh.MA_NGANH AS major_id,
                    lh.MA_NIEN_KHOA AS year_id
             FROM lop_hoc lh
             ORDER BY lh.TEN_LOP'
        )->fetchAll();

        return [
            'departments' => $departments,
            'majors' => $majors,
            'academic_years' => $academicYears,
            'classes' => $classes,
        ];
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM sinh_vien')->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $statement = $this->db->prepare(
            'SELECT sv.MA_SV AS id,
                    sv.MSSV AS mssv,
                    sv.TEN_DANG_NHAP AS username,
                    sv.EMAIL_SV AS email,
                    sv.SO_DIEN_THOAI AS phone,
                    sv.KHOA_HOC AS academic_year,
                    sv.TRANG_THAI_HOC_TAP AS status,
                    lh.TEN_LOP AS class_name,
                    sv.MA_LOP AS class_id
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             ORDER BY sv.MSSV DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT sv.MA_SV AS id,
                    sv.MA_LOP AS class_id,
                    sv.TEN_DANG_NHAP AS username,
                    sv.MSSV AS mssv,
                    sv.NGAY_SINH AS birth_date,
                    sv.GIO_TINH AS gender,
                    sv.EMAIL_SV AS email,
                    sv.SO_DIEN_THOAI AS phone,
                    sv.DIA_CHI AS address,
                    sv.KHOA_HOC AS academic_year,
                    sv.TRANG_THAI_HOC_TAP AS status
             FROM sinh_vien sv
             WHERE sv.MA_SV = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function findClassById(int $classId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT lh.MA_LOP AS class_id,
                    lh.MA_KHOA AS department_id,
                    lh.MA_NGANH AS major_id,
                    lh.MA_NIEN_KHOA AS year_id,
                    nk.TEN_NIEN_KHOA AS academic_year_name
             FROM lop_hoc lh
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
             WHERE lh.MA_LOP = :class_id
             LIMIT 1'
        );
        $statement->execute(['class_id' => $classId]);

        return $statement->fetch() ?: null;
    }

    public function findRoleId(string $roleName): ?string
    {
        $statement = $this->db->prepare(
            'SELECT MA_VAI_TRO
             FROM vai_tro
             WHERE TEN_VAI_TRO = :role_name
             LIMIT 1'
        );
        $statement->execute(['role_name' => $roleName]);
        $roleId = $statement->fetchColumn();

        return $roleId === false ? null : (string) $roleId;
    }

    public function usernameExists(string $username, ?int $excludeStudentId = null): bool
    {
        $sql = 'SELECT 1 FROM nguoi_dung WHERE TEN_DANG_NHAP = :username';
        if ($excludeStudentId !== null) {
            $sql .= ' AND TEN_DANG_NHAP <> (
                SELECT TEN_DANG_NHAP FROM sinh_vien WHERE MA_SV = :exclude_id
                LIMIT 1
            )';
        }

        $statement = $this->db->prepare($sql);
        $params = ['username' => $username];
        if ($excludeStudentId !== null) {
            $params['exclude_id'] = $excludeStudentId;
        }
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }

    public function emailExists(string $email, ?int $excludeStudentId = null): bool
    {
        $sql = 'SELECT 1 FROM sinh_vien WHERE EMAIL_SV = :email';
        if ($excludeStudentId !== null) {
            $sql .= ' AND MA_SV <> :exclude_id';
        }

        $statement = $this->db->prepare($sql);
        $params = ['email' => $email];
        if ($excludeStudentId !== null) {
            $params['exclude_id'] = $excludeStudentId;
        }
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }

    public function createStudent(array $data): array
    {
        $classId = (int) $data['class_id'];
        $classInfo = $this->findClassById($classId);
        if ($classInfo === null) {
            throw new \RuntimeException('Lớp học không tồn tại.');
        }

        $roleId = $this->findRoleId('SINH_VIEN');
        if ($roleId === null) {
            throw new \RuntimeException('Vai trò SINH_VIEN chưa được thiết lập trong hệ thống.');
        }

        $mssv = $this->generateMssv($classId, $classInfo);
        $khoaHoc = $this->findAcademicYearNameById((int) ($data['academic_year_id'] ?? 0))
            ?? trim($classInfo['academic_year_name'] ?? '');
        $rawPassword = $this->generatePasswordFromName(trim($data['full_name'] ?? ''));
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $this->db->beginTransaction();

        try {
            $this->db->prepare(
                'INSERT INTO nguoi_dung (TEN_DANG_NHAP, MAT_KHAU, TRANG_THAI_ND)
                 VALUES (:username, :password, :status)'
            )->execute([
                'username' => $mssv,
                'password' => $hashedPassword,
                'status' => 'HOAT_DONG',
            ]);

            $this->db->prepare(
                'INSERT INTO nguoi_dung_vai_tro (TEN_DANG_NHAP, MA_VAI_TRO)
                 VALUES (:username, :role_id)'
            )->execute([
                'username' => $mssv,
                'role_id' => $roleId,
            ]);

            $this->db->prepare(
                'INSERT INTO sinh_vien
                 (MA_LOP, TEN_DANG_NHAP, MSSV, NGAY_SINH, GIO_TINH, EMAIL_SV, SO_DIEN_THOAI, DIA_CHI, KHOA_HOC, TRANG_THAI_HOC_TAP)
                 VALUES (:class_id, :username, :mssv, :birth_date, :gender, :email, :phone, :address, :khoa_hoc, :status)'
            )->execute([
                'class_id' => $classId,
                'username' => $mssv,
                'mssv' => $mssv,
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'email' => trim($data['email']),
                'phone' => trim($data['phone']),
                'address' => trim($data['address']),
                'khoa_hoc' => $khoaHoc,
                'status' => $data['status'],
            ]);

            $this->db->commit();

            return ['created' => true, 'password' => $rawPassword, 'mssv' => $mssv];
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    public function updateStudent(int $id, array $data): bool
    {
        $student = $this->findById($id);
        if ($student === null) {
            return false;
        }

        $classId = (int) $data['class_id'];
        $classInfo = $this->findClassById($classId);
        if ($classInfo === null) {
            throw new \RuntimeException('Lớp học không tồn tại.');
        }

        $this->db->beginTransaction();

        try {
            $currentUsername = $student['username'];
            $updatedUsername = trim($data['username']);

            if ($updatedUsername !== $currentUsername) {
                $this->db->prepare(
                    'UPDATE nguoi_dung
                     SET TEN_DANG_NHAP = :new_username
                     WHERE TEN_DANG_NHAP = :current_username'
                )->execute([
                    'new_username' => $updatedUsername,
                    'current_username' => $currentUsername,
                ]);

                $this->db->prepare(
                    'UPDATE nguoi_dung_vai_tro
                     SET TEN_DANG_NHAP = :new_username
                     WHERE TEN_DANG_NHAP = :current_username'
                )->execute([
                    'new_username' => $updatedUsername,
                    'current_username' => $currentUsername,
                ]);
            }

            $statement = $this->db->prepare(
                'UPDATE sinh_vien
                 SET MA_LOP = :class_id,
                     TEN_DANG_NHAP = :username,
                     NGAY_SINH = :birth_date,
                     GIO_TINH = :gender,
                     EMAIL_SV = :email,
                     SO_DIEN_THOAI = :phone,
                     DIA_CHI = :address,
                     KHOA_HOC = :khoa_hoc,
                     TRANG_THAI_HOC_TAP = :status
                 WHERE MA_SV = :id'
            );

            $statement->execute([
                'class_id' => $classId,
                'username' => $updatedUsername,
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'email' => trim($data['email']),
                'phone' => trim($data['phone']),
                'address' => trim($data['address']),
                'khoa_hoc' => $classInfo['academic_year_name'] ?? '',
                'status' => $data['status'],
                'id' => $id,
            ]);

            $this->db->commit();

            return $statement->rowCount() > 0;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    private function findAcademicYearNameById(int $academicYearId): ?string
    {
        if ($academicYearId < 1) {
            return null;
        }

        $statement = $this->db->prepare(
            'SELECT TEN_NIEN_KHOA
             FROM nien_khoa
             WHERE MA_NIEN_KHOA = :academic_year_id
             LIMIT 1'
        );
        $statement->execute(['academic_year_id' => $academicYearId]);

        $name = $statement->fetchColumn();

        return $name === false ? null : (string) $name;
    }

    public function deleteStudent(int $id): bool
    {
        $student = $this->findById($id);
        if ($student === null) {
            return false;
        }

        $roleId = $this->findRoleId('SINH_VIEN');
        if ($roleId === null) {
            throw new \RuntimeException('Vai trò SINH_VIEN chưa được thiết lập trong hệ thống.');
        }

        $this->db->beginTransaction();

        try {
            $this->db->prepare('DELETE FROM sinh_vien WHERE MA_SV = :id')->execute(['id' => $id]);
            $this->db->prepare(
                'DELETE FROM nguoi_dung_vai_tro
                 WHERE TEN_DANG_NHAP = :username
                   AND MA_VAI_TRO = :role_id'
            )->execute([
                'username' => $student['username'],
                'role_id' => $roleId,
            ]);
            $this->db->prepare('DELETE FROM nguoi_dung WHERE TEN_DANG_NHAP = :username')->execute([
                'username' => $student['username'],
            ]);

            $this->db->commit();

            return true;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    public function isDuplicateException(Throwable $exception): bool
    {
        return $exception instanceof PDOException
            && $exception->getCode() === '23000'
            && (int) ($exception->errorInfo[1] ?? 0) === 1062;
    }

    public function isConstraintException(Throwable $exception): bool
    {
        return $exception instanceof PDOException && $exception->getCode() === '23000';
    }

    private function generateMssv(int $classId, array $classInfo): string
    {
        $prefix = $classInfo['department_id'] . $classInfo['year_id'] . $classInfo['class_id'];
        $latestMssv = $this->findLatestMssvByClass($classId, $prefix);
        $nextId = $this->nextSequentialId($latestMssv);

        return $prefix . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);
    }

    private function generatePasswordFromName(string $fullName): string
    {
        $slug = preg_replace('/[^A-Za-z0-9]/', '', mb_strtolower($fullName));
        if ($slug === '') {
            $slug = 'user';
        }
        return $slug . '#tdu1234';
    }

    private function findLatestMssvByClass(int $classId, string $prefix): ?string
    {
        $statement = $this->db->prepare(
            'SELECT MSSV
             FROM sinh_vien
             WHERE MA_LOP = :class_id
               AND MSSV LIKE :prefix
             ORDER BY MSSV DESC
             LIMIT 1'
        );
        $statement->execute([
            'class_id' => $classId,
            'prefix' => $prefix . '%',
        ]);

        $latest = $statement->fetchColumn();

        return $latest === false ? null : (string) $latest;
    }

    private function nextSequentialId(?string $latestMssv): int
    {
        if ($latestMssv === null) {
            return 1;
        }

        if (preg_match('/(\d+)$/', $latestMssv, $matches)) {
            return (int) $matches[1] + 1;
        }

        return 1;
    }
}
