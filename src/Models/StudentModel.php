<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;
use Throwable;

class StudentModel
{
    private const DEFAULT_STUDENT_PASSWORD = '#Tdu1234';

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
        $statement = $this->db->prepare(
            "SELECT nd.TEN_DANG_NHAP AS username,
                    nd.MAT_KHAU AS password_hash,
                    nd.TRANG_THAI_ND AS account_status,
                    vt.TEN_VAI_TRO AS role_name,
                    sv.MA_SV AS student_id,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS ho_ten,
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
                    lh.TEN_LOP AS lop_hoc,
                    nh.TEN_NGANH AS nganh,
                    nk.TEN_NIEN_KHOA AS nien_khoa
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             LEFT JOIN nganh_hoc nh ON nh.MA_NGANH = lh.MA_NGANH
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
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
                    lh.TEN_LOP AS lop_hoc,
                    nh.TEN_NGANH AS nganh,
                    nk.TEN_NIEN_KHOA AS nien_khoa
             FROM sinh_vien sv
             LEFT JOIN lop_hoc lh ON lh.MA_LOP = sv.MA_LOP
             LEFT JOIN nganh_hoc nh ON nh.MA_NGANH = lh.MA_NGANH
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
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

    public function updateAccountPassword(string $username, string $passwordHash): bool
    {
        $statement = $this->db->prepare(
            'UPDATE nguoi_dung
             SET MAT_KHAU = :password
             WHERE TEN_DANG_NHAP = :username'
        );
        $statement->execute([
            'password' => $passwordHash,
            'username' => $username,
        ]);

        return $statement->rowCount() > 0;
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
