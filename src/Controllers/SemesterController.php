<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\SemesterModel;
use Throwable;

class SemesterController
{
    private const LIST_PER_PAGE = 10;
    private const MAX_SEMESTERS_PER_YEAR = 12;
    private const SEMESTER_LIMIT_MESSAGE = 'Niên khóa này đã có tối đa 12 học kỳ. Không thể tạo thêm.';
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
        if ($form['status'] === '') {
            $form['status'] = $this->calculatedStatusValue($form['start_date'], $form['end_date']);
        }

        $state['formData'] = $form;
        $state['errors'] = $this->validateForm($form, $state['status_options']);

        if (!empty($state['errors'])) {
            return $state;
        }

        if ($this->academicYearReachedSemesterLimit((int) $form['academic_year'])) {
            $state['errors']['academic_year'] = self::SEMESTER_LIMIT_MESSAGE;
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
        $this->syncStatusesByDate();

        $page = max(1, (int) ($query['page_num'] ?? 1));
        $keyword = trim($query['keyword'] ?? $query['q'] ?? $query['search'] ?? '');
        $statusOptions = $this->model->getStatusOptions();
        $status = trim((string) ($query['status'] ?? ''));
        if ($status !== '' && !in_array($status, array_column($statusOptions, 'value'), true)) {
            $status = '';
        }
        $academicYears = $this->model->getAcademicYears();
        $academicYearId = trim((string) ($query['academic_year_id'] ?? $query['academic_year'] ?? ''));
        $academicYearIds = array_map(
            static fn (array $year): string => (string) ($year['id'] ?? $year['MA_NIEN_KHOA'] ?? ''),
            $academicYears
        );
        $academicYearIds = array_values(array_filter($academicYearIds, static fn (string $id): bool => $id !== ''));
        if ($academicYearId !== '' && !in_array($academicYearId, $academicYearIds, true)) {
            $academicYearId = '';
        }
        $hasFilters = $keyword !== '' || $status !== '' || $academicYearId !== '';

        $state = [
            'semesters' => [],
            'filters' => ['keyword' => $keyword, 'status' => $status, 'academic_year_id' => $academicYearId],
            'status_options' => $statusOptions,
            'academic_years' => $academicYears,
            'emptyMessage' => $hasFilters ? 'Không có học kỳ phù hợp.' : 'Chưa có học kỳ nào.',
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

        $action = trim((string) ($data['action'] ?? ''));

        if ($method === 'POST' && $action === 'delete') {
            $id = (int) ($data['id'] ?? 0);
            if ($id > 0) {
                $deleteState = $this->delete($id);
                $state['toast'] = [
                    'type' => $deleteState['success'] ? 'success' : 'error',
                    'message' => $deleteState['message'],
                ];
            } else {
                $state['toast'] = ['type' => 'error', 'message' => 'ID học kỳ không hợp lệ.'];
            }
        }

        if ($method === 'POST' && $action === 'status') {
            $statusState = $this->handleStatusChange($data);
            $state['toast'] = $statusState === null ? null : [
                'type' => $statusState['success'] ? 'success' : 'error',
                'message' => $statusState['message'],
            ];
        }

        if ($method === 'POST' && $action === '' && !empty($data['status_change'])) {
            $id = (int) ($data['semester_id'] ?? 0);
            $newStatus = trim($data['new_status'] ?? '');
            $statusState = $this->changeStatus($id, $newStatus);
            $state['toast'] = [
                'type' => $statusState['success'] ? 'success' : 'error',
                'message' => $statusState['message'],
            ];
        }

        try {
            $totalItems = $hasFilters
                ? $this->model->countFiltered($keyword, $status, $academicYearId)
                : $this->model->countAll();
            $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
            $page = min($page, $totalPages);

            $state['semesters'] = $totalItems > 0
                ? ($hasFilters
                    ? $this->model->listFilteredPaginated($page, self::LIST_PER_PAGE, $keyword, $status, $academicYearId)
                    : $this->model->listPaginated($page, self::LIST_PER_PAGE))
                : [];

            $state['emptyMessage'] = $hasFilters ? 'Không có học kỳ phù hợp.' : 'Chưa có học kỳ nào.';
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
                'message' => !$hasFilters
                    ? 'Không thể tải danh sách học kỳ.'
                    : 'Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại.',
            ];
        }

        return $state;
    }

    private function handleEdit(int $id, array $data, string $method): array
    {
        $returnUrl = $this->safeListReturnUrl($data['return'] ?? $_GET['return'] ?? '');
        $state = [
            'formData' => [],
            'errors' => [],
            'academic_years' => $this->model->getAcademicYears(),
            'status_options' => $this->model->getStatusOptions(),
            'toast' => null,
            'redirect' => null,
            'returnUrl' => $returnUrl,
        ];

        if ($id === 0) {
            $state['toast'] = ['type' => 'error', 'message' => 'ID học kỳ không hợp lệ.'];
            $state['redirect'] = $returnUrl;
            return $state;
        }

        if ($method === 'POST') {
            $currentSemester = $this->model->findById($id);
            if (!$currentSemester) {
                $state['toast'] = ['type' => 'error', 'message' => 'Không tìm thấy học kỳ cần chỉnh sửa.'];
                $state['redirect'] = $returnUrl;
                return $state;
            }

            $form = [
                'academic_year' => trim($data['academic_year'] ?? ''),
                'semester_name' => trim($data['semester_name'] ?? ''),
                'start_date' => trim($data['start_date'] ?? ''),
                'end_date' => trim($data['end_date'] ?? ''),
                'status' => trim($data['status'] ?? ''),
            ];
            if ($form['status'] === '') {
                $form['status'] = $this->calculatedStatusValue($form['start_date'], $form['end_date']);
            }

            $state['formData'] = $form;
            $state['errors'] = $this->validateForm($form, $state['status_options']);

            if (empty($state['errors'])) {
                $academicYearChanged = (int) ($currentSemester['academic_year'] ?? 0) !== (int) $form['academic_year'];
                if ($academicYearChanged && $this->academicYearReachedSemesterLimit((int) $form['academic_year'])) {
                    $state['errors']['academic_year'] = self::SEMESTER_LIMIT_MESSAGE;
                    return $state;
                }

                try {
                    if (($currentSemester['start_date'] ?? '') !== $form['start_date']
                        || ($currentSemester['end_date'] ?? '') !== $form['end_date']) {
                        $form['status'] = $this->calculatedStatusValue($form['start_date'], $form['end_date']);
                    }

                    $updated = $this->model->update($id, $form);
                    if ($updated) {
                        $state['toast'] = ['type' => 'success', 'message' => 'Cập nhật học kỳ thành công.'];
                        $state['redirect'] = $returnUrl;
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
            $state['redirect'] = $returnUrl;
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

    private function safeListReturnUrl(mixed $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '?page=list_semester';
        }

        $parts = parse_url($value);
        if ($parts === false || isset($parts['scheme'], $parts['host'])) {
            return '?page=list_semester';
        }

        parse_str($parts['query'] ?? ltrim($value, '?'), $params);
        if (($params['page'] ?? '') !== 'list_semester') {
            return '?page=list_semester';
        }

        return '?' . http_build_query($params);
    }

    private function syncStatusesByDate(): void
    {
        foreach ($this->model->allForStatusSync() as $semester) {
            if ((string) ($semester['status'] ?? '') === 'Tạm khóa') {
                continue;
            }

            $status = $this->calculatedStatusValue(
                (string) ($semester['start_date'] ?? ''),
                (string) ($semester['end_date'] ?? '')
            );

            if ((string) ($semester['status'] ?? '') !== $status) {
                $this->model->updateStatus((int) ($semester['id'] ?? 0), $status);
            }
        }
    }

    private function calculatedStatusValue(string $startDate, string $endDate): string
    {
        $today = new \DateTimeImmutable('today');
        $start = $this->parseDate($startDate);
        $end = $this->parseDate($endDate);

        if ($start !== false && $today < $start) {
            return 'Sắp diễn ra';
        }

        if ($start !== false && $end !== false && $today >= $start && $today <= $end) {
            return 'Đang diễn ra';
        }

        if ($end !== false && $today > $end) {
            return 'Đã hoàn thành';
        }

        return 'Sắp diễn ra';
    }

    private function parseDate(string $value): \DateTimeImmutable|false
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date !== false) {
            return $date;
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (Throwable) {
            return false;
        }
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

    private function academicYearReachedSemesterLimit(int $academicYearId): bool
    {
        return $this->model->countByAcademicYear($academicYearId) >= self::MAX_SEMESTERS_PER_YEAR;
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
        if ($id < 1) {
            return ['success' => false, 'message' => 'Cập nhật trạng thái học kỳ thất bại.'];
        }

        $semester = $this->model->findById($id);
        if (!$semester) {
            return ['success' => false, 'message' => 'Không tìm thấy học kỳ.'];
        }

        $validStatuses = array_column($this->model->getStatusOptions(), 'value');
        if (!in_array($status, $validStatuses, true)) {
            return ['success' => false, 'message' => 'Trạng thái không hợp lệ.'];
        }

        try {
            if ((string) ($semester['status'] ?? '') === $status) {
                return ['success' => true, 'message' => 'Cập nhật trạng thái học kỳ thành công.'];
            }

            $updated = $this->model->updateStatus($id, $status);

            return [
                'success' => $updated,
                'message' => $updated
                    ? 'Cập nhật trạng thái học kỳ thành công.'
                    : 'Cập nhật trạng thái học kỳ thất bại.',
            ];
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Cập nhật trạng thái học kỳ thất bại.'];
        }
    }

    private function handleStatusChange(array $data): ?array
    {
        if (!isset($data['status']) || !is_array($data['status']) || count($data['status']) !== 1) {
            return ['success' => false, 'message' => 'Cập nhật trạng thái học kỳ thất bại.'];
        }

        if (!isset($data['_row_id']) || !ctype_digit((string) $data['_row_id'])) {
            return ['success' => false, 'message' => 'Cập nhật trạng thái học kỳ thất bại.'];
        }

        $id = (int) $data['_row_id'];
        if (!array_key_exists((string) $id, $data['status'])) {
            return ['success' => false, 'message' => 'Cập nhật trạng thái học kỳ thất bại.'];
        }

        return $this->changeStatus($id, trim((string) $data['status'][$id]));
    }
}
