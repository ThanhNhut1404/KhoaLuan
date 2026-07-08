<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;
use Throwable;

class SemesterModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO hoc_ky (MA_NIEN_KHOA, TEN_HOC_KY, THOI_GIAN_BDHK, THOI_GIAN_KTHK, TRANG_THAI_HK) 
                VALUES (:year_id, :name, :start_date, :end_date, :status)';
        $startedTransaction = !$this->db->inTransaction();

        if ($startedTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $stmt = $this->db->prepare($sql);
            $created = $stmt->execute([
                'year_id' => (int) $data['academic_year'],
                'name' => trim($data['semester_name']),
                'start_date' => trim($data['start_date']),
                'end_date' => trim($data['end_date']),
                'status' => trim($data['status']),
            ]);

            if (!$created || $stmt->rowCount() < 1) {
                if ($startedTransaction) {
                    $this->db->rollBack();
                }
                return false;
            }

            if ($startedTransaction) {
                $this->db->commit();
            }

            return true;
        } catch (\Throwable $exception) {
            if ($startedTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM hoc_ky');
        return (int) $stmt->fetchColumn();
    }

    public function countByAcademicYear(int $academicYearId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM hoc_ky WHERE MA_NIEN_KHOA = :year_id');
        $stmt->execute(['year_id' => $academicYearId]);

        return (int) $stmt->fetchColumn();
    }

    public function countFiltered(string $keyword = '', string $status = '', string $academicYear = ''): int
    {
        if (trim($keyword) === '' && trim($status) === '' && trim($academicYear) === '') {
            return $this->countAll();
        }

        [$where, $params] = $this->filterClause($keyword, $status, $academicYear);
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM hoc_ky h
             JOIN nien_khoa n ON h.MA_NIEN_KHOA = n.MA_NIEN_KHOA' . $where
        );
        $this->bindNamedParams($stmt, $params);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = $this->db->prepare(
            'SELECT h.MA_HOC_KY AS id, h.TEN_HOC_KY AS name, 
                    h.THOI_GIAN_BDHK AS start_date, h.THOI_GIAN_KTHK AS end_date, 
                    h.TRANG_THAI_HK AS status, n.TEN_NIEN_KHOA AS academic_year
             FROM hoc_ky h
             JOIN nien_khoa n ON h.MA_NIEN_KHOA = n.MA_NIEN_KHOA
             ORDER BY h.MA_HOC_KY DESC
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listFilteredPaginated(int $page, int $perPage, string $keyword = '', string $status = '', string $academicYear = ''): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        [$where, $params] = $this->filterClause($keyword, $status, $academicYear);
        
        $stmt = $this->db->prepare(
            'SELECT h.MA_HOC_KY AS id, h.TEN_HOC_KY AS name, 
                    h.THOI_GIAN_BDHK AS start_date, h.THOI_GIAN_KTHK AS end_date, 
                    h.TRANG_THAI_HK AS status, n.TEN_NIEN_KHOA AS academic_year
             FROM hoc_ky h
             JOIN nien_khoa n ON h.MA_NIEN_KHOA = n.MA_NIEN_KHOA
             ' . $where . '
             ORDER BY h.MA_HOC_KY DESC
             LIMIT :limit OFFSET :offset'
        );
        $this->bindNamedParams($stmt, $params);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT h.MA_HOC_KY AS id, h.MA_NIEN_KHOA AS academic_year, 
                    h.TEN_HOC_KY AS name, h.THOI_GIAN_BDHK AS start_date, 
                    h.THOI_GIAN_KTHK AS end_date, h.TRANG_THAI_HK AS status,
                    n.TEN_NIEN_KHOA AS academic_year_name
             FROM hoc_ky h
             LEFT JOIN nien_khoa n ON h.MA_NIEN_KHOA = n.MA_NIEN_KHOA
             WHERE h.MA_HOC_KY = :id'
        );
        $stmt->execute(['id' => (int) $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function allForStatusSync(): array
    {
        $stmt = $this->db->query(
            'SELECT MA_HOC_KY AS id, THOI_GIAN_BDHK AS start_date, THOI_GIAN_KTHK AS end_date, TRANG_THAI_HK AS status
             FROM hoc_ky'
        );

        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE hoc_ky 
                SET MA_NIEN_KHOA = :year_id, TEN_HOC_KY = :name, 
                    THOI_GIAN_BDHK = :start_date, THOI_GIAN_KTHK = :end_date, 
                    TRANG_THAI_HK = :status 
                WHERE MA_HOC_KY = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'year_id' => (int) $data['academic_year'],
            'name' => trim($data['semester_name']),
            'start_date' => trim($data['start_date']),
            'end_date' => trim($data['end_date']),
            'status' => trim($data['status']),
        ]);
        return $stmt->rowCount() > 0;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE hoc_ky SET TRANG_THAI_HK = :status WHERE MA_HOC_KY = :id');
        $stmt->execute(['id' => $id, 'status' => trim($status)]);
        return $stmt->rowCount() > 0;
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM hoc_ky WHERE MA_HOC_KY = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function hasRelatedData(int $id): bool
    {
        // Kiểm tra xem học kỳ này có đang được sử dụng không
        $relatedTables = [
            ['table' => 'lop_hoc', 'column' => 'MA_HOC_KY'],
            ['table' => 'sinh_vien_lop', 'column' => 'MA_HOC_KY'],
            ['table' => 'hoat_dong_hoc_ky', 'column' => 'MA_HOC_KY'],
        ];

        foreach ($relatedTables as $relation) {
            if (!$this->tableExists($relation['table']) || !$this->hasColumn($relation['table'], $relation['column'])) {
                continue;
            }

            $stmt = $this->db->prepare(sprintf(
                'SELECT 1 FROM %s WHERE %s = :id LIMIT 1',
                $relation['table'],
                $relation['column']
            ));
            $stmt->execute(['id' => $id]);

            if ($stmt->fetchColumn()) {
                return true;
            }
        }

        return false;
    }

    public function getAcademicYears(): array
    {
        $stmt = $this->db->query(
            'SELECT MA_NIEN_KHOA AS id, TEN_NIEN_KHOA AS name
             FROM nien_khoa
             ORDER BY MA_NIEN_KHOA DESC'
        );
        return $stmt->fetchAll();
    }

    public function getActiveSemesters(): array
    {
        $stmt = $this->db->query(
            'SELECT MA_HOC_KY, TEN_HOC_KY
             FROM hoc_ky
             WHERE TRANG_THAI_HK IN (\'Sắp diễn ra\', \'Đang diễn ra\')
             ORDER BY MA_HOC_KY DESC'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getActiveSemestersByAcademicYear(int $academicYearId): array
    {
        $stmt = $this->db->prepare(
            'SELECT MA_HOC_KY, TEN_HOC_KY
             FROM hoc_ky
             WHERE MA_NIEN_KHOA = :year_id
             AND TRANG_THAI_HK IN (\'Sắp diễn ra\', \'Đang diễn ra\')
             ORDER BY MA_HOC_KY DESC'
        );
        $stmt->execute(['year_id' => $academicYearId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getSemestersByAcademicYear(int $academicYearId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                MA_HOC_KY,
                MA_NIEN_KHOA,
                TEN_HOC_KY,
                THOI_GIAN_BDHK,
                THOI_GIAN_KTHK,
                TRANG_THAI_HK
             FROM hoc_ky
             WHERE MA_NIEN_KHOA = :academic_year_id
             ORDER BY THOI_GIAN_BDHK ASC, MA_HOC_KY ASC'
        );

        $stmt->execute(['academic_year_id' => $academicYearId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAcademicYearIdBySemester(int $semesterId): int
    {
        $stmt = $this->db->prepare('SELECT MA_NIEN_KHOA FROM hoc_ky WHERE MA_HOC_KY = :id LIMIT 1');
        $stmt->execute(['id' => $semesterId]);
        return (int) $stmt->fetchColumn();
    }

    public function getStatusOptions(): array
    {
        return [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
            ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
            ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
        ];
    }

    public function isDuplicateException(\Throwable $exception): bool
    {
        return $exception instanceof PDOException && $exception->getCode() === '23000';
    }

    public function isConstraintException(Throwable $exception): bool
    {
        return $exception instanceof PDOException && $exception->getCode() === '23000';
    }

    private function tableExists(string $table): bool
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT 1
                 FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = :table
                 LIMIT 1'
            );
            $stmt->execute(['table' => $table]);
            return (bool) $stmt->fetchColumn();
        } catch (Throwable) {
            return false;
        }
    }

    private function hasColumn(string $table, string $column): bool
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT 1
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = :table
                   AND COLUMN_NAME = :column'
            );
            $stmt->execute(['table' => $table, 'column' => $column]);
            return (bool) $stmt->fetchColumn();
        } catch (Throwable) {
            return false;
        }
    }

    private function filterClause(string $keyword = '', string $status = '', string $academicYear = ''): array
    {
        $conditions = [];
        $params = [];
        $keyword = trim($keyword);

        if ($keyword !== '') {
            $conditions[] = '(LOWER(h.TEN_HOC_KY) LIKE :keyword_semester
                OR LOWER(n.TEN_NIEN_KHOA) LIKE :keyword_year
                OR DATE_FORMAT(h.THOI_GIAN_BDHK, "%Y-%m-%d") LIKE :keyword_start_iso
                OR DATE_FORMAT(h.THOI_GIAN_BDHK, "%d/%m/%Y") LIKE :keyword_start_display
                OR DATE_FORMAT(h.THOI_GIAN_KTHK, "%Y-%m-%d") LIKE :keyword_end_iso
                OR DATE_FORMAT(h.THOI_GIAN_KTHK, "%d/%m/%Y") LIKE :keyword_end_display)';
            $keywordParam = '%' . strtolower($keyword) . '%';
            $dateKeywordParam = '%' . $keyword . '%';
            $params['keyword_semester'] = $keywordParam;
            $params['keyword_year'] = $keywordParam;
            $params['keyword_start_iso'] = $dateKeywordParam;
            $params['keyword_start_display'] = $dateKeywordParam;
            $params['keyword_end_iso'] = $dateKeywordParam;
            $params['keyword_end_display'] = $dateKeywordParam;
        }

        $status = trim($status);
        if ($status !== '') {
            $conditions[] = 'h.TRANG_THAI_HK = :status';
            $params['status'] = $status;
        }

        $academicYear = trim($academicYear);
        if ($academicYear !== '') {
            $conditions[] = 'h.MA_NIEN_KHOA = :academic_year';
            $params['academic_year'] = (int) $academicYear;
        }

        return [empty($conditions) ? '' : ' WHERE ' . implode(' AND ', $conditions), $params];
    }

    private function bindNamedParams(\PDOStatement $statement, array $params): void
    {
        foreach ($params as $name => $value) {
            $statement->bindValue(':' . ltrim((string) $name, ':'), $value);
        }
    }
}
