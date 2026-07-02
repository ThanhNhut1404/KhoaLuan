<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\MajorModel;
use Throwable;

class MajorController
{
    private const LIST_PER_PAGE = 10;
    private const MAX_NAME_LENGTH = 50;
    private const MAX_CODE_LENGTH = 20;
    private const MAX_DESCRIPTION_LENGTH = 500;

    private const STATUS_OPTIONS = [
        ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
        ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
    ];

    public function __construct(private ?MajorModel $model = null)
    {
        $this->model = $this->model ?? new MajorModel(Database::getConnection());
    }

    public function create(array $data, string $method): array
    {
        $state = [
            'formData' => $method === 'POST' ? $this->formData($data) : [],
            'errors' => [],
            'departments' => $this->safeDepartments(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
        ];

        if ($method !== 'POST') {
            return $state;
        }

        $form = $this->formData($data);
        $state['formData'] = $form;
        $state['errors'] = $this->validate($form);

        if (!empty($state['errors'])) {
            return $state;
        }

        $departmentId = (int) $form['department'];

        try {
            if (!$this->model->departmentExists($departmentId)) {
                $state['errors']['department'] = 'Khoa trực thuộc không tồn tại hoặc đã bị xóa.';
                return $state;
            }

            if ($this->model->existsByCode($form['major_code'])) {
                $state['errors']['major_code'] = 'Tên viết tắt đã tồn tại.';
                return $state;
            }

            if ($this->model->existsByNameInDepartment($form['major_name'], $departmentId)) {
                $state['errors']['major_name'] = 'Tên ngành đã tồn tại trong khoa/bộ môn này.';
                return $state;
            }

            $created = $this->model->create([
                'department_id' => $departmentId,
                'name' => $form['major_name'],
                'short_name' => $form['major_code'],
                'description' => $form['description'],
                'status' => $form['status'],
            ]);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());

            if ($this->model->isDuplicateException($exception)) {
                return $this->duplicateState($state, $form, $departmentId);
            }

            if ($this->model->isConstraintException($exception)) {
                $state['errors']['department'] = 'Khoa trực thuộc không hợp lệ hoặc không còn tồn tại.';
                return $state;
            }

            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tạo ngành học. Vui lòng thử lại.'];
            return $state;
        }

        $state['toast'] = [
            'type' => $created ? 'success' : 'error',
            'message' => $created ? 'Tạo ngành học thành công.' : 'Tạo ngành học thất bại. Vui lòng thử lại.',
        ];

        if ($created) {
            $state['formData'] = [];
        }

        return $state;
    }

    public function listing(array $data, array $query, string $method): array
    {
        $page = max(1, (int) ($query['page_num'] ?? 1));
        $statusFilter = trim((string) ($query['status'] ?? ''));
        if ($statusFilter !== '' && !in_array($statusFilter, array_column(self::STATUS_OPTIONS, 'value'), true)) {
            $statusFilter = '';
        }

        $filters = [
            'keyword' => $this->searchKeyword($query['keyword'] ?? $query['q'] ?? $query['search'] ?? ''),
            'status' => $statusFilter,
        ];

        $state = [
            'majors' => [],
            'statusOptions' => self::STATUS_OPTIONS,
            'filters' => $filters,
            'emptyMessage' => 'Chưa có ngành học nào.',
            'pagination' => [
                'current_page' => $page,
                'total_items' => 0,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => 1,
                'from' => 0,
                'to' => 0,
            ],
            'toast' => null,
        ];

        if ($method === 'POST') {
            $state['toast'] = $this->handleListAction($data);
        }

        try {
            return $this->loadListState($state, $page, $filters);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());

            $hasFilters = $filters['keyword'] !== '' || $filters['status'] !== '';
            $state['emptyMessage'] = $hasFilters ? 'Không có ngành học phù hợp.' : 'Chưa có ngành học nào.';
            $state['pagination'] = [
                'current_page' => 1,
                'total_items' => 0,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => 1,
                'from' => 0,
                'to' => 0,
            ];
            $state['toast'] ??= [
                'type' => 'error',
                'message' => $hasFilters
                    ? 'Đã xảy ra lỗi khi tìm kiếm ngành học. Vui lòng thử lại.'
                    : 'Không thể tải danh sách ngành học.',
            ];

            return $state;
        }
    }

    public function editState(int $id, array $data, string $method): array
    {
        $state = [
            'formData' => [],
            'errors' => [],
            'departments' => $this->safeDepartments(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
            'redirect' => null,
            'isEdit' => true,
        ];

        if ($id < 1) {
            $state['toast'] = ['type' => 'error', 'message' => 'Ngành học không hợp lệ.'];
            $state['redirect'] = '?page=list_major';
            return $state;
        }

        if ($method === 'POST') {
            $form = $this->formData($data);
            $state['formData'] = $form;
            $major = null;

            try {
                $major = $this->model->findById($id);
                if ($major === null) {
                    $state['toast'] = ['type' => 'error', 'message' => 'Ngành học không tồn tại hoặc đã bị xóa.'];
                    $state['redirect'] = '?page=list_major';
                    return $state;
                }
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                $state['toast'] = ['type' => 'error', 'message' => 'Không thể kiểm tra dữ liệu ngành học. Vui lòng thử lại.'];
                return $state;
            }

            $state['errors'] = $this->validate($form);
            if (!empty($state['errors'])) {
                return $state;
            }

            $departmentId = (int) $form['department'];

            try {
                if (!$this->model->departmentExists($departmentId)) {
                    $state['errors']['department'] = 'Khoa trực thuộc không tồn tại hoặc đã bị xóa.';
                    return $state;
                }

                if ($this->model->existsByCodeExcept($form['major_code'], $id)) {
                    $state['errors']['major_code'] = 'Tên viết tắt đã tồn tại.';
                    return $state;
                }

                if ($this->model->existsByNameInDepartmentExcept($form['major_name'], $departmentId, $id)) {
                    $state['errors']['major_name'] = 'Tên ngành đã tồn tại trong khoa/bộ môn này.';
                    return $state;
                }

                if ($this->majorHasNoChanges($major, $form, $departmentId)) {
                    $state['toast'] = ['type' => 'info', 'message' => 'Không có thay đổi nào được thực hiện.'];
                    $state['redirect'] = '?page=list_major';
                    return $state;
                }

                $updated = $this->model->update($id, [
                    'department_id' => $departmentId,
                    'name' => $form['major_name'],
                    'short_name' => $form['major_code'],
                    'description' => $form['description'],
                    'status' => $form['status'],
                ]);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());

                if ($this->model->isDuplicateException($exception)) {
                    return $this->duplicateState($state, $form, $departmentId, $id);
                }

                if ($this->model->isConstraintException($exception)) {
                    $state['errors']['department'] = 'Khoa trực thuộc không hợp lệ hoặc không còn tồn tại.';
                    return $state;
                }

                $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi cập nhật ngành học. Vui lòng thử lại.'];
                return $state;
            }

            $state['toast'] = [
                'type' => $updated ? 'success' : 'info',
                'message' => $updated ? 'Cập nhật ngành học thành công.' : 'Không có thay đổi nào được thực hiện.',
            ];

            if ($updated || ($state['toast']['type'] ?? '') === 'info') {
                $state['redirect'] = '?page=list_major';
            }

            return $state;
        }

        try {
            $major = $this->model->findById($id);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tải dữ liệu ngành học. Vui lòng thử lại.'];
            $state['redirect'] = '?page=list_major';
            return $state;
        }

        if ($major === null) {
            $state['toast'] = ['type' => 'error', 'message' => 'Ngành học không tồn tại hoặc đã bị xóa.'];
            $state['redirect'] = '?page=list_major';
            return $state;
        }

        $state['formData'] = [
            'major_code' => $major['code'] ?? '',
            'major_name' => $major['name'] ?? '',
            'department' => (string) ($major['department_id'] ?? ''),
            'description' => $major['description'] ?? '',
            'status' => $major['status'] ?? '',
        ];

        return $state;
    }

    public function handle(string $page, array $post, array $get, string $method): array
    {
        $page = trim($page);

        if ($page === 'create_major') {
            return $this->create($post, $method) + ['page' => 'create_major'];
        }

        if ($page === 'list_major') {
            return $this->listing($post, $get, $method) + ['page' => 'list_major'];
        }

        if ($page === 'edit_major') {
            return $this->editState((int) ($get['id'] ?? 0), $post, $method) + ['page' => 'edit_major'];
        }

        return [
            'page' => $page,
            'formData' => [],
            'errors' => [],
            'departments' => [],
            'majors' => [],
            'pagination' => [],
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
            'redirect' => null,
            'isEdit' => false,
        ];
    }

    private function loadListState(array $state, int $requestedPage, array $filters = []): array
    {
        $keyword = $this->searchKeyword($filters['keyword'] ?? '');
        $status = trim((string) ($filters['status'] ?? ''));
        $hasFilters = $keyword !== '' || $status !== '';
        $totalItems = $hasFilters
            ? $this->model->countFiltered($keyword, $status)
            : $this->model->countAll();
        $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
        $currentPage = min(max(1, $requestedPage), $totalPages);
        $majors = $totalItems > 0
            ? ($hasFilters
                ? $this->model->listFilteredPaginated($currentPage, self::LIST_PER_PAGE, $keyword, $status)
                : $this->model->listPaginated($currentPage, self::LIST_PER_PAGE))
            : [];

        $state['majors'] = array_map(fn (array $major): array => $this->formatListRow($major), $majors);
        $state['emptyMessage'] = $hasFilters ? 'Không có ngành học phù hợp.' : 'Chưa có ngành học nào.';
        $state['pagination'] = [
            'current_page' => $currentPage,
            'total_items' => $totalItems,
            'items_per_page' => self::LIST_PER_PAGE,
            'total_pages' => $totalPages,
            'from' => $totalItems === 0 ? 0 : (($currentPage - 1) * self::LIST_PER_PAGE) + 1,
            'to' => min($totalItems, $currentPage * self::LIST_PER_PAGE),
        ];

        return $state;
    }

    private function majorHasNoChanges(array $major, array $form, int $departmentId): bool
    {
        return (int) ($major['department_id'] ?? 0) === $departmentId
            && trim((string) ($major['name'] ?? '')) === $form['major_name']
            && trim((string) ($major['code'] ?? '')) === $form['major_code']
            && trim((string) ($major['description'] ?? '')) === $form['description']
            && trim((string) ($major['status'] ?? '')) === $form['status'];
    }

    private function handleListAction(array $data): ?array
    {
        $action = trim((string) ($data['action'] ?? ''));

        if ($action === 'delete') {
            return $this->deleteFromList((int) ($data['id'] ?? 0));
        }

        if (!isset($data['status']) || !is_array($data['status'])) {
            return null;
        }

        $statusValues = array_column(self::STATUS_OPTIONS, 'value');

        foreach ($data['status'] as $id => $status) {
            $id = (int) $id;
            $status = trim((string) $status);

            if ($id < 1 || !in_array($status, $statusValues, true)) {
                return ['type' => 'error', 'message' => 'Vui lòng chọn trạng thái hợp lệ.'];
            }

            try {
                $major = $this->model->findById($id);
                if ($major === null) {
                    return ['type' => 'error', 'message' => 'Ngành học không tồn tại hoặc đã bị xóa.'];
                }

                if ((string) ($major['status'] ?? '') === $status) {
                    return ['type' => 'error', 'message' => 'Trạng thái mới trùng với trạng thái hiện tại.'];
                }

                if (!$this->model->updateStatus($id, $status)) {
                    return ['type' => 'error', 'message' => 'Cập nhật trạng thái ngành học thất bại. Vui lòng thử lại.'];
                }
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                return ['type' => 'error', 'message' => 'Cập nhật trạng thái ngành học thất bại. Vui lòng thử lại.'];
            }
        }

        return ['type' => 'success', 'message' => 'Cập nhật trạng thái ngành học thành công.'];
    }

    private function deleteFromList(int $id): array
    {
        if ($id < 1) {
            return ['type' => 'error', 'message' => 'Ngành học không hợp lệ.'];
        }

        try {
            if ($this->model->findById($id) === null) {
                return ['type' => 'error', 'message' => 'Ngành học không tồn tại hoặc đã bị xóa.'];
            }

            if ($this->model->hasRelatedData($id)) {
                return ['type' => 'error', 'message' => 'Không thể xóa ngành học vì đang có dữ liệu liên quan.'];
            }

            if (!$this->model->delete($id)) {
                return ['type' => 'error', 'message' => 'Xóa ngành học thất bại. Vui lòng thử lại.'];
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());

            if ($this->model->isConstraintException($exception)) {
                return ['type' => 'error', 'message' => 'Không thể xóa ngành học vì đang có dữ liệu liên quan.'];
            }

            return ['type' => 'error', 'message' => 'Xóa ngành học thất bại. Vui lòng thử lại.'];
        }

        return ['type' => 'success', 'message' => 'Xóa ngành học thành công.'];
    }

    private function formatListRow(array $major): array
    {
        $status = (string) ($major['status'] ?? '');

        return [
            'id' => (int) ($major['id'] ?? 0),
            'code' => $this->blank($major['code'] ?? null),
            'name' => $this->blank($major['name'] ?? null),
            'department' => $this->blank($major['department_code'] ?? null),
            'department_name' => $this->blank($major['department_name'] ?? null),
            'status' => $status,
            'status_label' => $this->statusLabel($status),
            'status_class' => $status === 'Hoạt động' ? 'active' : 'inactive',
        ];
    }

    private function statusLabel(string $status): string
    {
        foreach (self::STATUS_OPTIONS as $option) {
            if ($option['value'] === $status) {
                return $option['label'];
            }
        }

        return $status === '' ? '--' : $status;
    }

    private function blank(mixed $value): string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? '--' : $value;
    }

    private function formData(array $data): array
    {
        return [
            'major_code' => $this->normalizeCode((string) ($data['major_code'] ?? '')),
            'major_name' => preg_replace('/\s+/u', ' ', trim((string) ($data['major_name'] ?? ''))),
            'department' => trim((string) ($data['department'] ?? '')),
            'description' => preg_replace('/[ \t]+/u', ' ', trim((string) ($data['description'] ?? ''))),
            'status' => trim((string) ($data['status'] ?? '')),
        ];
    }

    private function validate(array $form): array
    {
        $errors = [];
        $statusValues = array_column(self::STATUS_OPTIONS, 'value');

        if ($form['major_code'] !== '') {
            if ($this->length($form['major_code']) > self::MAX_CODE_LENGTH) {
                $errors['major_code'] = 'Tên viết tắt không được vượt quá ' . self::MAX_CODE_LENGTH . ' ký tự.';
            } elseif (!preg_match('/^[A-Z0-9]+$/u', $form['major_code'])) {
                $errors['major_code'] = 'Tên viết tắt chỉ được gồm chữ cái không dấu và số.';
            }
        }

        if ($form['major_name'] === '') {
            $errors['major_name'] = 'Tên ngành không được để trống.';
        } elseif ($this->length($form['major_name']) > self::MAX_NAME_LENGTH) {
            $errors['major_name'] = 'Tên ngành không được vượt quá ' . self::MAX_NAME_LENGTH . ' ký tự.';
        } elseif (!preg_match('/^[\p{L}\p{M}\p{N}\s\/&().,\-]+$/u', $form['major_name'])) {
            $errors['major_name'] = 'Tên ngành chứa ký tự không hợp lệ.';
        }

        if ($form['department'] === '') {
            $errors['department'] = 'Vui lòng chọn khoa trực thuộc.';
        } elseif (!ctype_digit($form['department']) || (int) $form['department'] < 1) {
            $errors['department'] = 'Vui lòng chọn khoa trực thuộc hợp lệ.';
        }

        if ($form['status'] === '') {
            $errors['status'] = 'Vui lòng chọn trạng thái.';
        } elseif (!in_array($form['status'], $statusValues, true)) {
            $errors['status'] = 'Vui lòng chọn trạng thái hợp lệ.';
        }

        if ($form['description'] !== '' && $this->length($form['description']) > self::MAX_DESCRIPTION_LENGTH) {
            $errors['description'] = 'Mô tả không được vượt quá ' . self::MAX_DESCRIPTION_LENGTH . ' ký tự.';
        }

        return $errors;
    }

    private function duplicateState(array $state, array $form, int $departmentId, ?int $excludeId = null): array
    {
        try {
            $codeExists = $excludeId === null
                ? $this->model->existsByCode($form['major_code'])
                : $this->model->existsByCodeExcept($form['major_code'], $excludeId);
            if ($codeExists) {
                $state['errors']['major_code'] = 'Tên viết tắt đã tồn tại.';
                return $state;
            }

            $nameExists = $excludeId === null
                ? $this->model->existsByNameInDepartment($form['major_name'], $departmentId)
                : $this->model->existsByNameInDepartmentExcept($form['major_name'], $departmentId, $excludeId);
            if ($nameExists) {
                $state['errors']['major_name'] = 'Tên ngành đã tồn tại trong khoa/bộ môn này.';
                return $state;
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
        }

        $state['toast'] = ['type' => 'error', 'message' => 'Dữ liệu ngành học bị trùng. Vui lòng kiểm tra lại.'];
        return $state;
    }

    private function safeDepartments(): array
    {
        try {
            return $this->model->getDepartments();
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }

    private function normalizeCode(string $code): string
    {
        $code = preg_replace('/\s+/u', '', trim($code));

        return function_exists('mb_strtoupper') ? mb_strtoupper($code, 'UTF-8') : strtoupper($code);
    }

    private function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
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
}
