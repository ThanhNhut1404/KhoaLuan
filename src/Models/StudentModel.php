<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;
use Throwable;

class StudentModel
{
    private const DEFAULT_STUDENT_PASSWORD = '#Tdu1234';
    private const DEFAULT_NOTIFICATION_TYPES = [
        'Hệ thống',
        'Hoạt động',
        'Điểm rèn luyện',
        'Minh chứng',
    ];
    private const DEFAULT_NOTIFICATION_SENDERS = [
        'Phòng CTSV',
        'Đoàn - Hội',
        'Khoa',
        'Cố vấn học tập',
        'Lớp trưởng',
    ];

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

    public function countFiltered(array $filters): int
    {
        [$whereSql, $params] = $this->buildListFilterWhere($filters);
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             ' . $whereSql
        );
        $statement->execute($params);

        return (int) $statement->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $statement = $this->db->prepare(
            'SELECT sv.MA_SV AS id,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS full_name,
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

    public function listFilteredPaginated(int $page, int $perPage, array $filters): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        [$whereSql, $params] = $this->buildListFilterWhere($filters);
        $statement = $this->db->prepare(
            'SELECT sv.MA_SV AS id,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS full_name,
                    sv.TEN_DANG_NHAP AS username,
                    sv.EMAIL_SV AS email,
                    sv.SO_DIEN_THOAI AS phone,
                    sv.KHOA_HOC AS academic_year,
                    sv.TRANG_THAI_HOC_TAP AS status,
                    lh.TEN_LOP AS class_name,
                    sv.MA_LOP AS class_id
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             ' . $whereSql . '
             ORDER BY sv.MSSV DESC
             LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getListFilterOptions(): array
    {
        $classes = $this->db->query(
            'SELECT DISTINCT lh.MA_LOP AS id,
                    lh.TEN_LOP AS name
             FROM lop_hoc lh
             INNER JOIN sinh_vien sv ON sv.MA_LOP = lh.MA_LOP
             ORDER BY lh.TEN_LOP'
        )->fetchAll();

        $academicYears = $this->db->query(
            "SELECT DISTINCT KHOA_HOC AS name
             FROM sinh_vien
             WHERE KHOA_HOC IS NOT NULL
               AND TRIM(KHOA_HOC) <> ''
             ORDER BY KHOA_HOC DESC"
        )->fetchAll();

        return [
            'classes' => $classes,
            'academic_years' => $academicYears,
        ];
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT sv.MA_SV AS id,
                    sv.MA_LOP AS class_id,
                    lh.MA_KHOA AS department_id,
                    lh.MA_NGANH AS major_id,
                    lh.MA_NIEN_KHOA AS academic_year_id,
                    sv.TEN_DANG_NHAP AS username,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS full_name,
                    sv.NGAY_SINH AS birth_date,
                    sv.GIO_TINH AS gender,
                    sv.EMAIL_SV AS email,
                    sv.SO_DIEN_THOAI AS phone,
                    sv.DIA_CHI AS address,
                    sv.KHOA_HOC AS academic_year,
                    sv.TRANG_THAI_HOC_TAP AS status
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             WHERE sv.MA_SV = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function findStudentAccountForLogin(string $login): ?array
    {
        $avatarSelect = $this->tableHasColumn('sinh_vien', 'AVATAR')
            ? 'sv.AVATAR AS avatar,'
            : "'' AS avatar,";
        $themeColorSelect = $this->tableHasColumn('nguoi_dung', 'theme_color')
            ? 'nd.theme_color AS theme_color,'
            : "'blue' AS theme_color,";

        $statement = $this->db->prepare(
            "SELECT nd.TEN_DANG_NHAP AS username,
                    nd.MAT_KHAU AS password_hash,
                    nd.TRANG_THAI_ND AS account_status,
                    {$themeColorSelect}
                    vt.TEN_VAI_TRO AS role_name,
                    sv.MA_SV AS student_id,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS ho_ten,
                    {$avatarSelect}
                    sv.TEN_DANG_NHAP AS student_username
             FROM nguoi_dung nd
             INNER JOIN nguoi_dung_vai_tro ndvt ON ndvt.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             INNER JOIN vai_tro vt ON vt.MA_VAI_TRO = ndvt.MA_VAI_TRO
             INNER JOIN sinh_vien sv ON (
                sv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
                OR sv.MSSV = nd.TEN_DANG_NHAP
             )
             WHERE (
                nd.TEN_DANG_NHAP = :login_username
                OR sv.TEN_DANG_NHAP = :login_student_username
                OR sv.MSSV = :login_mssv
             )
               AND vt.TEN_VAI_TRO = 'SINH_VIEN'
             LIMIT 1"
        );
        $statement->execute([
            'login_username' => $login,
            'login_student_username' => $login,
            'login_mssv' => $login,
        ]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function findPortalStudentById(int $id): ?array
    {
        $avatarSelect = $this->tableHasColumn('sinh_vien', 'AVATAR')
            ? 'sv.AVATAR AS avatar,'
            : "'' AS avatar,";

        $statement = $this->db->prepare(
            "SELECT sv.MA_SV AS student_id,
                    sv.MA_LOP AS ma_lop,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS ho_ten,
                    sv.TEN_DANG_NHAP AS username,
                    sv.NGAY_SINH AS ngay_sinh,
                    sv.GIO_TINH AS gioi_tinh,
                    sv.EMAIL_SV AS email,
                    sv.SO_DIEN_THOAI AS so_dien_thoai,
                    sv.DIA_CHI AS dia_chi,
                    sv.DIA_CHI AS dia_chi_thuong_tru,
                    sv.KHOA_HOC AS khoa_hoc,
                    sv.TRANG_THAI_HOC_TAP AS trang_thai_hoc_tap,
                    sv.TRANG_THAI_HOC_TAP AS trang_thai,
                    {$avatarSelect}
                    hk.TEN_HOC_KY AS hoc_ky,
                    hk.THOI_GIAN_BDHK AS thoi_gian_bat_dau_danh_gia,
                    hk.THOI_GIAN_KTHK AS thoi_gian_ket_thuc_danh_gia,
                    lh.TEN_LOP AS lop_hoc,
                    kbm.TEN_KHOA AS khoa,
                    nh.TEN_NGANH AS nganh,
                    nk.TEN_NIEN_KHOA AS nien_khoa
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             LEFT JOIN khoa_bo_mon kbm ON kbm.MA_KHOA = lh.MA_KHOA
             LEFT JOIN nganh_hoc nh ON nh.MA_NGANH = lh.MA_NGANH
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
             LEFT JOIN hoc_ky hk ON hk.MA_HOC_KY = (
                SELECT h2.MA_HOC_KY
                FROM hoc_ky h2
                WHERE h2.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
                ORDER BY CASE
                    WHEN CURDATE() BETWEEN h2.THOI_GIAN_BDHK AND h2.THOI_GIAN_KTHK THEN 0
                    ELSE 1
                END, h2.MA_HOC_KY DESC
                LIMIT 1
             )
             WHERE sv.MA_SV = :id
             LIMIT 1"
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function findPortalStudentByLogin(string $login): ?array
    {
        $login = trim($login);
        if ($login === '') {
            return null;
        }

        $avatarSelect = $this->tableHasColumn('sinh_vien', 'AVATAR')
            ? 'sv.AVATAR AS avatar,'
            : "'' AS avatar,";

        $statement = $this->db->prepare(
            "SELECT sv.MA_SV AS student_id,
                    sv.MA_LOP AS ma_lop,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS ho_ten,
                    sv.TEN_DANG_NHAP AS username,
                    sv.NGAY_SINH AS ngay_sinh,
                    sv.GIO_TINH AS gioi_tinh,
                    sv.EMAIL_SV AS email,
                    sv.SO_DIEN_THOAI AS so_dien_thoai,
                    sv.DIA_CHI AS dia_chi,
                    sv.DIA_CHI AS dia_chi_thuong_tru,
                    sv.KHOA_HOC AS khoa_hoc,
                    sv.TRANG_THAI_HOC_TAP AS trang_thai_hoc_tap,
                    sv.TRANG_THAI_HOC_TAP AS trang_thai,
                    {$avatarSelect}
                    hk.TEN_HOC_KY AS hoc_ky,
                    hk.THOI_GIAN_BDHK AS thoi_gian_bat_dau_danh_gia,
                    hk.THOI_GIAN_KTHK AS thoi_gian_ket_thuc_danh_gia,
                    lh.TEN_LOP AS lop_hoc,
                    kbm.TEN_KHOA AS khoa,
                    nh.TEN_NGANH AS nganh,
                    nk.TEN_NIEN_KHOA AS nien_khoa
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             LEFT JOIN khoa_bo_mon kbm ON kbm.MA_KHOA = lh.MA_KHOA
             LEFT JOIN nganh_hoc nh ON nh.MA_NGANH = lh.MA_NGANH
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
             LEFT JOIN hoc_ky hk ON hk.MA_HOC_KY = (
                SELECT h2.MA_HOC_KY
                FROM hoc_ky h2
                WHERE h2.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
                ORDER BY CASE
                    WHEN CURDATE() BETWEEN h2.THOI_GIAN_BDHK AND h2.THOI_GIAN_KTHK THEN 0
                    ELSE 1
                END, h2.MA_HOC_KY DESC
                LIMIT 1
             )
             WHERE sv.TEN_DANG_NHAP = :login_username
                OR sv.MSSV = :login_mssv
             LIMIT 1"
        );
        $statement->execute([
            'login_username' => $login,
            'login_mssv' => $login,
        ]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function updateAccountPassword(int $studentId, string $username, string $passwordHash): bool
    {
        $statement = $this->db->prepare(
            'UPDATE nguoi_dung nd
             INNER JOIN sinh_vien sv ON (
                sv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
                OR sv.MSSV = nd.TEN_DANG_NHAP
             )
             SET nd.MAT_KHAU = :password
             WHERE nd.TEN_DANG_NHAP = :username
               AND sv.MA_SV = :student_id'
        );
        $statement->execute([
            'password' => $passwordHash,
            'username' => $username,
            'student_id' => $studentId,
        ]);

        return $statement->rowCount() > 0;
    }

    public function updateAccountThemeColor(int $studentId, string $username, string $themeColor): bool
    {
        $statement = $this->db->prepare(
            'UPDATE nguoi_dung nd
             INNER JOIN sinh_vien sv ON (
                sv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
                OR sv.MSSV = nd.TEN_DANG_NHAP
             )
             SET nd.theme_color = :theme_color
             WHERE nd.TEN_DANG_NHAP = :username
               AND sv.MA_SV = :student_id'
        );
        $statement->execute([
            'theme_color' => $themeColor,
            'username' => $username,
            'student_id' => $studentId,
        ]);

        return $statement->rowCount() > 0 || $this->accountThemeColorMatches($studentId, $username, $themeColor);
    }

    private function accountThemeColorMatches(int $studentId, string $username, string $themeColor): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM nguoi_dung nd
             INNER JOIN sinh_vien sv ON (
                sv.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
                OR sv.MSSV = nd.TEN_DANG_NHAP
             )
             WHERE nd.TEN_DANG_NHAP = :username
               AND sv.MA_SV = :student_id
               AND nd.theme_color = :theme_color
             LIMIT 1'
        );
        $statement->execute([
            'username' => $username,
            'student_id' => $studentId,
            'theme_color' => $themeColor,
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function departmentExists(int $departmentId): bool
    {
        if ($departmentId < 1) {
            return false;
        }

        $statement = $this->db->prepare(
            'SELECT 1
             FROM khoa_bo_mon
             WHERE MA_KHOA = :department_id
             LIMIT 1'
        );
        $statement->execute(['department_id' => $departmentId]);

        return (bool) $statement->fetchColumn();
    }

    public function findMajorById(int $majorId): ?array
    {
        if ($majorId < 1) {
            return null;
        }

        $statement = $this->db->prepare(
            'SELECT MA_NGANH AS major_id,
                    MA_KHOA AS department_id,
                    TEN_NGANH AS major_name
             FROM nganh_hoc
             WHERE MA_NGANH = :major_id
             LIMIT 1'
        );
        $statement->execute(['major_id' => $majorId]);

        return $statement->fetch() ?: null;
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
        $departmentId = (int) ($data['department_id'] ?? 0);
        $majorId = (int) ($data['major_id'] ?? 0);
        $academicYearId = (int) ($data['academic_year_id'] ?? 0);
        $classId = (int) $data['class_id'];

        $majorInfo = $this->findMajorById($majorId);
        if ($majorInfo === null || (int) $majorInfo['department_id'] !== $departmentId) {
            throw new \RuntimeException('Ngành học không thuộc khoa/bộ môn đã chọn.');
        }

        $classInfo = $this->findClassById($classId);
        if ($classInfo === null) {
            throw new \RuntimeException('Lớp học không tồn tại.');
        }
        if ((int) $classInfo['department_id'] !== $departmentId || (int) $classInfo['major_id'] !== $majorId) {
            throw new \RuntimeException('Lớp học không thuộc ngành học đã chọn.');
        }
        if ((int) ($classInfo['year_id'] ?? 0) !== $academicYearId) {
            throw new \RuntimeException('Lớp học không thuộc niên khóa đã chọn.');
        }

        $roleId = $this->findRoleId('SINH_VIEN');
        if ($roleId === null) {
            throw new \RuntimeException('Vai trò SINH_VIEN chưa được thiết lập trong hệ thống.');
        }

        $mssv = $this->generateMssv($majorId, $academicYearId);
        $khoaHoc = $this->findAcademicYearNameById($academicYearId)
            ?? trim($classInfo['academic_year_name'] ?? '');
        $rawPassword = self::DEFAULT_STUDENT_PASSWORD;
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
                 (MA_LOP, TEN_DANG_NHAP, MSSV, HO_TEN, NGAY_SINH, GIO_TINH, EMAIL_SV, SO_DIEN_THOAI, DIA_CHI, KHOA_HOC, TRANG_THAI_HOC_TAP)
                 VALUES (:class_id, :username, :mssv, :full_name, :birth_date, :gender, :email, :phone, :address, :khoa_hoc, :status)'
            )->execute([
                'class_id' => $classId,
                'username' => $mssv,
                'mssv' => $mssv,
                'full_name' => trim($data['full_name']),
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
            if ((int) ($classInfo['department_id'] ?? 0) !== (int) ($data['department_id'] ?? 0)) {
                throw new \RuntimeException('Lớp học không thuộc khoa/bộ môn đã chọn.');
            }
            if ((int) ($classInfo['major_id'] ?? 0) !== (int) ($data['major_id'] ?? 0)) {
                throw new \RuntimeException('Lớp học không thuộc ngành học đã chọn.');
            }
            if ((int) ($classInfo['year_id'] ?? 0) !== (int) ($data['academic_year_id'] ?? 0)) {
                throw new \RuntimeException('Lớp học không thuộc niên khóa đã chọn.');
            }

            $updatedUsername = (string) $student['username'];
            $fullName = trim((string) ($data['full_name'] ?? ($student['full_name'] ?? '')));

            $statement = $this->db->prepare(
                'UPDATE sinh_vien
                 SET MA_LOP = :class_id,
                     TEN_DANG_NHAP = :username,
                     HO_TEN = :full_name,
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
                'full_name' => $fullName,
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

    public function updateStudentStatus(int $id, string $status): bool
    {
        $student = $this->findById($id);
        if ($student === null) {
            return false;
        }

        $statement = $this->db->prepare(
            'UPDATE sinh_vien
             SET TRANG_THAI_HOC_TAP = :status
             WHERE MA_SV = :id'
        );
        $statement->execute([
            'status' => $status,
            'id' => $id,
        ]);

        return $statement->rowCount() > 0;
    }

    public function updatePortalStudentProfile(int $id, array $data): bool
    {
        $sets = [
            'HO_TEN = :full_name',
            'NGAY_SINH = :birth_date',
            'GIO_TINH = :gender',
            'EMAIL_SV = :email',
            'SO_DIEN_THOAI = :phone',
            'DIA_CHI = :address',
        ];
        $params = [
            'full_name' => trim((string) ($data['full_name'] ?? '')),
            'birth_date' => trim((string) ($data['birth_date'] ?? '')),
            'gender' => trim((string) ($data['gender'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'phone' => trim((string) ($data['phone'] ?? '')),
            'address' => trim((string) ($data['address'] ?? '')),
            'id' => $id,
        ];

        if (array_key_exists('avatar', $data) && $this->tableHasColumn('sinh_vien', 'AVATAR')) {
            $sets[] = 'AVATAR = :avatar';
            $params['avatar'] = trim((string) $data['avatar']);
        }

        $statement = $this->db->prepare(
            'UPDATE sinh_vien
             SET ' . implode(', ', $sets) . '
             WHERE MA_SV = :id'
        );
        $statement->execute($params);

        return $statement->rowCount() > 0;
    }

    public function updateStudentAvatar(int $id, string $avatar): bool
    {
        if (!$this->tableHasColumn('sinh_vien', 'AVATAR')) {
            return false;
        }

        $statement = $this->db->prepare(
            'UPDATE sinh_vien
             SET AVATAR = :avatar
             WHERE MA_SV = :id'
        );
        $statement->execute([
            'avatar' => trim($avatar),
            'id' => $id,
        ]);

        return $statement->rowCount() > 0;
    }

    public function studentAvatarColumnExists(): bool
    {
        return $this->tableHasColumn('sinh_vien', 'AVATAR');
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

    public function listPortalNotifications(string $username, array $filters = []): array
    {
        [$whereSql, $params] = $this->buildNotificationFilterWhere($username, $filters);
        $typeExpression = $this->notificationTypeExpression();

        $statement = $this->db->prepare(
            "SELECT tb.MA_THONG_BAO AS id,
                    tb.NGUOI_GUI AS sender,
                    tb.TIEU_DE AS title,
                    tb.NOI_DUNG_TB AS body,
                    tb.NGAY_TAO_TB AS created_at,
                    tb.FILE_DINH_KEM AS attachment,
                    tb.MUC_DO_UU_TIEN AS priority,
                    tb.TRANG_THAI_TB AS notification_status,
                    tb.MA_HOAT_DONG AS activity_id,
                    COALESCE(n.DA_DOC, 0) AS is_read,
                    {$typeExpression} AS type_name
             FROM thong_bao tb
             LEFT JOIN nhan n
                ON n.MA_THONG_BAO = tb.MA_THONG_BAO
               AND n.TEN_DANG_NHAP = :recipient_join
             {$whereSql}
             ORDER BY tb.NGAY_TAO_TB DESC, tb.MA_THONG_BAO DESC"
        );

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue('recipient_join', $username);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getPortalNotificationFilterOptions(string $username): array
    {
        $visibilitySql = $this->notificationVisibilitySql();
        $typeExpression = $this->notificationTypeExpression();

        $typeStatement = $this->db->prepare(
            "SELECT DISTINCT {$typeExpression} AS value
             FROM thong_bao tb
             LEFT JOIN nhan n
                ON n.MA_THONG_BAO = tb.MA_THONG_BAO
               AND n.TEN_DANG_NHAP = :recipient_join
             WHERE {$visibilitySql}
             HAVING value IS NOT NULL AND TRIM(value) <> ''
             ORDER BY value"
        );
        $typeStatement->execute([
            'recipient_join' => $username,
            'recipient_exists' => $username,
        ]);

        $senderStatement = $this->db->prepare(
            "SELECT DISTINCT tb.NGUOI_GUI AS value
             FROM thong_bao tb
             LEFT JOIN nhan n
                ON n.MA_THONG_BAO = tb.MA_THONG_BAO
               AND n.TEN_DANG_NHAP = :recipient_join
             WHERE {$visibilitySql}
               AND tb.NGUOI_GUI IS NOT NULL
               AND TRIM(tb.NGUOI_GUI) <> ''
             ORDER BY tb.NGUOI_GUI"
        );
        $senderStatement->execute([
            'recipient_join' => $username,
            'recipient_exists' => $username,
        ]);

        return [
            'types' => $this->buildNotificationOptions(
                array_column($typeStatement->fetchAll(), 'value'),
                self::DEFAULT_NOTIFICATION_TYPES
            ),
            'senders' => $this->buildNotificationOptions(
                array_column($senderStatement->fetchAll(), 'value'),
                self::DEFAULT_NOTIFICATION_SENDERS
            ),
        ];
    }

    public function markPortalNotificationAsRead(string $username, int $notificationId): bool
    {
        if ($notificationId < 1 || $username === '') {
            return false;
        }

        $statement = $this->db->prepare(
            'UPDATE nhan
             SET DA_DOC = 1
             WHERE MA_THONG_BAO = :notification_id
               AND TEN_DANG_NHAP = :username'
        );
        $statement->execute([
            'notification_id' => $notificationId,
            'username' => $username,
        ]);

        return $statement->rowCount() > 0;
    }

    private function buildNotificationFilterWhere(string $username, array $filters): array
    {
        $where = [$this->notificationVisibilitySql()];
        $params = ['recipient_exists' => $username];

        $readStatus = trim((string) ($filters['read_status'] ?? ''));
        if ($readStatus === 'unread') {
            $where[] = 'COALESCE(n.DA_DOC, 0) = 0';
        } elseif ($readStatus === 'read') {
            $where[] = 'COALESCE(n.DA_DOC, 0) = 1';
        }

        $type = trim((string) ($filters['type'] ?? ''));
        if ($type !== '') {
            $where[] = $this->notificationTypeExpression() . ' = :notification_type';
            $params['notification_type'] = $type;
        }

        $sender = trim((string) ($filters['sender'] ?? ''));
        if ($sender !== '') {
            $where[] = 'tb.NGUOI_GUI = :notification_sender';
            $params['notification_sender'] = $sender;
        }

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $where[] = '(tb.TIEU_DE LIKE :notification_keyword
                OR tb.NOI_DUNG_TB LIKE :notification_keyword
                OR tb.NGUOI_GUI LIKE :notification_keyword)';
            $params['notification_keyword'] = '%' . $keyword . '%';
        }

        return ['WHERE ' . implode(' AND ', $where), $params];
    }

    private function notificationVisibilitySql(): string
    {
        return '(n.TEN_DANG_NHAP IS NOT NULL
            OR NOT EXISTS (
                SELECT 1
                FROM nhan n_all
                WHERE n_all.MA_THONG_BAO = tb.MA_THONG_BAO
                  AND n_all.TEN_DANG_NHAP <> :recipient_exists
            ))';
    }

    private function notificationTypeExpression(): string
    {
        if ($this->tableHasColumn('thong_bao', 'LOAI_THONG_BAO')) {
            return 'tb.LOAI_THONG_BAO';
        }

        return "CASE WHEN tb.MA_HOAT_DONG IS NOT NULL THEN 'Hoạt động' ELSE 'Hệ thống' END";
    }

    private function buildNotificationOptions(array $dbValues, array $defaultValues): array
    {
        $values = [];

        foreach (array_merge($dbValues, $defaultValues) as $value) {
            $value = trim((string) $value);
            if ($value === '' || isset($values[$value])) {
                continue;
            }

            $values[$value] = ['value' => $value, 'label' => $value];
        }

        return array_values($values);
    }

    private function buildListFilterWhere(array $filters): array
    {
        $where = [];
        $params = [];

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $where[] = '(sv.MSSV LIKE :keyword
                OR sv.TEN_DANG_NHAP LIKE :keyword
                OR sv.EMAIL_SV LIKE :keyword
                OR sv.SO_DIEN_THOAI LIKE :keyword
                OR lh.TEN_LOP LIKE :keyword)';
            $params['keyword'] = '%' . $keyword . '%';
        }

        $classId = trim((string) ($filters['class_id'] ?? ''));
        if ($classId !== '' && ctype_digit($classId)) {
            $where[] = 'sv.MA_LOP = :class_id';
            $params['class_id'] = (int) $classId;
        }

        $academicYear = trim((string) ($filters['academic_year'] ?? ''));
        if ($academicYear !== '') {
            $where[] = 'sv.KHOA_HOC = :academic_year';
            $params['academic_year'] = $academicYear;
        }

        $status = trim((string) ($filters['status'] ?? ''));
        if ($status !== '') {
            $where[] = 'sv.TRANG_THAI_HOC_TAP = :status';
            $params['status'] = $status;
        }

        return [
            empty($where) ? '' : 'WHERE ' . implode(' AND ', $where),
            $params,
        ];
    }

    private function generateMssv(int $majorId, int $academicYearId): string
    {
        $majorCode = $this->formatMssvPart($majorId, 'Mã ngành không hợp lệ để sinh MSSV.');
        $academicYearCode = $this->formatMssvPart($academicYearId, 'Mã niên khóa không hợp lệ để sinh MSSV.');
        $prefix = $majorCode . $academicYearCode;
        $nextSequence = $this->nextMssvSequence($majorId, $academicYearId, $prefix);

        while ($nextSequence <= 9999) {
            $mssv = $prefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
            if (!$this->mssvOrUsernameExists($mssv)) {
                return $mssv;
            }

            $nextSequence++;
        }

        throw new \RuntimeException('Không thể tạo MSSV mới cho ngành và niên khóa đã chọn.');
    }

    private function formatMssvPart(int $value, string $errorMessage): string
    {
        if ($value < 0 || $value > 99) {
            throw new \RuntimeException($errorMessage);
        }

        return str_pad((string) $value, 2, '0', STR_PAD_LEFT);
    }

    private function nextMssvSequence(int $majorId, int $academicYearId, string $prefix): int
    {
        $statement = $this->db->prepare(
            'SELECT COALESCE(MAX(CAST(SUBSTRING(sv.MSSV, 5, 4) AS UNSIGNED)), 0) AS max_sequence
             FROM sinh_vien sv
             INNER JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             WHERE lh.MA_NGANH = :major_id
               AND lh.MA_NIEN_KHOA = :academic_year_id
               AND sv.MSSV LIKE :prefix
               AND CHAR_LENGTH(sv.MSSV) = 8'
        );
        $statement->execute([
            'major_id' => $majorId,
            'academic_year_id' => $academicYearId,
            'prefix' => $prefix . '%',
        ]);

        return ((int) $statement->fetchColumn()) + 1;
    }

    private function mssvOrUsernameExists(string $mssv): bool
    {
        $studentStatement = $this->db->prepare(
            'SELECT 1
             FROM sinh_vien
             WHERE MSSV = :mssv
             LIMIT 1'
        );
        $studentStatement->execute(['mssv' => $mssv]);
        if ($studentStatement->fetchColumn()) {
            return true;
        }

        $accountStatement = $this->db->prepare(
            'SELECT 1
             FROM nguoi_dung
             WHERE TEN_DANG_NHAP = :username
             LIMIT 1'
        );
        $accountStatement->execute(['username' => $mssv]);

        return (bool) $accountStatement->fetchColumn();
    }

    private function tableHasColumn(string $table, string $column): bool
    {
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = :table_name
               AND COLUMN_NAME = :column_name'
        );
        $statement->execute([
            'table_name' => $table,
            'column_name' => $column,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }
}
