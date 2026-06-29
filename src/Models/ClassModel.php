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

    private function normalizeCode(string $code): string
    {
        $code = trim($code);

        return function_exists('mb_strtoupper') ? mb_strtoupper($code, 'UTF-8') : strtoupper($code);
    }
}
