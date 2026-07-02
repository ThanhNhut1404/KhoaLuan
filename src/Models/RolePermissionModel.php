<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use Throwable;

class RolePermissionModel
{
    public function __construct(private PDO $db)
    {
    }

    public function getRoles(): array
    {
        return $this->db->query(
            'SELECT MA_VAI_TRO, TEN_VAI_TRO
             FROM vai_tro
             ORDER BY MA_VAI_TRO'
        )->fetchAll() ?: [];
    }

    public function countRolePermissionSummaries(string $keyword = '', string $roleId = ''): int
    {
        [$where, $params] = $this->rolePermissionFilterClause($keyword, $roleId);
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM vai_tro vt'
             . $where
        );
        $statement->execute($params);

        return (int) $statement->fetchColumn();
    }

    public function getRolePermissionSummaries(int $page, int $perPage, string $keyword = '', string $roleId = ''): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        [$where, $params] = $this->rolePermissionFilterClause($keyword, $roleId);
        $statement = $this->db->prepare(
            'SELECT
                vt.MA_VAI_TRO,
                vt.TEN_VAI_TRO,
                COUNT(DISTINCT CASE
                    WHEN vtcn.MA_CHUC_NANG IS NULL THEN NULL
                    ELSE COALESCE(NULLIF(TRIM(cn.MODULE), \'\'), \'Khác\')
                END) AS menu_count,
                COUNT(vtcn.MA_CHUC_NANG) AS function_count
             FROM vai_tro vt
             LEFT JOIN vai_tro_chuc_nang vtcn ON vtcn.MA_VAI_TRO = vt.MA_VAI_TRO
             LEFT JOIN chuc_nang cn ON cn.MA_CHUC_NANG = vtcn.MA_CHUC_NANG'
             . $where .
            ' GROUP BY vt.MA_VAI_TRO, vt.TEN_VAI_TRO
             ORDER BY vt.MA_VAI_TRO
             LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $name => $value) {
            $statement->bindValue(':' . ltrim((string) $name, ':'), $value);
        }

        $statement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll() ?: [];
    }

    public function getPermissionDetailByRole(int|string $roleId): array
    {
        $statement = $this->db->prepare(
            'SELECT
                cn.MA_CHUC_NANG,
                cn.MA_CHUC_NANG_CODE,
                cn.TEN_CHUC_NANG,
                cn.PAGE,
                cn.MODULE,
                cn.THU_TU
             FROM vai_tro_chuc_nang vtcn
             INNER JOIN chuc_nang cn ON cn.MA_CHUC_NANG = vtcn.MA_CHUC_NANG
             WHERE vtcn.MA_VAI_TRO = :role_id
             ORDER BY COALESCE(cn.THU_TU, 0), cn.MA_CHUC_NANG'
        );
        $statement->execute(['role_id' => $roleId]);
        $permissions = $statement->fetchAll() ?: [];

        $grouped = [];
        foreach ($permissions as $permission) {
            $module = trim((string) ($permission['MODULE'] ?? 'Khác'));
            if ($module === '') {
                $module = 'Khác';
            }

            $grouped[$module][] = $permission;
        }

        return $grouped;
    }

    public function getRoleById(int|string $roleId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT MA_VAI_TRO, TEN_VAI_TRO
             FROM vai_tro
             WHERE MA_VAI_TRO = :role_id
             LIMIT 1'
        );
        $statement->execute(['role_id' => $roleId]);
        $role = $statement->fetch();

        return $role ?: null;
    }

    public function getActiveFunctionsGroupedByModule(): array
    {
        $functions = $this->db->query(
            "SELECT MA_CHUC_NANG, MA_CHUC_NANG_CODE, TEN_CHUC_NANG, PAGE, MODULE, THU_TU
             FROM chuc_nang
             WHERE TRANG_THAI_CN = 'HOAT_DONG'
             ORDER BY COALESCE(THU_TU, 0), MA_CHUC_NANG"
        )->fetchAll() ?: [];

        $grouped = [];
        foreach ($functions as $function) {
            $module = trim((string) ($function['MODULE'] ?? 'Khác'));
            if ($module === '') {
                $module = 'Khác';
            }

            $grouped[$module][] = $function;
        }

        return $grouped;
    }

    public function getActiveFunctionIds(): array
    {
        $ids = $this->db->query(
            "SELECT MA_CHUC_NANG
             FROM chuc_nang
             WHERE TRANG_THAI_CN = 'HOAT_DONG'"
        )->fetchAll(PDO::FETCH_COLUMN) ?: [];

        return array_map('intval', $ids);
    }

    public function getPermissionIdsByRole(int|string $roleId): array
    {
        $statement = $this->db->prepare(
            'SELECT MA_CHUC_NANG
             FROM vai_tro_chuc_nang
             WHERE MA_VAI_TRO = :role_id
             ORDER BY MA_CHUC_NANG'
        );
        $statement->execute(['role_id' => $roleId]);

        return array_map('intval', $statement->fetchAll(PDO::FETCH_COLUMN) ?: []);
    }

    public function savePermissions(int|string $roleId, array $functionIds): bool
    {
        $this->db->beginTransaction();

        try {
            $delete = $this->db->prepare(
                'DELETE FROM vai_tro_chuc_nang
                 WHERE MA_VAI_TRO = :role_id'
            );
            $delete->execute(['role_id' => $roleId]);

            if (!empty($functionIds)) {
                $insert = $this->db->prepare(
                    'INSERT INTO vai_tro_chuc_nang (MA_VAI_TRO, MA_CHUC_NANG)
                     VALUES (:role_id, :function_id)'
                );

                foreach ($functionIds as $functionId) {
                    $insert->execute([
                        'role_id' => $roleId,
                        'function_id' => $functionId,
                    ]);
                }
            }

            $this->db->commit();

            return true;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    private function rolePermissionFilterClause(string $keyword, string $roleId): array
    {
        $conditions = [];
        $params = [];

        $keyword = trim($keyword);
        if ($keyword !== '') {
            $textKeyword = function_exists('mb_strtolower') ? mb_strtolower($keyword, 'UTF-8') : strtolower($keyword);
            $conditions[] = 'LOWER(vt.TEN_VAI_TRO) LIKE :keyword';
            $params['keyword'] = '%' . $textKeyword . '%';
        }

        $roleId = trim($roleId);
        if ($roleId !== '' && ctype_digit($roleId) && (int) $roleId > 0) {
            $conditions[] = 'vt.MA_VAI_TRO = :role_id';
            $params['role_id'] = (int) $roleId;
        }

        return [empty($conditions) ? '' : ' WHERE ' . implode(' AND ', $conditions), $params];
    }
}
