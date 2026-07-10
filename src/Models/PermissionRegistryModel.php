<?php

namespace KhoaLuan\QLDRL\Models;

use InvalidArgumentException;
use PDO;
use Throwable;

class PermissionRegistryModel
{
    private const REGISTRY_FILE = __DIR__ . '/../Config/PermissionRegistry.php';

    public function __construct(private PDO $db)
    {
    }

    public function getRegistryFunctions(): array
    {
        [$functions] = $this->loadRegistry();

        return $functions;
    }

    public function getRegistryErrors(): array
    {
        [, $errors] = $this->loadRegistry();

        return $errors;
    }

    public function getPendingFunctions(): array
    {
        return array_values(array_filter(
            $this->getRegistryFunctions(),
            fn(array $function): bool => !$this->functionExists($function)
        ));
    }

    public function getRegisteredRegistryFunctions(): array
    {
        $registered = [];

        foreach ($this->getRegistryFunctions() as $function) {
            $databaseRow = $this->findExistingFunction($function);
            if ($databaseRow === null) {
                continue;
            }

            $function['DB_ROW'] = $databaseRow;
            $function['ASSIGNMENT_COUNT'] = $this->countAssignments((int) ($databaseRow['MA_CHUC_NANG'] ?? 0));
            $registered[] = $function;
        }

        return $registered;
    }

    public function registerByCode(string $code): bool
    {
        $function = $this->findRegistryFunctionByCode($code);
        if ($function === null) {
            throw new InvalidArgumentException('Chức năng không tồn tại trong PermissionRegistry.php.');
        }

        if ($this->functionExists($function)) {
            throw new InvalidArgumentException('Chức năng đã tồn tại trong database.');
        }

        if ($this->hasPageConflict($function)) {
            throw new InvalidArgumentException('PAGE hoặc MENU_PAGE đã được sử dụng bởi chức năng khác trong database.');
        }

        $statement = $this->db->prepare(
            'INSERT INTO chuc_nang
                (MA_CHUC_NANG_CODE, TEN_CHUC_NANG, PAGE, MODULE, ICON, THU_TU, TRANG_THAI_CN)
             VALUES
                (:code, :name, :page, :module, :icon, :order_number, :status)'
        );

        return $statement->execute([
            'code' => $function['MA_CHUC_NANG_CODE'],
            'name' => $function['TEN_CHUC_NANG'],
            'page' => $function['PAGE'],
            'module' => $function['MODULE'],
            'icon' => $function['ICON'],
            'order_number' => $function['THU_TU'],
            'status' => $function['TRANG_THAI_CN'],
        ]);
    }

    public function registerAllPending(): int
    {
        $registeredCount = 0;

        foreach ($this->getPendingFunctions() as $function) {
            try {
                if ($this->registerByCode((string) $function['MA_CHUC_NANG_CODE'])) {
                    $registeredCount++;
                }
            } catch (Throwable) {
                continue;
            }
        }

        return $registeredCount;
    }

    public function deleteByCodeIfUnassigned(string $code): bool
    {
        $function = $this->findRegistryFunctionByCode($code);
        if ($function === null) {
            throw new InvalidArgumentException('Chức năng không tồn tại trong PermissionRegistry.php.');
        }

        $databaseRow = $this->findExistingFunction($function);
        if ($databaseRow === null) {
            throw new InvalidArgumentException('Chức năng chưa tồn tại trong database.');
        }

        $functionId = (int) ($databaseRow['MA_CHUC_NANG'] ?? 0);
        if ($this->countAssignments($functionId) > 0) {
            throw new InvalidArgumentException('Không thể xóa chức năng đang được cấp quyền cho vai trò. Vui lòng gỡ quyền trước.');
        }

        $statement = $this->db->prepare(
            'DELETE FROM chuc_nang
             WHERE MA_CHUC_NANG_CODE = :code
             LIMIT 1'
        );

        return $statement->execute(['code' => $function['MA_CHUC_NANG_CODE']]);
    }

    public function findRegistryFunctionByCode(string $code): ?array
    {
        $code = trim($code);
        if ($code === '') {
            return null;
        }

        foreach ($this->getRegistryFunctions() as $function) {
            if ((string) ($function['MA_CHUC_NANG_CODE'] ?? '') === $code) {
                return $function;
            }
        }

        return null;
    }

    private function functionExists(array $function): bool
    {
        return $this->findExistingFunction($function) !== null;
    }

    private function findExistingFunction(array $function): ?array
    {
        $statement = $this->db->prepare(
            'SELECT MA_CHUC_NANG, MA_CHUC_NANG_CODE, TEN_CHUC_NANG, PAGE, MODULE, ICON, THU_TU, TRANG_THAI_CN
             FROM chuc_nang
             WHERE MA_CHUC_NANG_CODE = :code
             LIMIT 1'
        );
        $statement->execute([
            'code' => $function['MA_CHUC_NANG_CODE'],
        ]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    private function hasPageConflict(array $function): bool
    {
        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM chuc_nang
             WHERE MA_CHUC_NANG_CODE <> :code
               AND (PAGE = :page OR PAGE = :menu_page)'
        );
        $statement->execute([
            'code' => $function['MA_CHUC_NANG_CODE'],
            'page' => $function['PAGE'],
            'menu_page' => $function['MENU_PAGE'] ?? $function['PAGE'],
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    private function countAssignments(int $functionId): int
    {
        if ($functionId < 1) {
            return 0;
        }

        $statement = $this->db->prepare(
            'SELECT COUNT(*)
             FROM vai_tro_chuc_nang
             WHERE MA_CHUC_NANG = :function_id'
        );
        $statement->execute(['function_id' => $functionId]);

        return (int) $statement->fetchColumn();
    }

    private function loadRegistry(): array
    {
        if (!is_file(self::REGISTRY_FILE)) {
            return [[], ['Không tìm thấy file src/Config/PermissionRegistry.php.']];
        }

        $rawRegistry = require self::REGISTRY_FILE;
        if (!is_array($rawRegistry)) {
            return [[], ['PermissionRegistry.php phải return một mảng.']];
        }

        $functions = [];
        $errors = [];
        $seenCodes = [];
        $seenPages = [];

        foreach ($rawRegistry as $index => $rawFunction) {
            if (!is_array($rawFunction)) {
                $errors[] = 'Dòng registry #' . ($index + 1) . ' không hợp lệ.';
                continue;
            }

            $function = $this->normalizeRegistryFunction($rawFunction);
            $rowLabel = $function['MA_CHUC_NANG_CODE'] !== ''
                ? $function['MA_CHUC_NANG_CODE']
                : '#' . ($index + 1);

            foreach (['MA_CHUC_NANG_CODE', 'TEN_CHUC_NANG', 'PAGE', 'MODULE'] as $requiredField) {
                if ($function[$requiredField] === '') {
                    $errors[] = $rowLabel . ': thiếu ' . $requiredField . '.';
                }
            }

            if ($function['MA_CHUC_NANG_CODE'] !== '' && isset($seenCodes[$function['MA_CHUC_NANG_CODE']])) {
                $errors[] = $rowLabel . ': trùng MA_CHUC_NANG_CODE trong registry.';
            }

            if ($function['PAGE'] !== '' && isset($seenPages[$function['PAGE']])) {
                $errors[] = $rowLabel . ': trùng PAGE trong registry.';
            }

            if (!preg_match('/^[A-Za-z0-9_.:-]+$/', $function['MA_CHUC_NANG_CODE'])) {
                $errors[] = $rowLabel . ': MA_CHUC_NANG_CODE chỉ nên gồm chữ, số, dấu gạch dưới, gạch ngang, dấu chấm hoặc dấu hai chấm.';
            }

            if ($function['TRANG_THAI_CN'] === '') {
                $function['TRANG_THAI_CN'] = 'HOAT_DONG';
            }

            $seenCodes[$function['MA_CHUC_NANG_CODE']] = true;
            $seenPages[$function['PAGE']] = true;

            if ($function['MA_CHUC_NANG_CODE'] !== '' && $function['TEN_CHUC_NANG'] !== '' && $function['PAGE'] !== '' && $function['MODULE'] !== '') {
                $functions[] = $function;
            }
        }

        usort($functions, static function (array $left, array $right): int {
            return [(int) ($left['THU_TU'] ?? 0), (string) ($left['MA_CHUC_NANG_CODE'] ?? '')]
                <=> [(int) ($right['THU_TU'] ?? 0), (string) ($right['MA_CHUC_NANG_CODE'] ?? '')];
        });

        return [$functions, $errors];
    }

    private function normalizeRegistryFunction(array $function): array
    {
        $code = trim((string) ($function['MA_CHUC_NANG_CODE'] ?? $function['code'] ?? ''));
        $page = trim((string) ($function['PAGE'] ?? $function['page'] ?? ''));

        return [
            'MA_CHUC_NANG_CODE' => $code,
            'TEN_CHUC_NANG' => trim((string) ($function['TEN_CHUC_NANG'] ?? $function['name'] ?? '')),
            'PAGE' => $page,
            'MODULE' => trim((string) ($function['MODULE'] ?? $function['module'] ?? '')),
            'ICON' => trim((string) ($function['ICON'] ?? $function['icon'] ?? '')),
            'THU_TU' => (int) ($function['THU_TU'] ?? $function['order'] ?? 0),
            'TRANG_THAI_CN' => trim((string) ($function['TRANG_THAI_CN'] ?? $function['status'] ?? 'HOAT_DONG')),
            'HIEN_THI_MENU' => (bool) ($function['HIEN_THI_MENU'] ?? $function['show_in_menu'] ?? true),
            'MENU_PAGE' => trim((string) ($function['MENU_PAGE'] ?? $function['menu_page'] ?? $page)),
            'MENU_URL' => trim((string) ($function['MENU_URL'] ?? $function['menu_url'] ?? '')),
        ];
    }
}
