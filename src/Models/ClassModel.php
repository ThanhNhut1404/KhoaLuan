<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;
use Throwable;

class ClassModel
{
    public function __construct(private PDO $db)
    {
    }

    public function getAcademicYears(): array
    {
        $statement = $this->db->query(
            'SELECT MA_NIEN_KHOA AS id, TEN_NIEN_KHOA AS name
             FROM nien_khoa
             ORDER BY MA_NIEN_KHOA DESC'
        );

        return $statement->fetchAll();
    }

    public function getDepartments(): array
    {
        $statement = $this->db->query(
            'SELECT MA_KHOA AS id, TEN_KHOA AS name
             FROM khoa_bo_mon
             ORDER BY TEN_KHOA'
        );

        return $statement->fetchAll();
    }

    public function getMajors(): array
    {
        $statement = $this->db->query(
            'SELECT MA_NGANH AS id, MA_KHOA AS department_id, TEN_NGANH AS name
             FROM nganh_hoc
             ORDER BY TEN_NGANH'
        );

        return $statement->fetchAll();
    }

    public function academicYearExists(int $id): bool
    {
        return $this->exists('nien_khoa', 'MA_NIEN_KHOA', $id);
    }

    public function departmentExists(int $id): bool
    {
        return $this->exists('khoa_bo_mon', 'MA_KHOA', $id);
    }

    public function majorBelongsToDepartment(int $majorId, int $departmentId): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM nganh_hoc
             WHERE MA_NGANH = :major_id
               AND MA_KHOA = :department_id
             LIMIT 1'
        );
        $statement->execute([
            'major_id' => $majorId,
            'department_id' => $departmentId,
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function codeExists(string $code): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM lop_hoc
             WHERE UPPER(TRIM(TEN_LOP_VIET_TAT)) = :code
             LIMIT 1'
        );
        $statement->execute(['code' => $this->normalizeCode($code)]);

        return (bool) $statement->fetchColumn();
    }

    public function codeExistsExcept(string $code, int $excludeId): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM lop_hoc
             WHERE UPPER(TRIM(TEN_LOP_VIET_TAT)) = :code
               AND MA_LOP <> :exclude_id
             LIMIT 1'
        );
        $statement->execute([
            'code' => $this->normalizeCode($code),
            'exclude_id' => $excludeId,
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO lop_hoc
                (MA_KHOA, MA_NGANH, MA_NIEN_KHOA, TEN_LOP, TEN_LOP_VIET_TAT, SI_SO, TRANG_THAI_LH, GHI_CHU)
             VALUES
                (:department_id, :major_id, :academic_year_id, :name, :code, :capacity, :status, :notes)'
        );
        $created = $statement->execute([
            'department_id' => $data['department_id'],
            'major_id' => $data['major_id'],
            'academic_year_id' => $data['academic_year_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'capacity' => $data['capacity'],
            'status' => $data['status'],
            'notes' => $data['notes'] === '' ? null : $data['notes'],
        ]);

        return $created && $statement->rowCount() > 0;
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM lop_hoc')->fetchColumn();
    }

    public function countFiltered(string $keyword = '', array $filters = []): int
    {
        [$where, $params] = $this->filterClause($keyword, $filters);
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM lop_hoc lh
             LEFT JOIN khoa_bo_mon kbm ON kbm.MA_KHOA = lh.MA_KHOA
             LEFT JOIN nganh_hoc nh ON nh.MA_NGANH = lh.MA_NGANH
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA'
             . $where
        );
        $statement->execute($params);

        return (int) $statement->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $statement = $this->db->prepare(
            'SELECT lh.MA_LOP AS id,
                    lh.TEN_LOP_VIET_TAT AS code,
                    lh.TEN_LOP AS name,
                    kbm.TEN_KHOA AS department,
                    nk.TEN_NIEN_KHOA AS academic_year,
                    nh.TEN_NGANH AS major,
                    lh.SI_SO AS capacity,
                    lh.TRANG_THAI_LH AS status
             FROM lop_hoc lh
             LEFT JOIN khoa_bo_mon kbm ON kbm.MA_KHOA = lh.MA_KHOA
             LEFT JOIN nganh_hoc nh ON nh.MA_NGANH = lh.MA_NGANH
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA
             ORDER BY lh.MA_LOP DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function listFilteredPaginated(int $page, int $perPage, string $keyword = '', array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        [$where, $params] = $this->filterClause($keyword, $filters);
        $statement = $this->db->prepare(
            'SELECT lh.MA_LOP AS id,
                    lh.TEN_LOP_VIET_TAT AS code,
                    lh.TEN_LOP AS name,
                    kbm.TEN_KHOA AS department,
                    nk.TEN_NIEN_KHOA AS academic_year,
                    nh.TEN_NGANH AS major,
                    lh.SI_SO AS capacity,
                    lh.TRANG_THAI_LH AS status
             FROM lop_hoc lh
             LEFT JOIN khoa_bo_mon kbm ON kbm.MA_KHOA = lh.MA_KHOA
             LEFT JOIN nganh_hoc nh ON nh.MA_NGANH = lh.MA_NGANH
             LEFT JOIN nien_khoa nk ON nk.MA_NIEN_KHOA = lh.MA_NIEN_KHOA'
             . $where .
            ' ORDER BY lh.MA_LOP DESC
             LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $name => $value) {
            $statement->bindValue(':' . ltrim((string) $name, ':'), $value);
        }

        $statement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT MA_LOP AS id,
                    MA_KHOA AS department_id,
                    MA_NGANH AS major_id,
                    MA_NIEN_KHOA AS academic_year_id,
                    TEN_LOP_VIET_TAT AS code,
                    TEN_LOP AS name,
                    SI_SO AS capacity,
                    TRANG_THAI_LH AS status,
                    GHI_CHU AS notes
             FROM lop_hoc
             WHERE MA_LOP = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare(
            'UPDATE lop_hoc
             SET MA_KHOA = :department_id,
                 MA_NGANH = :major_id,
                 MA_NIEN_KHOA = :academic_year_id,
                 TEN_LOP = :name,
                 TEN_LOP_VIET_TAT = :code,
                 SI_SO = :capacity,
                 TRANG_THAI_LH = :status,
                 GHI_CHU = :notes
             WHERE MA_LOP = :id'
        );

        return $statement->execute([
            'id' => $id,
            'department_id' => $data['department_id'],
            'major_id' => $data['major_id'],
            'academic_year_id' => $data['academic_year_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'capacity' => $data['capacity'],
            'status' => $data['status'],
            'notes' => $data['notes'] === '' ? null : $data['notes'],
        ]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $statement = $this->db->prepare(
            'UPDATE lop_hoc
             SET TRANG_THAI_LH = :status
             WHERE MA_LOP = :id'
        );
        $statement->execute([
            'id' => $id,
            'status' => $status,
        ]);

        return $statement->rowCount() > 0;
    }

    public function countStudents(int $id): int
    {
        if (!$this->tableHasColumn('sinh_vien', 'MA_LOP')) {
            return 0;
        }

        $statement = $this->db->prepare('SELECT COUNT(*) FROM sinh_vien WHERE MA_LOP = :id');
        $statement->execute(['id' => $id]);

        return (int) $statement->fetchColumn();
    }

    public function hasRelatedData(int $id): bool
    {
        foreach ($this->referencingColumns('lop_hoc', 'MA_LOP') as $reference) {
            if ($this->hasReferenceRows($reference['table'], $reference['column'], $id)) {
                return true;
            }
        }

        return $this->countStudents($id) > 0;
    }

    private function hasReferenceRows(string $table, string $column, int $id): bool
    {
        try {
            $statement = $this->db->prepare(sprintf(
                'SELECT 1 FROM `%s` WHERE `%s` = :id LIMIT 1',
                str_replace('`', '``', $table),
                str_replace('`', '``', $column)
            ));
            $statement->execute(['id' => $id]);

            return (bool) $statement->fetchColumn();
        } catch (Throwable) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM lop_hoc WHERE MA_LOP = :id');
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
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

    private function exists(string $table, string $column, int $id): bool
    {
        $statement = $this->db->prepare(sprintf(
            'SELECT 1 FROM `%s` WHERE `%s` = :id LIMIT 1',
            str_replace('`', '``', $table),
            str_replace('`', '``', $column)
        ));
        $statement->execute(['id' => $id]);

        return (bool) $statement->fetchColumn();
    }

    private function tableHasColumn(string $table, string $column): bool
    {
        try {
            $statement = $this->db->prepare(
                'SELECT 1
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = :table
                   AND COLUMN_NAME = :column
                 LIMIT 1'
            );
            $statement->execute([
                'table' => $table,
                'column' => $column,
            ]);

            return (bool) $statement->fetchColumn();
        } catch (Throwable) {
            return false;
        }
    }

    private function referencingColumns(string $referencedTable, string $referencedColumn): array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT TABLE_NAME AS table_name, COLUMN_NAME AS column_name
                 FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND REFERENCED_TABLE_NAME = :referenced_table
                   AND REFERENCED_COLUMN_NAME = :referenced_column'
            );
            $statement->execute([
                'referenced_table' => $referencedTable,
                'referenced_column' => $referencedColumn,
            ]);

            return array_map(static fn (array $row): array => [
                'table' => (string) ($row['table_name'] ?? ''),
                'column' => (string) ($row['column_name'] ?? ''),
            ], $statement->fetchAll());
        } catch (Throwable) {
            return [];
        }
    }

    private function filterClause(string $keyword, array $filters): array
    {
        $conditions = [];
        $params = [];

        $keyword = trim($keyword);
        if ($keyword !== '') {
            $textKeyword = function_exists('mb_strtolower') ? mb_strtolower($keyword, 'UTF-8') : strtolower($keyword);
            $conditions[] = '(LOWER(lh.TEN_LOP_VIET_TAT) LIKE :keyword_code
                OR LOWER(lh.TEN_LOP) LIKE :keyword_name
                OR LOWER(kbm.TEN_KHOA) LIKE :keyword_department
                OR LOWER(nh.TEN_NGANH) LIKE :keyword_major
                OR LOWER(nk.TEN_NIEN_KHOA) LIKE :keyword_year)';
            $params['keyword_code'] = '%' . $textKeyword . '%';
            $params['keyword_name'] = '%' . $textKeyword . '%';
            $params['keyword_department'] = '%' . $textKeyword . '%';
            $params['keyword_major'] = '%' . $textKeyword . '%';
            $params['keyword_year'] = '%' . $textKeyword . '%';
        }

        $academicYear = trim((string) ($filters['academic_year'] ?? ''));
        if ($academicYear !== '' && ctype_digit($academicYear) && (int) $academicYear > 0) {
            $conditions[] = 'lh.MA_NIEN_KHOA = :academic_year_id';
            $params['academic_year_id'] = (int) $academicYear;
        }

        $status = trim((string) ($filters['status'] ?? ''));
        if ($status !== '') {
            $conditions[] = 'lh.TRANG_THAI_LH = :status';
            $params['status'] = $status;
        }

        return [empty($conditions) ? '' : ' WHERE ' . implode(' AND ', $conditions), $params];
    }

    private function normalizeCode(string $code): string
    {
        $code = trim($code);

        return function_exists('mb_strtoupper') ? mb_strtoupper($code, 'UTF-8') : strtoupper($code);
    }
}
