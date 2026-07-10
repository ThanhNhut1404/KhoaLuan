<?php

namespace KhoaLuan\QLDRL\Controllers;

use InvalidArgumentException;
use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\PermissionRegistryModel;
use Throwable;

class PermissionRegistryController
{
    private PermissionRegistryModel $registry;

    public function __construct(?PermissionRegistryModel $registry = null)
    {
        $this->registry = $registry ?? new PermissionRegistryModel(Database::getConnection());
    }

    public function handle(array $get, array $post, string $method, array $adminSession): array
    {
        if (!$this->adminHasRole($adminSession, 'ADMIN')) {
            return [
                'pendingFunctions' => [],
                'registeredFunctions' => [],
                'registryErrors' => [],
                'toast' => ['type' => 'error', 'message' => 'Bạn không có quyền truy cập chức năng này.'],
                'forbidden' => true,
            ];
        }

        $toast = null;
        if ($method === 'POST') {
            $toast = $this->handlePost($post);
        }

        return [
            'pendingFunctions' => $this->registry->getPendingFunctions(),
            'registeredFunctions' => $this->registry->getRegisteredRegistryFunctions(),
            'registryErrors' => $this->registry->getRegistryErrors(),
            'toast' => $toast,
            'forbidden' => false,
        ];
    }

    private function handlePost(array $post): array
    {
        $action = trim((string) ($post['action'] ?? ''));

        try {
            if ($action === 'register') {
                $code = trim((string) ($post['code'] ?? ''));
                $this->registry->registerByCode($code);

                return ['type' => 'success', 'message' => 'Đăng ký chức năng thành công.'];
            }

            if ($action === 'register_all') {
                $registeredCount = $this->registry->registerAllPending();

                return $registeredCount > 0
                    ? ['type' => 'success', 'message' => 'Đã đăng ký ' . $registeredCount . ' chức năng.']
                    : ['type' => 'info', 'message' => 'Không có chức năng mới cần đăng ký.'];
            }

            if ($action === 'delete') {
                $code = trim((string) ($post['code'] ?? ''));
                $this->registry->deleteByCodeIfUnassigned($code);

                return ['type' => 'success', 'message' => 'Đã xóa chức năng khỏi database.'];
            }

            return ['type' => 'error', 'message' => 'Thao tác không hợp lệ.'];
        } catch (InvalidArgumentException $exception) {
            return ['type' => 'error', 'message' => $exception->getMessage()];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());

            return ['type' => 'error', 'message' => 'Không thể xử lý đăng ký chức năng. Vui lòng thử lại.'];
        }
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
