<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;

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
               AND TEN_NGANH = :name
             LIMIT 1'
        );
        $statement->execute([
            'department_id' => $departmentId,
            'name' => $name,
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function existsByNameInDepartmentExcept(string $name, int $departmentId, int $excludeId): bool
    {
        $statement = $this->db->prepare(
            'SELECT 1
             FROM nganh_hoc
             WHERE MA_KHOA = :department_id
               AND TEN_NGANH = :name
               AND MA_NGANH <> :exclude_id
             LIMIT 1'
        );
        $statement->execute([
            'department_id' => $departmentId,
            'name' => $name,
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

        return $statement->execute([
            'department_id' => $data['department_id'],
            'name' => $data['name'],
            'short_name' => $data['short_name'] === '' ? null : $data['short_name'],
            'description' => $data['description'] === '' ? null : $data['description'],
            'status' => $data['status'],
        ]);
    }

    public function countAll(): int
    {
        $statement = $this->db->query('SELECT COUNT(*) FROM nganh_hoc');

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
                    khoa_bo_mon.TEN_KHOA AS department_name,
                    nganh_hoc.TRANG_THAI AS status
             FROM nganh_hoc
             LEFT JOIN khoa_bo_mon ON khoa_bo_mon.MA_KHOA = nganh_hoc.MA_KHOA
             ORDER BY nganh_hoc.MA_NGANH DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
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

    public function isDuplicateException(\Throwable $exception): bool
    {
        return $exception instanceof PDOException && $exception->getCode() === '23000';
    }
}
