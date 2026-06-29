<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;
use Throwable;

class MajorModel
{
    public function __construct(private PDO $db)
    {
    }

    public function getDepartments(): array
    {
        $statement = $this->db->query(
            'SELECT MA_KHOA, TEN_KHOA FROM khoa_bo_mon ORDER BY TEN_KHOA'
        );

        return $statement->fetchAll();
    }

    public function departmentExists(int $departmentId): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1 FROM khoa_bo_mon WHERE MA_KHOA = :department_id LIMIT 1'
        );
        $statement->execute(['department_id' => $departmentId]);

        return (bool) $statement->fetchColumn();
    }

    public function existsByNameInDepartment(string $name, int $departmentId): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM nganh_hoc
             WHERE MA_KHOA = :department_id
               AND LOWER(REPLACE(TRIM(TEN_NGANH), " ", "")) = :name
             LIMIT 1'
        );
        $statement->execute([
            'department_id' => $departmentId,
            'name' => $this->normalizeNameForLookup($name),
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function existsByCode(string $code): bool
    {
        if ($code === '') {
            return false;
        }

        $statement = $this->db->prepare(
            'SELECT 1
             FROM nganh_hoc
             WHERE UPPER(TRIM(TEN_VIET_TAT)) = :code
             LIMIT 1'
        );
        $statement->execute(['code' => $this->normalizeCode($code)]);

        return (bool) $statement->fetchColumn();
    }

    public function existsByNameInDepartmentExcept(string $name, int $departmentId, int $excludeId): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM nganh_hoc
             WHERE MA_KHOA = :department_id
               AND LOWER(REPLACE(TRIM(TEN_NGANH), " ", "")) = :name
               AND MA_NGANH <> :exclude_id
             LIMIT 1'
        );
        $statement->execute([
            'department_id' => $departmentId,
            'name' => $this->normalizeNameForLookup($name),
            'exclude_id' => $excludeId,
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function existsByCodeExcept(string $code, int $excludeId): bool
    {
        if ($code === '') {
            return false;
        }

        $statement = $this->db->prepare(
            'SELECT 1
             FROM nganh_hoc
             WHERE UPPER(TRIM(TEN_VIET_TAT)) = :code
               AND MA_NGANH <> :exclude_id
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
            'INSERT INTO nganh_hoc
                (MA_KHOA, TEN_NGANH, TEN_VIET_TAT, MO_TA, TRANG_THAI)
             VALUES
                (:department_id, :name, :short_name, :description, :status)'
        );
        $created = $statement->execute([
            'department_id' => $data['department_id'],
            'name' => $data['name'],
            'short_name' => $data['short_name'] === '' ? null : $data['short_name'],
            'description' => $data['description'] === '' ? null : $data['description'],
            'status' => $data['status'],
        ]);

        return $created && $statement->rowCount() > 0;
    }

    public function countAll(): int
    {
        $statement = $this->db->query('SELECT COUNT(*) FROM nganh_hoc');

        return (int) $statement->fetchColumn();
    }

    public function countFiltered(string $keyword = '', string $status = ''): int
    {
        [$where, $params] = $this->filterClause($keyword, $status);
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM nganh_hoc
             LEFT JOIN khoa_bo_mon ON khoa_bo_mon.MA_KHOA = nganh_hoc.MA_KHOA'
             . $where
        );
        $statement->execute($params);

        return (int) $statement->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $statement = $this->db->prepare(
            'SELECT MA_NGANH AS id,
                    nganh_hoc.TEN_VIET_TAT AS code,
                    nganh_hoc.TEN_NGANH AS name,
                    nganh_hoc.MA_KHOA AS department_id,
                    khoa_bo_mon.TEN_VIET_TAT_KHOA AS department_code,
                    khoa_bo_mon.TEN_KHOA AS department_name,
                    nganh_hoc.TRANG_THAI AS status
             FROM nganh_hoc
             LEFT JOIN khoa_bo_mon ON khoa_bo_mon.MA_KHOA = nganh_hoc.MA_KHOA
             ORDER BY nganh_hoc.MA_NGANH DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function listFilteredPaginated(int $page, int $perPage, string $keyword = '', string $status = ''): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        [$where, $params] = $this->filterClause($keyword, $status);
        $statement = $this->db->prepare(
            'SELECT MA_NGANH AS id,
                    nganh_hoc.TEN_VIET_TAT AS code,
                    nganh_hoc.TEN_NGANH AS name,
                    nganh_hoc.MA_KHOA AS department_id,
                    khoa_bo_mon.TEN_VIET_TAT_KHOA AS department_code,
                    khoa_bo_mon.TEN_KHOA AS department_name,
                    nganh_hoc.TRANG_THAI AS status
             FROM nganh_hoc
             LEFT JOIN khoa_bo_mon ON khoa_bo_mon.MA_KHOA = nganh_hoc.MA_KHOA'
             . $where .
            ' ORDER BY nganh_hoc.MA_NGANH DESC
             LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $name => $value) {
            $statement->bindValue(':' . ltrim((string) $name, ':'), $value);
        }

        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $statement = $this->db->prepare(
            'UPDATE nganh_hoc
             SET TRANG_THAI = :status
             WHERE MA_NGANH = :id'
        );
        $statement->execute([
            'id' => $id,
            'status' => $status,
        ]);

        return $statement->rowCount() > 0;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT MA_NGANH AS id,
                    MA_KHOA AS department_id,
                    TEN_NGANH AS name,
                    TEN_VIET_TAT AS code,
                    MO_TA AS description,
                    TRANG_THAI AS status
             FROM nganh_hoc
             WHERE MA_NGANH = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare(
            'UPDATE nganh_hoc
             SET MA_KHOA = :department_id,
                 TEN_NGANH = :name,
                 TEN_VIET_TAT = :short_name,
                 MO_TA = :description,
                 TRANG_THAI = :status
             WHERE MA_NGANH = :id'
        );

        return $statement->execute([
            'id' => $id,
            'department_id' => $data['department_id'],
            'name' => $data['name'],
            'short_name' => $data['short_name'] === '' ? null : $data['short_name'],
            'description' => $data['description'] === '' ? null : $data['description'],
            'status' => $data['status'],
        ]);
    }

    public function hasRelatedData(int $id): bool
    {
        foreach ($this->relatedChecks() as [$table, $column]) {
            if (!$this->tableExists($table) || !$this->hasColumn($table, $column)) {
                continue;
            }

            $statement = $this->db->prepare(sprintf(
                'SELECT 1 FROM `%s` WHERE `%s` = :id LIMIT 1',
                str_replace('`', '``', $table),
                str_replace('`', '``', $column)
            ));
            $statement->execute(['id' => $id]);

            if ($statement->fetchColumn()) {
                return true;
            }
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM nganh_hoc WHERE MA_NGANH = :id');
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

    private function filterClause(string $keyword, string $status): array
    {
        $conditions = [];
        $params = [];

        $keyword = preg_replace('/\s+/u', ' ', trim($keyword));
        if ($keyword !== '') {
            $textKeyword = function_exists('mb_strtolower') ? mb_strtolower($keyword, 'UTF-8') : strtolower($keyword);

            $conditions[] = '(LOWER(nganh_hoc.TEN_NGANH) LIKE :keyword_name
                OR LOWER(nganh_hoc.TEN_VIET_TAT) LIKE :keyword_code
                OR CAST(nganh_hoc.MA_NGANH AS CHAR) LIKE :keyword_id
                OR LOWER(khoa_bo_mon.TEN_VIET_TAT_KHOA) LIKE :keyword_department_code
                OR LOWER(khoa_bo_mon.TEN_KHOA) LIKE :keyword_department_name)';
            $params['keyword_name'] = '%' . $textKeyword . '%';
            $params['keyword_code'] = '%' . $textKeyword . '%';
            $params['keyword_id'] = '%' . $keyword . '%';
            $params['keyword_department_code'] = '%' . $textKeyword . '%';
            $params['keyword_department_name'] = '%' . $textKeyword . '%';
        }

        if ($status !== '') {
            $conditions[] = 'nganh_hoc.TRANG_THAI = :status';
            $params['status'] = $status;
        }

        return [empty($conditions) ? '' : ' WHERE ' . implode(' AND ', $conditions), $params];
    }

    private function relatedChecks(): array
    {
        $checks = [
            ['lop_hoc', 'MA_NGANH'],
            ['sinh_vien', 'MA_NGANH'],
            ['bang_drl', 'MA_NGANH'],
            ['bang_diem', 'MA_NGANH'],
            ['diem_ren_luyen', 'MA_NGANH'],
            ['phieu_danh_gia', 'MA_NGANH'],
            ['ket_qua_ren_luyen', 'MA_NGANH'],
        ];

        foreach ($this->foreignKeyChecks() as $check) {
            $checks[] = $check;
        }

        $uniqueChecks = [];
        foreach ($checks as $check) {
            $uniqueChecks[$check[0] . '.' . $check[1]] = $check;
        }

        return array_values($uniqueChecks);
    }

    private function foreignKeyChecks(): array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT TABLE_NAME AS table_name, COLUMN_NAME AS column_name
                 FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND REFERENCED_TABLE_NAME = :table
                   AND REFERENCED_COLUMN_NAME = :column'
            );
            $statement->execute([
                'table' => 'nganh_hoc',
                'column' => 'MA_NGANH',
            ]);

            return array_map(
                static fn (array $row): array => [$row['table_name'], $row['column_name']],
                $statement->fetchAll()
            );
        } catch (Throwable) {
            return [];
        }
    }

    private function tableExists(string $table): bool
    {
        try {
            $statement = $this->db->prepare(
                'SELECT 1
                 FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = :table
                 LIMIT 1'
            );
            $statement->execute(['table' => $table]);

            return (bool) $statement->fetchColumn();
        } catch (Throwable) {
            return false;
        }
    }

    private function hasColumn(string $table, string $column): bool
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

    private function normalizeNameForLookup(string $name): string
    {
        $name = preg_replace('/\s+/u', '', trim($name));

        return function_exists('mb_strtolower') ? mb_strtolower($name, 'UTF-8') : strtolower($name);
    }

    private function normalizeCode(string $code): string
    {
        $code = trim($code);

        return function_exists('mb_strtoupper') ? mb_strtoupper($code, 'UTF-8') : strtoupper($code);
    }
}
