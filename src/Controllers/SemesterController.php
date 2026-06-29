<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\SemesterModel;
use Throwable;

class SemesterController
{
    private const LIST_PER_PAGE = 10;
    private SemesterModel $model;

    public function __construct(?SemesterModel $model = null)
    {
        $this->model = $model ?? new SemesterModel(Database::getConnection());
    }

    public function handle(string $page, array $data, array $query, string $method): array
    {
        return match ($page) {
            'create_semester' => $this->handleCreate($data, $method),
            'list_semester' => $this->handleList($data, $query, $method),
            'edit_semester' => $this->handleEdit((int) ($query['id'] ?? 0), $data, $method),
            default => [],
        };
    }

    private function handleCreate(array $data, string $method): array
    {
        $state = [
            'formData' => $method === 'POST' ? $data : [],
            'errors' => [],
            'academic_years' => $this->model->getAcademicYears(),
            'status_options' => $this->model->getStatusOptions(),
            'toast' => null,
        ];

        if ($method !== 'POST') {
            return $state;
        }

        $form = [
            'academic_year' => trim($data['academic_year'] ?? ''),
            'semester_name' => trim($data['semester_name'] ?? ''),
            'start_date' => trim($data['start_date'] ?? ''),
            'end_date' => trim($data['end_date'] ?? ''),
            'status' => trim($data['status'] ?? ''),
        ];

        $state['formData'] = $form;
        $state['errors'] = $this->validateForm($form, $state['status_options']);

        if (!empty($state['errors'])) {
            return $state;
        }

        try {
            $created = $this->model->create($form);

            if ($created) {
                $state['toast'] = [
                    'type' => 'success',
                    'message' => 'Tạo học kỳ thành công.',
                ];
                $state['formData'] = [];
            } else {
                $state['toast'] = [
                    'type' => 'error',
                    'message' => 'Tạo học kỳ thất bại. Vui lòng thử lại.',
                ];
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $state['toast'] = [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi tạo học kỳ. Vui lòng thử lại.',
            ];
        }

        return $state;
    }

    private function handleList(array $data, array $query, string $method): array
    {
        $page = max(1, (int) ($query['page_num'] ?? 1));
        $keyword = trim($query['keyword'] ?? $query['q'] ?? $query['search'] ?? '');

        $state = [
            'semesters' => [],
            'filters' => ['keyword' => $keyword],
            'emptyMessage' => $keyword === '' ? 'Chưa có học kỳ nào.' : 'Không tìm thấy học kỳ phù hợp.',
            'pagination' => [
                'current_page' => $page,
                'total_items' => 0,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => 1,
                'from' => 0,
                'to' => 0,
            ],
            'toast' => null,
            'redirect' => null,
        ];

        // Xử lý DELETE
        if ($method === 'POST' && !empty($data['action']) && $data['action'] === 'delete') {
            $id = (int) ($data['id'] ?? 0);
            if ($id > 0) {
                $deleteState = $this->delete($id);
                $state['toast'] = [
                    'type' => $deleteState['success'] ? 'success' : 'error',
                    'message' => $deleteState['message'],
                ];
            }
        }

        // Xử lý thay đổi trạng thái
        if ($method === 'POST' && !empty($data['status_change'])) {
            $id = (int) ($data['semester_id'] ?? 0);
            $newStatus = trim($data['new_status'] ?? '');
            if ($id > 0 && $newStatus !== '') {
                $statusState = $this->changeStatus($id, $newStatus);
                $state['toast'] = [
                    'type' => $statusState['success'] ? 'success' : 'error',
                    'message' => $statusState['message'],
                ];
            }
        }

        try {
            $totalItems = $this->model->countFiltered($keyword);
            $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
            $page = min($page, $totalPages);

            $state['semesters'] = $totalItems > 0
                ? ($keyword === ''
                    ? $this->model->listPaginated($page, self::LIST_PER_PAGE)
                    : $this->model->listFilteredPaginated($page, self::LIST_PER_PAGE, $keyword))
                : [];

            $state['emptyMessage'] = $keyword === '' ? 'Chưa có học kỳ nào.' : 'Không tìm thấy học kỳ phù hợp.';
            $state['pagination'] = [
                'current_page' => $page,
                'total_items' => $totalItems,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => $totalPages,
                'from' => $totalItems === 0 ? 0 : (($page - 1) * self::LIST_PER_PAGE) + 1,
                'to' => min($totalItems, $page * self::LIST_PER_PAGE),
            ];
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $state['toast'] = [
                'type' => 'error',
                'message' => $keyword === ''
                    ? 'Không thể tải danh sách học kỳ.'
                    : 'Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại.',
            ];
        }

        return $state;
    }

    private function handleEdit(int $id, array $data, string $method): array
    {
        $state = [
            'formData' => [],
            'errors' => [],
            'academic_years' => $this->model->getAcademicYears(),
            'status_options' => $this->model->getStatusOptions(),
            'toast' => null,
            'redirect' => null,
        ];

        if ($id === 0) {
            $state['toast'] = ['type' => 'error', 'message' => 'ID học kỳ không hợp lệ.'];
            $state['redirect'] = '?page=list_semester';
            return $state;
        }

        if ($method === 'POST') {
            $form = [
                'academic_year' => trim($data['academic_year'] ?? ''),
                'semester_name' => trim($data['semester_name'] ?? ''),
                'start_date' => trim($data['start_date'] ?? ''),
                'end_date' => trim($data['end_date'] ?? ''),
                'status' => trim($data['status'] ?? ''),
            ];

            $state['formData'] = $form;
            $state['errors'] = $this->validateForm($form, $state['status_options']);

            if (empty($state['errors'])) {
                try {
                    $updated = $this->model->update($id, $form);
                    if ($updated) {
                        $state['toast'] = ['type' => 'success', 'message' => 'Cập nhật học kỳ thành công.'];
                        $state['redirect'] = '?page=list_semester';
                    } else {
                        $state['toast'] = ['type' => 'info', 'message' => 'Không có thay đổi nào được thực hiện.'];
                    }
                } catch (Throwable $e) {
                    error_log($e->getMessage());
                    $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra khi cập nhật học kỳ. Vui lòng thử lại.'];
                }
            }

            return $state;
        }

        $semester = $this->model->findById($id);
        if (!$semester) {
            $state['toast'] = ['type' => 'error', 'message' => 'Không tìm thấy học kỳ cần chỉnh sửa.'];
            $state['redirect'] = '?page=list_semester';
            return $state;
        }

        $state['formData'] = [
            'academic_year' => $semester['academic_year'],
            'semester_name' => $semester['name'],
            'start_date' => $semester['start_date'],
            'end_date' => $semester['end_date'],
            'status' => $semester['status'],
        ];

        return $state;
    }

    private function validateForm(array $form, array $statusOptions): array
    {
        $errors = [];

        if ($form['academic_year'] === '') {
            $errors['academic_year'] = 'Vui lòng chọn niên khóa.';
        } elseif (!is_numeric($form['academic_year']) || (int) $form['academic_year'] <= 0) {
            $errors['academic_year'] = 'Niên khóa không hợp lệ.';
        }

        if ($form['semester_name'] === '') {
            $errors['semester_name'] = 'Vui lòng nhập tên học kỳ.';
        } elseif (strlen($form['semester_name']) > 100) {
            $errors['semester_name'] = 'Tên học kỳ không được vượt quá 100 ký tự.';
        }

        if ($form['start_date'] === '') {
            $errors['start_date'] = 'Vui lòng chọn ngày bắt đầu.';
        } elseif (!$this->isValidDate($form['start_date'])) {
            $errors['start_date'] = 'Ngày bắt đầu không hợp lệ.';
        }

        if ($form['end_date'] === '') {
            $errors['end_date'] = 'Vui lòng chọn ngày kết thúc.';
        } elseif (!$this->isValidDate($form['end_date'])) {
            $errors['end_date'] = 'Ngày kết thúc không hợp lệ.';
        }

        if ($form['start_date'] !== '' && $form['end_date'] !== '' && $form['start_date'] >= $form['end_date']) {
            $errors['end_date'] = 'Ngày kết thúc phải sau ngày bắt đầu.';
        }

        if ($form['status'] === '') {
            $errors['status'] = 'Vui lòng chọn trạng thái.';
        } else {
            $validStatuses = array_column($statusOptions, 'value');
            if (!in_array($form['status'], $validStatuses, true)) {
                $errors['status'] = 'Trạng thái không hợp lệ.';
            }
        }

        return $errors;
    }

    private function isValidDate(string $date): bool
    {
        if (empty($date)) {
            return false;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return (bool) strtotime($date);
        }

        return false;
    }

    private function delete(int $id): array
    {
        if (!$this->model->findById($id)) {
            return ['success' => false, 'message' => 'Không tìm thấy học kỳ cần xóa.'];
        }

        try {
            if ($this->model->hasRelatedData($id)) {
                return ['success' => false, 'message' => 'Không thể xóa vì dữ liệu này đang được sử dụng.'];
            }

            $deleted = $this->model->deleteById($id);

            return [
                'success' => $deleted,
                'message' => $deleted
                    ? 'Xóa học kỳ thành công.'
                    : 'Xóa học kỳ thất bại. Vui lòng thử lại.',
            ];
        } catch (Throwable $e) {
            error_log($e->getMessage());

            if ($this->model->isConstraintException($e)) {
                return ['success' => false, 'message' => 'Không thể xóa vì dữ liệu này đang được sử dụng.'];
            }

            return ['success' => false, 'message' => 'Có lỗi xảy ra khi xóa học kỳ. Vui lòng thử lại.'];
        }
    }

    private function changeStatus(int $id, string $status): array
    {
        $semester = $this->model->findById($id);
        if (!$semester) {
            return ['success' => false, 'message' => 'Không tìm thấy học kỳ.'];
        }

        $validStatuses = array_column($this->model->getStatusOptions(), 'value');
        if (!in_array($status, $validStatuses, true)) {
            return ['success' => false, 'message' => 'Trạng thái không hợp lệ.'];
        }

        try {
            $updated = $this->model->updateStatus($id, $status);

            return [
                'success' => $updated,
                'message' => $updated
                    ? 'Cập nhật trạng thái thành công.'
                    : 'Cập nhật trạng thái thất bại. Vui lòng thử lại.',
            ];
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái. Vui lòng thử lại.'];
        }
    }
}
