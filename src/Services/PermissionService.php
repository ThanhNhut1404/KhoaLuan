<?php

namespace KhoaLuan\QLDRL\Services;

use KhoaLuan\QLDRL\Config\Database;
use PDO;

class PermissionService
{
    public function __construct(private ?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    public function getPermissionsByRole(int|string $roleId): array
    {
        $statement = $this->db->prepare(
            "SELECT
                cn.MA_CHUC_NANG,
                cn.MA_CHUC_NANG_CODE,
                cn.TEN_CHUC_NANG,
                cn.PAGE,
                cn.MODULE,
                cn.ICON,
                cn.THU_TU,
                cn.TRANG_THAI_CN
             FROM vai_tro_chuc_nang vtcn
             INNER JOIN chuc_nang cn ON cn.MA_CHUC_NANG = vtcn.MA_CHUC_NANG
             WHERE vtcn.MA_VAI_TRO = :role_id
               AND cn.TRANG_THAI_CN = 'HOAT_DONG'
             ORDER BY COALESCE(cn.THU_TU, 0), cn.MA_CHUC_NANG"
        );
        $statement->execute(['role_id' => $roleId]);

        return $statement->fetchAll() ?: [];
    }

    public function getPermissionsByRoles(array $roleIds): array
    {
        $permissions = [];
        $seen = [];

        foreach ($roleIds as $roleId) {
            if ($roleId === null || $roleId === '') {
                continue;
            }

            foreach ($this->getPermissionsByRole($roleId) as $permission) {
                $key = (string) ($permission['MA_CHUC_NANG'] ?? $permission['PAGE'] ?? '');
                if ($key === '' || isset($seen[$key])) {
                    continue;
                }

                $seen[$key] = true;
                $permissions[] = $permission;
            }
        }

        usort($permissions, static function (array $left, array $right): int {
            return [(int) ($left['THU_TU'] ?? 0), (int) ($left['MA_CHUC_NANG'] ?? 0)]
                <=> [(int) ($right['THU_TU'] ?? 0), (int) ($right['MA_CHUC_NANG'] ?? 0)];
        });

        return $permissions;
    }

    public function canAccess(int|string $roleId, string $page): bool
    {
        $allowedPages = $this->extractAllowedPages($this->getPermissionsByRole($roleId));
        foreach ($this->permissionKeysForPage($page) as $permissionPage) {
            if (isset($allowedPages[$permissionPage])) {
                return true;
            }
        }

        return false;
    }

    public function canAccessAny(array $roleIds, string $page): bool
    {
        foreach ($roleIds as $roleId) {
            if ($roleId !== null && $roleId !== '' && $this->canAccess($roleId, $page)) {
                return true;
            }
        }

        return false;
    }

    public function buildSidebarMenu(array $roleIds): array
    {
        $permissions = $this->getPermissionsByRoles($roleIds);
        $allowedPages = $this->extractAllowedPages($permissions);
        $menu = [];

        foreach ($this->menuCatalog() as $parent) {
            $children = [];

            foreach ($parent['children'] as $child) {
                if (!$this->isAllowedCatalogPage($child['page'], $allowedPages)) {
                    continue;
                }

                $children[] = $child;
            }

            if (empty($children)) {
                continue;
            }

            $parent['children'] = $children;
            $menu[] = $parent;
        }

        return $menu;
    }

    private function extractAllowedPages(array $permissions): array
    {
        $allowed = [];

        foreach ($permissions as $permission) {
            $page = trim((string) ($permission['PAGE'] ?? ''));
            if ($page !== '') {
                $allowed[$page] = true;
            }
        }

        return $allowed;
    }

    private function isAllowedCatalogPage(string $page, array $allowedPages): bool
    {
        foreach ($this->permissionKeysForPage($page) as $permissionPage) {
            if (isset($allowedPages[$permissionPage])) {
                return true;
            }
        }

        return false;
    }

    private function permissionKeysForPage(string $page): array
    {
        $page = trim($page);
        if ($page === '') {
            return [];
        }

        $aliases = [
            'list_khoa' => ['list_departments'],
            'list_major' => ['list_majors'],
            'list_class' => ['list_classes'],
            'list_year' => ['list_academic_years'],
            'list_activity' => ['list_activities'],
            'roles' => ['role_permission'],
            'role_permission' => ['roles'],
        ];

        return array_values(array_unique(array_merge([$page], $aliases[$page] ?? [])));
    }

    private function menuCatalog(): array
    {
        return [
            [
                'label' => 'Tổng quan',
                'icon' => 'dashboard',
                'children' => [
                    ['page' => 'dashboard', 'label' => 'Tổng quan', 'url' => '?page=dashboard'],
                ],
            ],
            [
                'label' => 'Quản lý khoa/bộ môn',
                'icon' => 'department',
                'children' => [
                    ['page' => 'create_khoa', 'label' => 'Tạo khoa/bộ môn', 'url' => '?page=create_khoa'],
                    ['page' => 'list_khoa', 'label' => 'Danh sách khoa/bộ môn', 'url' => '?page=list_khoa'],
                ],
            ],
            [
                'label' => 'Quản lý ngành học',
                'icon' => 'major',
                'children' => [
                    ['page' => 'create_major', 'label' => 'Tạo ngành học', 'url' => '?page=create_major'],
                    ['page' => 'list_major', 'label' => 'Danh sách ngành học', 'url' => '?page=list_major'],
                ],
            ],
            [
                'label' => 'Quản lý lớp',
                'icon' => 'class',
                'children' => [
                    ['page' => 'create_class', 'label' => 'Tạo lớp học', 'url' => '?page=create_class'],
                    ['page' => 'list_class', 'label' => 'Danh sách lớp học', 'url' => '?page=list_class'],
                ],
            ],
            [
                'label' => 'Quản lý niên khóa',
                'icon' => 'year',
                'children' => [
                    ['page' => 'create_year', 'label' => 'Tạo niên khóa', 'url' => '?page=create_year'],
                    ['page' => 'list_year', 'label' => 'Danh sách niên khóa', 'url' => '?page=list_year'],
                ],
            ],
            [
                'label' => 'Quản lý học kỳ',
                'icon' => 'semester',
                'children' => [
                    ['page' => 'create_semester', 'label' => 'Tạo học kỳ', 'url' => '?page=create_semester'],
                    ['page' => 'list_semester', 'label' => 'Danh sách học kỳ', 'url' => '?page=list_semester'],
                ],
            ],
            [
                'label' => 'Quản lý sinh viên',
                'icon' => 'student',
                'children' => [
                    ['page' => 'create_student', 'label' => 'Cấp tài khoản sinh viên', 'url' => '/KhoaLuan/public/admin.php?page=create_student'],
                    ['page' => 'list_students', 'label' => 'Danh sách sinh viên', 'url' => '?page=list_students'],
                ],
            ],
            [
                'label' => 'Quản lý tài khoản',
                'icon' => 'account',
                'children' => [
                    ['page' => 'create_account', 'label' => 'Cấp tài khoản', 'url' => '?page=create_account'],
                    ['page' => 'list_accounts', 'label' => 'Danh sách tài khoản', 'url' => '?page=list_accounts'],
                ],
            ],
            [
                'label' => 'Quản lý phân quyền',
                'icon' => 'permission',
                'children' => [
                    ['page' => 'roles', 'label' => 'Cấp quyền truy cập', 'url' => '?page=roles'],
                ],
            ],
            [
                'label' => 'Quản lý tiêu chí điểm',
                'icon' => 'criteria',
                'children' => [
                    ['page' => 'setup_criteria', 'label' => 'Thiết lập tiêu chí', 'url' => '?page=setup_criteria'],
                ],
            ],
            [
                'label' => 'Quản lý hoạt động',
                'icon' => 'activity',
                'children' => [
                    ['page' => 'create_activity', 'label' => 'Tạo hoạt động', 'url' => '?page=create_activity'],
                    ['page' => 'list_activity', 'label' => 'Danh sách hoạt động', 'url' => '?page=list_activity'],
                ],
            ],
            [
                'label' => 'Quản lý đánh giá',
                'icon' => 'evaluation',
                'children' => [
                    ['page' => 'list_evaluations', 'label' => 'Chấm điểm rèn luyện', 'url' => '?page=list_evaluations'],
                ],
            ],
            [
                'label' => 'Quản lý thống kê',
                'icon' => 'statistics',
                'children' => [
                    ['page' => 'statistics', 'label' => 'Danh sách thống kê', 'url' => '?page=statistics'],
                ],
            ],
        ];
    }
}
