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
                    t.MA_TIEU_CHI AS id,
                    dm.MA_HOC_KY AS semester_id,
                    t.MA_DANH_MUC AS category_id,
                    dm.TEN_DANH_MUC AS category_name,
                    t.TEN_TIEU_CHI AS name,
                    t.MO_TA_TIEU_CHI AS description,
                    CASE
                        WHEN t.LOAI_TIEU_CHI = \'CO_DINH\' THEN COALESCE(t.DIEM_CONG, 0)
                        WHEN t.LOAI_TIEU_CHI = \'CONG_THEO_LAN\' THEN COALESCE(t.DIEM_CONG, 0)
                        ELSE 0
                    END AS fixed_point,
                    t.DIEM_CONG AS add_point,
                    t.DIEM_TRU AS deduct_point,
                    t.DIEM_TOI_DA_TC AS max_point,
                    t.LAN_THUC_HIEN_TOI_DA AS max_times,
                    t.LOAI_TIEU_CHI AS type,
                    t.IS_HOAT_DONG AS is_activity,
                    t.SU_DUNG_CHO_HOAT_DONG AS use_for_activity,
                    t.THU_TU_HIEN_THI AS display_order,
                    t.TRANG_THAI_TC AS status
                 FROM tieu_chi_danh_gia t
                 LEFT JOIN danh_muc_tieu_chi dm ON dm.MA_DANH_MUC = t.MA_DANH_MUC
                 WHERE 1=1';

        $params = [];
        if ($semesterId > 0) {
            $sql .= ' AND dm.MA_HOC_KY = :semester_id';
            $params['semester_id'] = $semesterId;
        }

        if ($keyword !== '') {
            $sql .= ' AND (t.TEN_TIEU_CHI LIKE :keyword OR t.MO_TA_TIEU_CHI LIKE :keyword)';
            $params['keyword'] = '%' . $keyword . '%';
        }

        $sql .= ' ORDER BY COALESCE(t.THU_TU_HIEN_THI, 0), t.MA_TIEU_CHI';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT
                t.MA_TIEU_CHI AS id,
                dm.MA_HOC_KY AS semester_id,
                t.MA_DANH_MUC AS category_id,
                dm.TEN_DANH_MUC AS category_name,
                t.TEN_TIEU_CHI AS name,
                t.MO_TA_TIEU_CHI AS description,
                CASE
                    WHEN t.LOAI_TIEU_CHI = \'CO_DINH\' THEN COALESCE(t.DIEM_CONG, 0)
                    WHEN t.LOAI_TIEU_CHI = \'CONG_THEO_LAN\' THEN COALESCE(t.DIEM_CONG, 0)
                    ELSE 0
                END AS fixed_point,
                t.DIEM_CONG AS add_point,
                t.DIEM_TRU AS deduct_point,
                t.DIEM_TOI_DA_TC AS max_point,
                t.LAN_THUC_HIEN_TOI_DA AS max_times,
                    t.LOAI_TIEU_CHI AS type,
                    t.IS_HOAT_DONG AS is_activity,
                    t.SU_DUNG_CHO_HOAT_DONG AS use_for_activity,
                t.THU_TU_HIEN_THI AS display_order,
                t.TRANG_THAI_TC AS status
             FROM tieu_chi_danh_gia t
             LEFT JOIN danh_muc_tieu_chi dm ON dm.MA_DANH_MUC = t.MA_DANH_MUC
             WHERE t.MA_TIEU_CHI = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function listCategoriesBySemester(int $semesterId): array
    {
        $statement = $this->db->prepare(
            'SELECT
                dm.MA_DANH_MUC AS id,
                dm.MA_BO_MAU AS template_id,
                dm.TEN_DANH_MUC AS name,
                dm.DIEM_TOI_DA_MUC AS max_points,
                dm.THU_TU_HIEN_THI AS display_order
             FROM danh_muc_tieu_chi dm
             JOIN ap_dung_tieu_chi a ON a.MA_BO_MAU = dm.MA_BO_MAU
             WHERE a.MA_HOC_KY = :semester_id
             ORDER BY COALESCE(dm.THU_TU_HIEN_THI, 0), dm.MA_DANH_MUC'
        );
        $statement->execute(['semester_id' => $semesterId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function listMasterTemplates(): array
    {
        $statement = $this->db->prepare(
            'SELECT
                MA_BO_MAU AS id,
                TEN_BO_MAU AS name,
                MO_TA AS description,
                TRANG_THAI AS status,
                NGAY_TAO AS created_at
             FROM bo_tieu_chi_mau
             ORDER BY MA_BO_MAU DESC'
        );
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findMasterTemplateById(int $templateId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT
                MA_BO_MAU AS id,
                TEN_BO_MAU AS name,
                MO_TA AS description,
                TRANG_THAI AS status,
                NGAY_TAO AS created_at
             FROM bo_tieu_chi_mau
             WHERE MA_BO_MAU = :template_id
             LIMIT 1'
        );
        $statement->execute(['template_id' => $templateId]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getAppliedTemplateForSemester(int $semesterId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT
                m.MA_BO_MAU AS id,
                m.TEN_BO_MAU AS name
             FROM ap_dung_tieu_chi a
             JOIN bo_tieu_chi_mau m ON m.MA_BO_MAU = a.MA_BO_MAU
             WHERE a.MA_HOC_KY = :semester_id
             LIMIT 1'
        );
        $statement->execute(['semester_id' => $semesterId]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function listCategoriesByTemplate(int $templateId): array
    {
        $statement = $this->db->prepare(
            'SELECT
                MA_DANH_MUC AS id,
                MA_BO_MAU AS template_id,
                TEN_DANH_MUC AS name,
                DIEM_TOI_DA_MUC AS max_points,
                THU_TU_HIEN_THI AS display_order
             FROM danh_muc_tieu_chi
             WHERE MA_BO_MAU = :template_id
             ORDER BY COALESCE(THU_TU_HIEN_THI, 0), MA_DANH_MUC'
        );
        $statement->execute(['template_id' => $templateId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function listCriteriaByTemplate(int $templateId): array
    {
        $statement = $this->db->prepare(
            'SELECT
                t.MA_TIEU_CHI AS id,
                t.MA_DANH_MUC AS category_id,
                dm.TEN_DANH_MUC AS category_name,
                t.TEN_TIEU_CHI AS name,
                t.MO_TA_TIEU_CHI AS description,
                CASE
                    WHEN t.LOAI_TIEU_CHI = \'CO_DINH\' THEN COALESCE(t.DIEM_CONG, 0)
                    WHEN t.LOAI_TIEU_CHI = \'CONG_THEO_LAN\' THEN COALESCE(t.DIEM_CONG, 0)
                    ELSE 0
                END AS fixed_point,
                t.DIEM_CONG AS add_point,
                t.DIEM_TRU AS deduct_point,
                t.DIEM_TOI_DA_TC AS max_point,
                t.LAN_THUC_HIEN_TOI_DA AS max_times,
                t.LOAI_TIEU_CHI AS type,
                t.IS_HOAT_DONG AS is_activity,
                t.SU_DUNG_CHO_HOAT_DONG AS use_for_activity,
                t.THU_TU_HIEN_THI AS display_order,
                t.TRANG_THAI_TC AS status
             FROM tieu_chi_danh_gia t
             LEFT JOIN danh_muc_tieu_chi dm ON dm.MA_DANH_MUC = t.MA_DANH_MUC
             WHERE dm.MA_BO_MAU = :template_id
             ORDER BY COALESCE(t.THU_TU_HIEN_THI, 0), t.MA_TIEU_CHI'
        );
        $statement->execute(['template_id' => $templateId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getActivitySelectableCriteriaBySemester(int $semesterId): array
    {
        $statement = $this->db->prepare(
                        'SELECT
                                t.MA_TIEU_CHI AS id,
                                t.MA_DANH_MUC AS category_id,
                                dm.TEN_DANH_MUC AS category_name,
                                t.TEN_TIEU_CHI AS name,
                                t.MO_TA_TIEU_CHI AS description,
                                CASE
                                    WHEN t.LOAI_TIEU_CHI = \'CO_DINH\' THEN COALESCE(t.DIEM_CONG, 0)
                                    WHEN t.LOAI_TIEU_CHI = \'CONG_THEO_LAN\' THEN COALESCE(t.DIEM_CONG, 0)
                                    ELSE 0
                            END AS fixed_point,
                                t.DIEM_CONG AS add_point,
                                t.DIEM_TRU AS deduct_point,
                                t.DIEM_TOI_DA_TC AS max_point,
                                t.LAN_THUC_HIEN_TOI_DA AS max_times,
                                t.LOAI_TIEU_CHI AS type,
                                t.SU_DUNG_CHO_HOAT_DONG AS use_for_activity,
                                t.THU_TU_HIEN_THI AS display_order
                         FROM ap_dung_tieu_chi a
                         JOIN danh_muc_tieu_chi dm ON dm.MA_BO_MAU = a.MA_BO_MAU
                         JOIN tieu_chi_danh_gia t ON t.MA_DANH_MUC = dm.MA_DANH_MUC
                         WHERE a.MA_HOC_KY = :semester_id
                             AND t.SU_DUNG_CHO_HOAT_DONG = 1
                             AND t.TRANG_THAI_TC = \'HOAT_DONG\'
                         ORDER BY COALESCE(t.THU_TU_HIEN_THI, 0), t.MA_TIEU_CHI'
        );

        $statement->execute(['semester_id' => $semesterId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function sumTemplateCategoryPoints(int $templateId): float
    {
        if ($templateId < 1) {
            return 0.0;
        }

        $statement = $this->db->prepare(
            'SELECT COALESCE(SUM(DIEM_TOI_DA_MUC), 0) FROM danh_muc_tieu_chi WHERE MA_BO_MAU = :template_id'
        );
        $statement->execute(['template_id' => $templateId]);

        return (float) $statement->fetchColumn();
    }

    public function sumCategoryPointsBySemester(int $semesterId): float
    {
        if ($semesterId < 1) {
            return 0.0;
        }

        $statement = $this->db->prepare(
            'SELECT COALESCE(SUM(dm.DIEM_TOI_DA_MUC), 0)
             FROM danh_muc_tieu_chi dm
             JOIN ap_dung_tieu_chi a ON a.MA_BO_MAU = dm.MA_BO_MAU
             WHERE a.MA_HOC_KY = :semester_id'
        );
        $statement->execute(['semester_id' => $semesterId]);

        return (float) $statement->fetchColumn();
    }

    public function createMasterTemplate(array $data): int
    {
        // keep backward-compatible wrapper: delegate to createTemplate
        return $this->createTemplate([
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'status' => $data['status'] ?? 1,
        ]);
    }

    // New API: createTemplate
    public function createTemplate(array $data): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO bo_tieu_chi_mau (TEN_BO_MAU, MO_TA, TRANG_THAI, NGAY_TAO)
             VALUES (:name, :description, :status, NOW())'
        );

        $statement->execute([
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateTemplate(int $templateId, array $data): bool
    {
        $statement = $this->db->prepare(
            'UPDATE bo_tieu_chi_mau SET TEN_BO_MAU = :name, MO_TA = :description, TRANG_THAI = :status WHERE MA_BO_MAU = :template_id'
        );

        return $statement->execute([
            'template_id' => $templateId,
            'name' => trim($data['name'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
        ]);
    }

    public function deleteOrLockTemplate(int $templateId): bool
    {
        $statement = $this->db->prepare('UPDATE bo_tieu_chi_mau SET TRANG_THAI = 0 WHERE MA_BO_MAU = :template_id');
        $statement->execute(['template_id' => $templateId]);

        return $statement->rowCount() > 0;
    }

    public function countAppliedTemplatesByTemplate(int $templateId): int
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM ap_dung_tieu_chi WHERE MA_BO_MAU = :template_id');
        $statement->execute(['template_id' => $templateId]);

        return (int) $statement->fetchColumn();
    }

    public function deleteTemplateWithRelations(int $templateId): bool
    {
        $this->db->beginTransaction();

        try {
            $criteriaStatement = $this->db->prepare(
                'DELETE FROM tieu_chi_danh_gia WHERE MA_DANH_MUC IN (SELECT MA_DANH_MUC FROM danh_muc_tieu_chi WHERE MA_BO_MAU = :template_id)'
            );
            $criteriaStatement->execute(['template_id' => $templateId]);

            $categoryStatement = $this->db->prepare('DELETE FROM danh_muc_tieu_chi WHERE MA_BO_MAU = :template_id');
            $categoryStatement->execute(['template_id' => $templateId]);

            $templateStatement = $this->db->prepare('DELETE FROM bo_tieu_chi_mau WHERE MA_BO_MAU = :template_id');
            $templateStatement->execute(['template_id' => $templateId]);

            $this->db->commit();

            return $templateStatement->rowCount() > 0;
        } catch (\Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function createTemplateCategory(array $data): bool
    {
        $currentSum = $this->sumTemplateCategoryPoints($data['template_id']);
        $newSum = $currentSum + $data['max_points'];

        if ($newSum > 100.0) {
            throw new \InvalidArgumentException('Tổng điểm tối đa của các danh mục trong bộ mẫu không được vượt quá 100 điểm!');
        }

        $statement = $this->db->prepare(
            'INSERT INTO danh_muc_tieu_chi (MA_BO_MAU, TEN_DANH_MUC, DIEM_TOI_DA_MUC, THU_TU_HIEN_THI)
             VALUES (:template_id, :name, :max_points, :display_order)'
        );

        return $statement->execute([
            'template_id' => $data['template_id'],
            'name' => trim($data['name']),
            'max_points' => $data['max_points'],
            'display_order' => $data['display_order'],
        ]);
    }

    public function updateTemplateCategory(int $categoryId, array $data): bool
    {
        $statement = $this->db->prepare(
            'UPDATE danh_muc_tieu_chi SET MA_BO_MAU = :template_id, TEN_DANH_MUC = :name, DIEM_TOI_DA_MUC = :max_points, THU_TU_HIEN_THI = :display_order WHERE MA_DANH_MUC = :category_id'
        );

        return $statement->execute([
            'category_id' => $categoryId,
            'template_id' => $data['template_id'],
            'name' => trim($data['name']),
            'max_points' => $data['max_points'],
            'display_order' => $data['display_order'],
        ]);
    }

    public function deleteTemplateCategory(int $categoryId): bool
    {
        $statement = $this->db->prepare('DELETE FROM danh_muc_tieu_chi WHERE MA_DANH_MUC = :category_id');
        $statement->execute(['category_id' => $categoryId]);

        return $statement->rowCount() > 0;
    }

    public function countCriteriaByCategory(int $categoryId): int
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM tieu_chi_danh_gia WHERE MA_DANH_MUC = :category_id');
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function createTemplateCriteria(array $data): bool
    {
        // determine score mapping based on type
        $type = $data['type'] ?? '';
        $fixed = (float) ($data['fixed_point'] ?? 0);
        $add = (float) ($data['add_point'] ?? 0);
        $deduct = (float) ($data['deduct_point'] ?? 0);
        $maxTimes = (int) ($data['max_times'] ?? 0);
        $maxPoint = 0.0;
        $diem_cong = 0.0;
        $diem_tru = 0.0;

        if ($type === 'CO_DINH') {
            $diem_cong = $fixed;
            $diem_tru = 0.0;
            $maxPoint = $fixed;
            $maxTimes = 1;
        } elseif ($type === 'CONG_THEO_LAN') {
            $diem_cong = $add;
            $diem_tru = 0.0;
            $maxPoint = $add * $maxTimes;
        } elseif ($type === 'TRU_THEO_LAN') {
            $diem_cong = 0.0;
            $diem_tru = $deduct;
            $maxPoint = $deduct * $maxTimes;
        }

        $statement = $this->db->prepare(
            'INSERT INTO tieu_chi_danh_gia
                (MA_DANH_MUC, TEN_TIEU_CHI, MO_TA_TIEU_CHI,
                 DIEM_CONG, DIEM_TRU, DIEM_TOI_DA_TC,
                 LAN_THUC_HIEN_TOI_DA, LOAI_TIEU_CHI, SU_DUNG_CHO_HOAT_DONG,
                 THU_TU_HIEN_THI, TRANG_THAI_TC)
             VALUES
                (:category_id, :name, :description,
                 :diem_cong, :diem_tru, :max_point,
                 :max_times, :type, :use_for_activity,
                 :display_order, :status)'
        );

        return $statement->execute([
            'category_id' => $data['category_id'],
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'diem_cong' => $diem_cong,
            'diem_tru' => $diem_tru,
            'max_point' => $maxPoint,
            'max_times' => $maxTimes,
            'type' => $type,
            'use_for_activity' => !empty($data['use_for_activity']) ? 1 : 0,
            'display_order' => $data['display_order'] ?? 0,
            'status' => 'HOAT_DONG',
        ]);
    }

    public function updateTemplateCriteria(int $criteriaId, array $data): bool
    {
        $type = $data['type'] ?? '';
        $fixed = (float) ($data['fixed_point'] ?? 0);
        $add = (float) ($data['add_point'] ?? 0);
        $deduct = (float) ($data['deduct_point'] ?? 0);
        $maxTimes = (int) ($data['max_times'] ?? 0);
        $maxPoint = 0.0;
        $diem_cong = 0.0;
        $diem_tru = 0.0;

        if ($type === 'CO_DINH') {
            $diem_cong = $fixed;
            $diem_tru = 0.0;
            $maxPoint = $fixed;
            $maxTimes = 1;
        } elseif ($type === 'CONG_THEO_LAN') {
            $diem_cong = $add;
            $diem_tru = 0.0;
            $maxPoint = $add * $maxTimes;
        } elseif ($type === 'TRU_THEO_LAN') {
            $diem_cong = 0.0;
            $diem_tru = $deduct;
            $maxPoint = $deduct * $maxTimes;
        }

        $statement = $this->db->prepare(
            'UPDATE tieu_chi_danh_gia SET
                MA_DANH_MUC = :category_id,
                TEN_TIEU_CHI = :name,
                MO_TA_TIEU_CHI = :description,
                DIEM_CONG = :diem_cong,
                DIEM_TRU = :diem_tru,
                DIEM_TOI_DA_TC = :max_point,
                LAN_THUC_HIEN_TOI_DA = :max_times,
                LOAI_TIEU_CHI = :type,
                SU_DUNG_CHO_HOAT_DONG = :use_for_activity,
                THU_TU_HIEN_THI = :display_order,
                TRANG_THAI_TC = :status
             WHERE MA_TIEU_CHI = :criteria_id'
        );

        return $statement->execute([
            'criteria_id' => $criteriaId,
            'category_id' => $data['category_id'],
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'diem_cong' => $diem_cong,
            'diem_tru' => $diem_tru,
            'max_point' => $maxPoint,
            'max_times' => $maxTimes,
            'type' => $type,
            'use_for_activity' => !empty($data['use_for_activity']) ? 1 : 0,
            'display_order' => $data['display_order'] ?? 0,
            'status' => 'HOAT_DONG',
        ]);
    }

    public function deleteTemplateCriteria(int $criteriaId): bool
    {
        $statement = $this->db->prepare('DELETE FROM tieu_chi_danh_gia WHERE MA_TIEU_CHI = :criteria_id');
        $statement->execute(['criteria_id' => $criteriaId]);

        return $statement->rowCount() > 0;
    }

    // Apply a master template to a semester, recording who applied it.
    public function applyTemplateToSemester(int $semesterId, int $templateId, ?string $username = null): bool
    {
        $sql = 'INSERT INTO ap_dung_tieu_chi
                (MA_HOC_KY, MA_BO_MAU, NGAY_AP_DUNG, NGUOI_AP_DUNG)
                VALUES
                (:semester_id, :template_id, NOW(), :username)
                ON DUPLICATE KEY UPDATE
                MA_BO_MAU = VALUES(MA_BO_MAU),
                NGAY_AP_DUNG = VALUES(NGAY_AP_DUNG),
                NGUOI_AP_DUNG = VALUES(NGUOI_AP_DUNG)';

        $statement = $this->db->prepare($sql);
        $saved = $statement->execute([
            'semester_id' => $semesterId,
            'template_id' => $templateId,
            'username' => $username ?? '',
        ]);

        error_log('rowCount=' . $statement->rowCount());

        if (!$saved) {
            error_log('applyTemplateToSemester failed for semester_id=' . $semesterId . ' template_id=' . $templateId);
        }

        return $saved;
    }

    

    public function getAppliedTemplateBySemester(int $semesterId): ?array
    {
        return $this->getAppliedTemplateForSemester($semesterId);
    }

    public function getCategoriesByTemplate(int $templateId): array
    {
        return $this->listCategoriesByTemplate($templateId);
    }

    public function getCriteriaByTemplateGrouped(int $templateId): array
    {
        $rows = $this->listCriteriaByTemplate($templateId);
        $grouped = [];
        foreach ($rows as $r) {
            $grouped[$r['category_id']][] = $r;
        }
        return $grouped;
    }

    public function getCategoryTotalPoints(int $categoryId): float
    {
        return $this->getCategoryMaxPoints($categoryId);
    }

    public function getTemplateTotalCategoryPoints(int $templateId): float
    {
        return $this->sumTemplateCategoryPoints($templateId);
    }

    public function countByFilters(int $semesterId = 0, int $categoryId = 0, string $keyword = ''): int
    {
        $sql = 'SELECT COUNT(*) FROM tieu_chi_danh_gia t LEFT JOIN danh_muc_tieu_chi dm ON dm.MA_DANH_MUC = t.MA_DANH_MUC WHERE 1=1';
        $params = [];

        if ($semesterId > 0) {
            $sql .= ' AND dm.MA_HOC_KY = :semester_id';
            $params['semester_id'] = $semesterId;
        }

        if ($categoryId > 0) {
            $sql .= ' AND t.MA_DANH_MUC = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($keyword !== '') {
            $sql .= ' AND (t.TEN_TIEU_CHI LIKE :keyword OR t.MO_TA_TIEU_CHI LIKE :keyword)';
            $params['keyword'] = '%' . $keyword . '%';
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn();
    }

    public function listByFilters(int $semesterId = 0, int $categoryId = 0, string $keyword = '', int $page = 1, int $perPage = 15): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $sql = 'SELECT
                    t.MA_TIEU_CHI AS id,
                    t.TEN_TIEU_CHI AS name,
                    dm.MA_DANH_MUC AS category_id,
                    dm.TEN_DANH_MUC AS category_name,
                    t.LOAI_TIEU_CHI AS type,
                    CASE
                        WHEN t.LOAI_TIEU_CHI = \'CO_DINH\' THEN COALESCE(t.DIEM_CONG, 0)
                        WHEN t.LOAI_TIEU_CHI = \'CONG_THEO_LAN\' THEN COALESCE(t.DIEM_CONG, 0)
                        ELSE 0
                    END AS fixed_point,
                    t.DIEM_TOI_DA_TC AS max_point,
                    t.IS_HOAT_DONG AS is_activity,
                    t.THU_TU_HIEN_THI AS display_order,
                    dm.MA_HOC_KY AS semester_id
                 FROM tieu_chi_danh_gia t
                 LEFT JOIN danh_muc_tieu_chi dm ON dm.MA_DANH_MUC = t.MA_DANH_MUC
                 WHERE 1=1';
        $params = [];

        if ($semesterId > 0) {
            $sql .= ' AND dm.MA_HOC_KY = :semester_id';
            $params['semester_id'] = $semesterId;
        }

        if ($categoryId > 0) {
            $sql .= ' AND t.MA_DANH_MUC = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($keyword !== '') {
            $sql .= ' AND (t.TEN_TIEU_CHI LIKE :keyword OR t.MO_TA_TIEU_CHI LIKE :keyword)';
            $params['keyword'] = '%' . $keyword . '%';
        }

        $sql .= ' ORDER BY COALESCE(t.THU_TU_HIEN_THI, 0), t.MA_TIEU_CHI LIMIT :limit OFFSET :offset';
        $statement = $this->db->prepare($sql);

        foreach ($params as $name => $value) {
            $statement->bindValue(':' . $name, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getCategoryMaxPoints(int $categoryId): float
    {
        if ($categoryId < 1) {
            return 0.0;
        }

        $statement = $this->db->prepare(
            'SELECT COALESCE(DIEM_TOI_DA_MUC, 0) FROM danh_muc_tieu_chi WHERE MA_DANH_MUC = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (float) $statement->fetchColumn();
    }

    public function sumCriteriaPointsByCategory(int $categoryId, int $excludeCriteriaId = 0): float
    {
        if ($categoryId < 1) {
            return 0.0;
        }

        $sql = 'SELECT COALESCE(SUM(DIEM_TOI_DA_TC), 0) FROM tieu_chi_danh_gia WHERE MA_DANH_MUC = :category_id';
        if ($excludeCriteriaId > 0) {
            $sql .= ' AND MA_TIEU_CHI != :exclude_id';
        }

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        if ($excludeCriteriaId > 0) {
            $statement->bindValue(':exclude_id', $excludeCriteriaId, PDO::PARAM_INT);
        }
        $statement->execute();

        return (float) $statement->fetchColumn();
    }

    public function createCategory(array $data): bool
    {
        $currentSum = $this->sumCategoryPointsBySemester($data['semester_id']);
        $newSum = $currentSum + $data['max_points'];

        if ($newSum > 100.0) {
            throw new \InvalidArgumentException('Tổng điểm tối đa của các danh mục trong học kỳ không được vượt quá 100 điểm!');
        }

        $statement = $this->db->prepare(
            'INSERT INTO danh_muc_tieu_chi (MA_HOC_KY, TEN_DANH_MUC, DIEM_TOI_DA_MUC, THU_TU_HIEN_THI)
             VALUES (:semester_id, :name, :max_points, :display_order)'
        );

        return $statement->execute([
            'semester_id' => $data['semester_id'],
            'name' => trim($data['name']),
            'max_points' => $data['max_points'],
            'display_order' => $data['display_order'],
        ]);
    }

    public function createCriteria(array $data): bool
    {
        // determine DIEM_CONG/DIEM_TRU/DIEM_TOI_DA_TC based on type
        $type = $data['type'] ?? '';
        $fixed = (float) ($data['fixed_point'] ?? 0);
        $add = (float) ($data['add_point'] ?? 0);
        $deduct = (float) ($data['deduct_point'] ?? 0);
        $maxTimes = (int) ($data['max_times'] ?? 0);
        $maxPoint = 0.0;
        $diem_cong = 0.0;
        $diem_tru = 0.0;

        if ($type === 'CO_DINH') {
            $diem_cong = $fixed;
            $diem_tru = 0.0;
            $maxPoint = $fixed;
        } elseif ($type === 'CONG_THEO_LAN') {
            $diem_cong = $add;
            $diem_tru = 0.0;
            $maxPoint = $add * $maxTimes;
        } elseif ($type === 'TRU_THEO_LAN') {
            $diem_cong = 0.0;
            $diem_tru = $deduct;
            $maxPoint = (float) ($data['max_point'] ?? 0);
        }

        $statement = $this->db->prepare(
            'INSERT INTO tieu_chi_danh_gia
                (MA_DANH_MUC, TEN_TIEU_CHI, MO_TA_TIEU_CHI,
                 DIEM_CONG, DIEM_TRU, DIEM_TOI_DA_TC,
                 LAN_THUC_HIEN_TOI_DA, LOAI_TIEU_CHI, SU_DUNG_CHO_HOAT_DONG,
                 THU_TU_HIEN_THI, TRANG_THAI_TC)
             VALUES
                (:category_id, :name, :description,
                 :diem_cong, :diem_tru, :max_point,
                 :max_times, :type, :use_for_activity,
                 :display_order, :status)'
        );

        return $statement->execute([
            'category_id' => $data['category_id'],
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'diem_cong' => $diem_cong,
            'diem_tru' => $diem_tru,
            'max_point' => $maxPoint,
            'max_times' => $maxTimes,
            'type' => $type,
            'use_for_activity' => !empty($data['use_for_activity']) ? 1 : 0,
            'display_order' => $data['display_order'] ?? 0,
            'status' => $data['status'] ?? 'HOAT_DONG',
        ]);
    }

    public function updateCriteria(int $id, array $data): bool
    {
        // compute DIEM_CONG/DIEM_TRU/DIEM_TOI_DA_TC according to type
        $type = $data['type'] ?? '';
        $fixed = (float) ($data['fixed_point'] ?? 0);
        $add = (float) ($data['add_point'] ?? 0);
        $deduct = (float) ($data['deduct_point'] ?? 0);
        $maxTimes = (int) ($data['max_times'] ?? 0);
        $maxPoint = 0.0;
        $diem_cong = 0.0;
        $diem_tru = 0.0;

        if ($type === 'CO_DINH') {
            $diem_cong = $fixed;
            $diem_tru = 0.0;
            $maxPoint = $fixed;
        } elseif ($type === 'CONG_THEO_LAN') {
            $diem_cong = $add;
            $diem_tru = 0.0;
            $maxPoint = $add * $maxTimes;
        } elseif ($type === 'TRU_THEO_LAN') {
            $diem_cong = 0.0;
            $diem_tru = $deduct;
            $maxPoint = (float) ($data['max_point'] ?? 0);
        }

        $statement = $this->db->prepare(
            'UPDATE tieu_chi_danh_gia SET
                MA_DANH_MUC = :category_id,
                TEN_TIEU_CHI = :name,
                MO_TA_TIEU_CHI = :description,
                DIEM_CONG = :add_point,
                DIEM_TRU = :deduct_point,
                DIEM_TOI_DA_TC = :max_point,
                LAN_THUC_HIEN_TOI_DA = :max_times,
                LOAI_TIEU_CHI = :type,
                SU_DUNG_CHO_HOAT_DONG = :use_for_activity,
                THU_TU_HIEN_THI = :display_order,
                TRANG_THAI_TC = :status
             WHERE MA_TIEU_CHI = :id'
        );

        $statement->execute([
            'id' => $id,
            'category_id' => $data['category_id'],
            'name' => trim($data['name']),
            'description' => trim($data['description'] ?? ''),
            'add_point' => $diem_cong,
            'deduct_point' => $diem_tru,
            'max_point' => $maxPoint,
            'max_times' => $maxTimes,
            'type' => $type,
            'use_for_activity' => !empty($data['use_for_activity']) ? 1 : 0,
            'display_order' => $data['display_order'] ?? 0,
            'status' => $data['status'] ?? 'HOAT_DONG',
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
