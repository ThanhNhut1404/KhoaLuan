<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\RolePermissionModel;
use Throwable;

class RolePermissionController
{
    private const LIST_PER_PAGE = 10;

    private RolePermissionModel $permissions;

    public function __construct(?RolePermissionModel $permissions = null)
    {
        $this->permissions = $permissions ?? new RolePermissionModel(Database::getConnection());
    }

    public function handle(array $get, array $post, string $method, array $adminSession): array
    {
        $roles = $this->permissions->getRoles();
        if (!$this->adminHasRole($adminSession, 'ADMIN')) {
            return $this->forbiddenState($roles);
        }

        $selectedRoleId = $this->resolveSelectedRoleId($get, $post, $roles);
        $selectedRole = $selectedRoleId !== null ? $this->permissions->getRoleById($selectedRoleId) : null;
        $errors = [];
        $toast = null;

        if ($selectedRole === null && $selectedRoleId !== null) {
            $errors['role_id'] = 'Vai trò không tồn tại.';
        }

        if ($method === 'POST') {
            [$errors, $toast] = $this->save($post, $selectedRole, $selectedRoleId);
            $selectedRole = $selectedRoleId !== null ? $this->permissions->getRoleById($selectedRoleId) : null;
        }

        return [
            'roles' => $roles,
            'selectedRoleId' => $selectedRoleId,
            'selectedRole' => $selectedRole,
            'functionsByModule' => $this->permissions->getActiveFunctionsGroupedByModule(),
            'assignedPermissionIds' => $selectedRoleId !== null ? $this->permissions->getPermissionIdsByRole($selectedRoleId) : [],
            'errors' => $errors,
            'toast' => $toast,
            'forbidden' => false,
        ];
    }

    public function listPermissions(array $get, array $adminSession): array
    {
        $roles = $this->permissions->getRoles();
        if (!$this->adminHasRole($adminSession, 'ADMIN')) {
            return [
                'roles' => $roles,
                'rolePermissionRows' => [],
                'rolePermissionDetails' => [],
                'filters' => [],
                'pagination' => [
                    'current_page' => 1,
                    'total_items' => 0,
                    'items_per_page' => self::LIST_PER_PAGE,
                    'total_pages' => 1,
                    'from' => 0,
                    'to' => 0,
                ],
                'emptyMessage' => 'Bạn không có quyền truy cập chức năng này.',
                'toast' => ['type' => 'error', 'message' => 'Bạn không có quyền truy cập chức năng này.'],
                'forbidden' => true,
            ];
        }

        $page = max(1, (int) ($get['page_num'] ?? 1));
        $filters = [
            'keyword' => $this->searchKeyword($get['keyword'] ?? $get['q'] ?? $get['search'] ?? ''),
            'role_id' => $this->positiveIdFilter($get['role_id'] ?? ''),
        ];
        $hasFilters = $filters['keyword'] !== '' || $filters['role_id'] !== '';
        $totalItems = $this->permissions->countRolePermissionSummaries($filters['keyword'], $filters['role_id']);
        $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
        $currentPage = min($page, $totalPages);
        $rows = $totalItems > 0
            ? $this->permissions->getRolePermissionSummaries($currentPage, self::LIST_PER_PAGE, $filters['keyword'], $filters['role_id'])
            : [];

        $details = [];
        foreach ($rows as $row) {
            $roleId = (int) ($row['MA_VAI_TRO'] ?? 0);
            if ($roleId < 1) {
                continue;
            }

            $details[$roleId] = [
                'role_id' => $roleId,
                'role_name' => (string) ($row['TEN_VAI_TRO'] ?? ''),
                'menu_count' => (int) ($row['menu_count'] ?? 0),
                'function_count' => (int) ($row['function_count'] ?? 0),
                'groups' => $this->permissions->getPermissionDetailByRole($roleId),
            ];
        }

        return [
            'roles' => $roles,
            'rolePermissionRows' => $rows,
            'rolePermissionDetails' => $details,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'total_items' => $totalItems,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => $totalPages,
                'from' => $totalItems === 0 ? 0 : (($currentPage - 1) * self::LIST_PER_PAGE) + 1,
                'to' => min($totalItems, $currentPage * self::LIST_PER_PAGE),
            ],
            'emptyMessage' => $hasFilters ? 'Không có phân quyền phù hợp.' : 'Chưa có vai trò nào.',
            'toast' => null,
            'forbidden' => false,
        ];
    }

    private function save(array $post, ?array $selectedRole, ?int $selectedRoleId): array
    {
        if ($selectedRole === null || $selectedRoleId === null) {
            return [
                ['role_id' => 'Vai trò không tồn tại.'],
                ['type' => 'error', 'message' => 'Không thể lưu phân quyền cho vai trò không hợp lệ.'],
            ];
        }

        $postedIds = $post['permission_ids'] ?? [];
        if (!is_array($postedIds)) {
            $postedIds = [];
        }

        $selectedIds = array_values(array_unique(array_map('intval', $postedIds)));
        $activeIds = $this->permissions->getActiveFunctionIds();
        $activeLookup = array_fill_keys($activeIds, true);
        $validIds = array_values(array_filter(
            $selectedIds,
            static fn(int $id): bool => isset($activeLookup[$id])
        ));

        if (($selectedRole['TEN_VAI_TRO'] ?? '') === 'ADMIN' && empty($validIds)) {
            return [
                ['permission_ids' => 'Không được xóa hết quyền của ADMIN.'],
                ['type' => 'error', 'message' => 'ADMIN phải còn ít nhất một quyền.'],
            ];
        }

        try {
            $this->permissions->savePermissions($selectedRoleId, $validIds);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());

            return [
                [],
                ['type' => 'error', 'message' => 'Lưu phân quyền thất bại. Vui lòng thử lại.'],
            ];
        }

        return [
            [],
            ['type' => 'success', 'message' => 'Lưu phân quyền thành công.'],
        ];
    }

    private function resolveSelectedRoleId(array $get, array $post, array $roles): ?int
    {
        $roleId = trim((string) ($post['role_id'] ?? $get['role_id'] ?? ''));
        if ($roleId !== '' && ctype_digit($roleId)) {
            return (int) $roleId;
        }

        return isset($roles[0]['MA_VAI_TRO']) ? (int) $roles[0]['MA_VAI_TRO'] : null;
    }

    private function forbiddenState(array $roles): array
    {
        return [
            'roles' => $roles,
            'selectedRoleId' => null,
            'selectedRole' => null,
            'functionsByModule' => [],
            'assignedPermissionIds' => [],
            'errors' => ['permission' => 'Bạn không có quyền truy cập chức năng này.'],
            'toast' => ['type' => 'error', 'message' => 'Bạn không có quyền truy cập chức năng này.'],
            'forbidden' => true,
        ];
    }

    private function positiveIdFilter(mixed $value): string
    {
        $value = trim((string) $value);

        return $value !== '' && ctype_digit($value) && (int) $value > 0 ? $value : '';
    }

    private function searchKeyword(mixed $value): string
    {
        $keyword = preg_replace('/\s+/u', ' ', trim((string) $value));
        if ($keyword === '') {
            return '';
        }

        if ($this->length($keyword) <= 100) {
            return $keyword;
        }

        return function_exists('mb_substr') ? mb_substr($keyword, 0, 100, 'UTF-8') : substr($keyword, 0, 100);
    }

    private function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    }

    private function adminHasRole(array $adminSession, string $roleCode): bool
    {
        foreach (['role_code', 'TEN_VAI_TRO', 'role_name'] as $key) {
            if ((string) ($adminSession[$key] ?? '') === $roleCode) {
                return true;
            }
        }

        $roles = is_array($adminSession['roles'] ?? null) ? $adminSession['roles'] : [];
        foreach ($roles as $role) {
            if (!is_array($role)) {
                continue;
            }

            foreach (['role_code', 'TEN_VAI_TRO', 'role_name'] as $key) {
                if ((string) ($role[$key] ?? '') === $roleCode) {
                    return true;
                }
            }
        }

        return false;
    }
}
