<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\ClassModel;
use Throwable;

class ClassController
{
    private const LIST_PER_PAGE = 10;
    private const MAX_CODE_LENGTH = 50;
    private const MAX_NAME_LENGTH = 100;
    private const MAX_CAPACITY = 200;

    private const STATUS_OPTIONS = [
        ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
        ['value' => 'Không hoạt động', 'label' => 'Không hoạt động'],
        ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
    ];

    public function __construct(private ?ClassModel $model = null)
    {
        $this->model = $this->model ?? new ClassModel(Database::getConnection());
    }

    public function create(array $data, string $method): array
    {
        $state = [
            'formData' => $method === 'POST' ? $this->formData($data) : [],
            'errors' => [],
            'academic_years' => $this->safeAcademicYears(),
            'departments' => $this->safeDepartments(),
            'majors' => $this->safeMajors(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
            'redirect' => null,
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

        $academicYearId = (int) $form['academic_year'];
        $departmentId = (int) $form['department'];
        $majorId = (int) $form['major'];

        try {
            if (!$this->model->academicYearExists($academicYearId)) {
                $state['errors']['academic_year'] = 'Vui lòng chọn niên khóa.';
                return $state;
            }

            if (!$this->model->departmentExists($departmentId)) {
                $state['errors']['department'] = 'Vui lòng chọn khoa.';
                return $state;
            }

            if (!$this->model->majorBelongsToDepartment($majorId, $departmentId)) {
                $state['errors']['major'] = 'Vui lòng chọn chuyên ngành.';
                return $state;
            }

            if ($this->model->codeExists($form['class_code'])) {
                $state['errors']['class_code'] = 'Mã lớp học đã tồn tại.';
                return $state;
            }

            $created = $this->model->create([
                'department_id' => $departmentId,
                'major_id' => $majorId,
                'academic_year_id' => $academicYearId,
                'name' => $form['class_name'],
                'code' => $form['class_code'],
                'capacity' => (int) $form['capacity'],
                'status' => $form['status'],
                'notes' => $form['notes'],
            ]);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());

            if ($this->model->isDuplicateException($exception)) {
                $state['errors']['class_code'] = 'Mã lớp học đã tồn tại.';
                return $state;
            }

            if ($this->model->isConstraintException($exception)) {
                $state['toast'] = ['type' => 'error', 'message' => 'Dữ liệu khoa, chuyên ngành hoặc niên khóa không hợp lệ.'];
                return $state;
            }

            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tạo lớp học. Vui lòng thử lại.'];
            return $state;
        }

        if ($created) {
            $state['toast'] = ['type' => 'success', 'message' => 'Tạo lớp học thành công.'];
            $state['formData'] = [];
            $state['redirect'] = '?page=list_class';
            return $state;
        }

        $state['toast'] = ['type' => 'error', 'message' => 'Tạo lớp học thất bại. Vui lòng thử lại.'];
        return $state;
    }

    public function listing(array $data, array $query, string $method): array
    {
        $page = max(1, (int) ($query['page_num'] ?? 1));
        $filters = [
            'keyword' => $this->searchKeyword($query['keyword'] ?? $query['q'] ?? $query['search'] ?? ''),
            'academic_year' => $this->positiveIdFilter($query['academic_year'] ?? ''),
            'status' => $this->statusFilter($query['status'] ?? ''),
        ];
        $state = [
            'classes' => [],
            'filters' => $filters,
            'academic_years' => $this->safeAcademicYears(),
            'statusOptions' => self::STATUS_OPTIONS,
            'pagination' => [
                'current_page' => $page,
                'total_items' => 0,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => 1,
                'from' => 0,
                'to' => 0,
            ],
            'emptyMessage' => 'Chưa có lớp học nào.',
            'toast' => null,
        ];

        if ($method === 'POST') {
            $state['toast'] = $this->handleListAction($data);
        }

        try {
            $hasFilters = $this->hasListFilters($filters);
            $totalItems = $hasFilters
                ? $this->model->countFiltered($filters['keyword'], $filters)
                : $this->model->countAll();
            $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
            $currentPage = min(max(1, $page), $totalPages);
            $rows = $totalItems > 0
                ? ($hasFilters
                    ? $this->model->listFilteredPaginated($currentPage, self::LIST_PER_PAGE, $filters['keyword'], $filters)
                    : $this->model->listPaginated($currentPage, self::LIST_PER_PAGE))
                : [];

            $state['classes'] = array_map(fn (array $class): array => $this->formatListRow($class), $rows);
            $state['emptyMessage'] = $hasFilters ? 'Không có lớp học phù hợp.' : 'Chưa có lớp học nào.';
            $state['pagination'] = [
                'current_page' => $currentPage,
                'total_items' => $totalItems,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => $totalPages,
                'from' => $totalItems === 0 ? 0 : (($currentPage - 1) * self::LIST_PER_PAGE) + 1,
                'to' => min($totalItems, $currentPage * self::LIST_PER_PAGE),
            ];

            return $state;
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] ??= ['type' => 'error', 'message' => 'Không thể tải danh sách lớp học.'];

            return $state;
        }
    }

    public function editState(int $id, array $data, string $method): array
    {
        $state = [
            'formData' => [],
            'errors' => [],
            'academic_years' => $this->safeAcademicYears(),
            'departments' => $this->safeDepartments(),
            'majors' => $this->safeMajors(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
            'redirect' => null,
        ];

        if ($id < 1) {
            $state['toast'] = ['type' => 'error', 'message' => 'Lớp học không hợp lệ.'];
            $state['redirect'] = '?page=list_class';
            return $state;
        }

        if ($method === 'POST') {
            $form = $this->formData($data);
            $state['formData'] = $form;

            try {
                if ($this->model->findById($id) === null) {
                    $state['toast'] = ['type' => 'error', 'message' => 'Lớp học không tồn tại hoặc đã bị xóa.'];
                    $state['redirect'] = '?page=list_class';
                    return $state;
                }
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                $state['toast'] = ['type' => 'error', 'message' => 'Không thể kiểm tra dữ liệu lớp học. Vui lòng thử lại.'];
                return $state;
            }

            $state['errors'] = $this->validate($form);
            if (!empty($state['errors'])) {
                return $state;
            }

            $academicYearId = (int) $form['academic_year'];
            $departmentId = (int) $form['department'];
            $majorId = (int) $form['major'];

            try {
                if (!$this->model->academicYearExists($academicYearId)) {
                    $state['errors']['academic_year'] = 'Vui lòng chọn niên khóa.';
                    return $state;
                }

                if (!$this->model->departmentExists($departmentId)) {
                    $state['errors']['department'] = 'Vui lòng chọn khoa.';
                    return $state;
                }

                if (!$this->model->majorBelongsToDepartment($majorId, $departmentId)) {
                    $state['errors']['major'] = 'Vui lòng chọn chuyên ngành.';
                    return $state;
                }

                if ($this->model->countStudents($id) > (int) $form['capacity']) {
                    $state['errors']['capacity'] = 'Sĩ số không được nhỏ hơn số sinh viên hiện có trong lớp.';
                    return $state;
                }

                if ($this->model->codeExistsExcept($form['class_code'], $id)) {
                    $state['errors']['class_code'] = 'Mã lớp học đã tồn tại.';
                    return $state;
                }

                $updated = $this->model->update($id, [
                    'department_id' => $departmentId,
                    'major_id' => $majorId,
                    'academic_year_id' => $academicYearId,
                    'name' => $form['class_name'],
                    'code' => $form['class_code'],
                    'capacity' => (int) $form['capacity'],
                    'status' => $form['status'],
                    'notes' => $form['notes'],
                ]);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());

                if ($this->model->isDuplicateException($exception)) {
                    $state['errors']['class_code'] = 'Mã lớp học đã tồn tại.';
                    return $state;
                }

                if ($this->model->isConstraintException($exception)) {
                    $state['toast'] = ['type' => 'error', 'message' => 'Dữ liệu khoa, chuyên ngành hoặc niên khóa không hợp lệ.'];
                    return $state;
                }

                $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi cập nhật lớp học. Vui lòng thử lại.'];
                return $state;
            }

            if ($updated) {
                $state['toast'] = ['type' => 'success', 'message' => 'Cập nhật lớp học thành công.'];
                $state['redirect'] = '?page=list_class';
                return $state;
            }

            $state['toast'] = ['type' => 'error', 'message' => 'Cập nhật lớp học thất bại. Vui lòng thử lại.'];
            return $state;
        }

        try {
            $class = $this->model->findById($id);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tải dữ liệu lớp học. Vui lòng thử lại.'];
            $state['redirect'] = '?page=list_class';
            return $state;
        }

        if ($class === null) {
            $state['toast'] = ['type' => 'error', 'message' => 'Lớp học không tồn tại hoặc đã bị xóa.'];
            $state['redirect'] = '?page=list_class';
            return $state;
        }

        $state['formData'] = [
            'class_code' => $class['code'] ?? '',
            'class_name' => $class['name'] ?? '',
            'academic_year' => (string) ($class['academic_year_id'] ?? ''),
            'department' => (string) ($class['department_id'] ?? ''),
            'major' => (string) ($class['major_id'] ?? ''),
            'capacity' => (string) ($class['capacity'] ?? ''),
            'status' => $class['status'] ?? '',
            'notes' => $class['notes'] ?? '',
        ];

        return $state;
    }

    private function deleteFromList(int $id): array
    {
        if ($id < 1) {
            return ['type' => 'error', 'message' => 'Lớp học không hợp lệ.'];
        }

        try {
            if ($this->model->findById($id) === null) {
                return ['type' => 'error', 'message' => 'Lớp học không tồn tại hoặc đã bị xóa.'];
            }

            if ($this->model->hasRelatedData($id)) {
                return ['type' => 'error', 'message' => 'Không thể xóa lớp học vì đang có dữ liệu sinh viên liên quan.'];
            }

            if (!$this->model->delete($id)) {
                return ['type' => 'error', 'message' => 'Xóa lớp học thất bại. Vui lòng thử lại.'];
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());

            if ($this->model->isConstraintException($exception)) {
                return ['type' => 'error', 'message' => 'Không thể xóa lớp học vì đang có dữ liệu liên quan.'];
            }

            return ['type' => 'error', 'message' => 'Xóa lớp học thất bại. Vui lòng thử lại.'];
        }

        return ['type' => 'success', 'message' => 'Xóa lớp học thành công.'];
    }

    private function handleListAction(array $data): ?array
    {
        $action = trim((string) ($data['action'] ?? ''));

        if ($action === 'delete') {
            return $this->deleteFromList((int) ($data['id'] ?? 0));
        }

        if ($action !== 'status') {
            return null;
        }

        if (!isset($data['status']) || !is_array($data['status']) || count($data['status']) !== 1) {
            return ['type' => 'error', 'message' => 'Yêu cầu cập nhật trạng thái không hợp lệ.'];
        }

        if (!isset($data['_row_id']) || !ctype_digit((string) $data['_row_id'])) {
            return ['type' => 'error', 'message' => 'Lớp học không hợp lệ.'];
        }

        if (!array_key_exists((string) $data['_row_id'], $data['status'])) {
            return ['type' => 'error', 'message' => 'Yêu cầu cập nhật trạng thái không hợp lệ.'];
        }

        if (empty($data['status'])) {
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
                $class = $this->model->findById($id);
                if ($class === null) {
                    return ['type' => 'error', 'message' => 'Lớp học không tồn tại hoặc đã bị xóa.'];
                }

                if ((string) ($class['status'] ?? '') === $status) {
                    return null;
                }

                if (!$this->model->updateStatus($id, $status)) {
                    return ['type' => 'error', 'message' => 'Cập nhật trạng thái lớp học thất bại. Vui lòng thử lại.'];
                }
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                return ['type' => 'error', 'message' => 'Cập nhật trạng thái lớp học thất bại. Vui lòng thử lại.'];
            }
        }

        return ['type' => 'success', 'message' => 'Cập nhật trạng thái lớp học thành công.'];
    }

    private function formatListRow(array $class): array
    {
        return [
            'id' => (int) ($class['id'] ?? 0),
            'code' => $this->blank($class['code'] ?? null),
            'name' => $this->blank($class['name'] ?? null),
            'department' => $this->blank($class['department'] ?? null),
            'academic_year' => $this->blank($class['academic_year'] ?? null),
            'major' => $this->blank($class['major'] ?? null),
            'capacity' => $this->blank($class['capacity'] ?? null),
            'status' => $this->blank($class['status'] ?? null),
            'status_class' => $this->statusClass((string) ($class['status'] ?? '')),
        ];
    }

    private function statusClass(string $status): string
    {
        $normalized = function_exists('mb_strtolower') ? mb_strtolower(trim($status), 'UTF-8') : strtolower(trim($status));

        return match ($normalized) {
            'hoạt động' => 'active',
            'không hoạt động' => 'inactive',
            'ngừng tuyển sinh' => 'stopped',
            default => 'unknown',
        };
    }

    private function blank(mixed $value): string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? '--' : $value;
    }

    private function hasListFilters(array $filters): bool
    {
        foreach (['keyword', 'academic_year', 'status'] as $key) {
            if (trim((string) ($filters[$key] ?? '')) !== '') {
                return true;
            }
        }

        return false;
    }

    private function positiveIdFilter(mixed $value): string
    {
        $value = trim((string) $value);

        return $value !== '' && ctype_digit($value) && (int) $value > 0 ? $value : '';
    }

    private function statusFilter(mixed $value): string
    {
        $value = trim((string) $value);
        $statusValues = array_column(self::STATUS_OPTIONS, 'value');

        return in_array($value, $statusValues, true) ? $value : '';
    }

    private function formData(array $data): array
    {
        return [
            'class_code' => trim((string) ($data['class_code'] ?? '')),
            'class_name' => trim((string) ($data['class_name'] ?? '')),
            'academic_year' => trim((string) ($data['academic_year'] ?? '')),
            'department' => trim((string) ($data['department'] ?? '')),
            'major' => trim((string) ($data['major'] ?? '')),
            'capacity' => trim((string) ($data['capacity'] ?? '')),
            'status' => trim((string) ($data['status'] ?? '')),
            'notes' => trim((string) ($data['notes'] ?? '')),
        ];
    }

    private function validate(array $form): array
    {
        $errors = [];
        $statusValues = array_column(self::STATUS_OPTIONS, 'value');

        if ($form['class_code'] === '') {
            $errors['class_code'] = 'Mã lớp học không được để trống.';
        } elseif ($this->length($form['class_code']) > self::MAX_CODE_LENGTH) {
            $errors['class_code'] = 'Mã lớp học không được vượt quá 50 ký tự.';
        }

        if ($form['class_name'] === '') {
            $errors['class_name'] = 'Tên lớp không được để trống.';
        } elseif ($this->length($form['class_name']) > self::MAX_NAME_LENGTH) {
            $errors['class_name'] = 'Tên lớp không được vượt quá 100 ký tự.';
        }

        if ($form['academic_year'] === '' || !ctype_digit($form['academic_year']) || (int) $form['academic_year'] < 1) {
            $errors['academic_year'] = 'Vui lòng chọn niên khóa.';
        }

        if ($form['department'] === '' || !ctype_digit($form['department']) || (int) $form['department'] < 1) {
            $errors['department'] = 'Vui lòng chọn khoa.';
        }

        if ($form['major'] === '' || !ctype_digit($form['major']) || (int) $form['major'] < 1) {
            $errors['major'] = 'Vui lòng chọn chuyên ngành.';
        }

        if ($form['capacity'] === '' || !ctype_digit($form['capacity'])) {
            $errors['capacity'] = 'Sĩ số không hợp lệ.';
        } else {
            $capacity = (int) $form['capacity'];
            if ($capacity < 1 || $capacity > self::MAX_CAPACITY) {
                $errors['capacity'] = 'Sĩ số không hợp lệ.';
            }
        }

        if ($form['status'] === '' || !in_array($form['status'], $statusValues, true)) {
            $errors['status'] = 'Vui lòng chọn trạng thái.';
        }

        return $errors;
    }

    private function safeAcademicYears(): array
    {
        try {
            return $this->model->getAcademicYears();
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [];
        }
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

    private function safeMajors(): array
    {
        try {
            return $this->model->getMajors();
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [];
        }
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
