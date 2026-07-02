<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;

class CriteriaModel
{
    public function __construct(private PDO $db)
    {
    }

    public function listBySemester(int $semesterId = 0, string $keyword = ''): array
    {
        $sql = 'SELECT
                    MA_TIEU_CHI AS id,
                    MA_HOC_KY AS semester_id,
                    TEN_TIEU_CHI AS name,
                    MO_TA_TIEU_CHI AS description,
                    DIEM_CONG AS credit,
                    DIEM_TRU AS deduction,
                    LAN_THUC_HIEN AS execution_round,
                    THU_TU_HIEN_THI AS display_order,
                    TRANG_THAI_TC AS status
                 FROM tieu_chi_danh_gia
                 WHERE 1=1';

        $params = [];
        if ($semesterId > 0) {
            $sql .= ' AND MA_HOC_KY = :semester_id';
            $params['semester_id'] = $semesterId;
        }

        if ($keyword !== '') {
            $sql .= ' AND (TEN_TIEU_CHI LIKE :keyword OR MO_TA_TIEU_CHI LIKE :keyword)';
            $params['keyword'] = '%' . $keyword . '%';
        }

        $sql .= ' ORDER BY COALESCE(THU_TU_HIEN_THI, 0), MA_TIEU_CHI';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT
                MA_TIEU_CHI AS id,
                MA_HOC_KY AS semester_id,
                TEN_TIEU_CHI AS name,
                MO_TA_TIEU_CHI AS description,
                DIEM_CONG AS credit,
                DIEM_TRU AS deduction,
                LAN_THUC_HIEN AS execution_round,
                THU_TU_HIEN_THI AS display_order,
                TRANG_THAI_TC AS status
             FROM tieu_chi_danh_gia
             WHERE MA_TIEU_CHI = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO tieu_chi_danh_gia
                (MA_HOC_KY, TEN_TIEU_CHI, MO_TA_TIEU_CHI, DIEM_CONG, DIEM_TRU, LAN_THUC_HIEN, THU_TU_HIEN_THI, TRANG_THAI_TC)
             VALUES
                (:semester_id, :name, :description, :credit, :deduction, :execution_round, :display_order, :status)'
        );

        return $statement->execute([
            'semester_id' => $data['semester_id'],
            'name' => trim($data['name']),
            'description' => trim($data['description']),
            'credit' => $data['credit'],
            'deduction' => $data['deduction'],
            'execution_round' => $data['execution_round'],
            'display_order' => $data['display_order'],
            'status' => trim($data['status']),
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare(
            'UPDATE tieu_chi_danh_gia SET
                MA_HOC_KY = :semester_id,
                TEN_TIEU_CHI = :name,
                MO_TA_TIEU_CHI = :description,
                DIEM_CONG = :credit,
                DIEM_TRU = :deduction,
                LAN_THUC_HIEN = :execution_round,
                THU_TU_HIEN_THI = :display_order,
                TRANG_THAI_TC = :status
             WHERE MA_TIEU_CHI = :id'
        );

        $statement->execute([
            'id' => $id,
            'semester_id' => $data['semester_id'],
            'name' => trim($data['name']),
            'description' => trim($data['description']),
            'credit' => $data['credit'],
            'deduction' => $data['deduction'],
            'execution_round' => $data['execution_round'],
            'display_order' => $data['display_order'],
            'status' => trim($data['status']),
        ]);

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare(
            'DELETE FROM tieu_chi_danh_gia WHERE MA_TIEU_CHI = :id'
        );
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }
}
