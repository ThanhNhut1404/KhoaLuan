<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\MajorModel;
use Throwable;

class MajorController
{
    private const LIST_PER_PAGE = 6;

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
            'formData' => $method === 'POST' ? $data : [],
            'errors' => [],
            'departments' => $this->model->getDepartments(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
        ];

        if ($method !== 'POST') {
            return $state;
        }

        $form = [
            'major_code' => trim($data['major_code'] ?? ''),
            'major_name' => trim($data['major_name'] ?? ''),
            'department' => trim($data['department'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'status' => trim($data['status'] ?? ''),
        ];

        $state['formData'] = $form;
        $state['errors'] = $this->validate($form);

        if (!empty($state['errors'])) {
            return $state;
        }

        $departmentId = (int) $form['department'];

        if (!$this->model->departmentExists($departmentId)) {
            $state['errors']['department'] = 'Khoa trực thuộc không hợp lệ.';
            return $state;
        }

        if ($this->model->existsByNameInDepartment($form['major_name'], $departmentId)) {
            $state['errors']['major_name'] = 'Ngành học này đã tồn tại trong khoa đã chọn.';
            return $state;
        }

        try {
            $created = $this->model->create([
                'department_id' => $departmentId,
                'name' => $form['major_name'],
                'short_name' => $form['major_code'],
                'description' => $form['description'],
                'status' => $form['status'],
            ]);
        } catch (Throwable $exception) {
            if ($this->model->isDuplicateException($exception)) {
                $state['errors']['major_name'] = 'Ngành học này đã tồn tại trong khoa đã chọn.';
                return $state;
            }

            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tạo ngành học.'];
            return $state;
        }

        $state['toast'] = [
            'type' => $created ? 'success' : 'error',
            'message' => $created ? 'Tạo ngành học thành công.' : 'Tạo ngành học thất bại.',
        ];

        if ($created) {
            $state['formData'] = [];
        }

        return $state;
    }

    public function listing(array $data, array $query, string $method): array
    {
        $page = max(1, (int) ($query['page_num'] ?? 1));
        $state = [
            'majors' => [],
            'statusOptions' => self::STATUS_OPTIONS,
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

        return $this->loadListState($state, $page);
    }

    public function editState(int $id, array $data, string $method): array
    {
        $state = [
            'formData' => [],
            'errors' => [],
            'departments' => $this->model->getDepartments(),
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
            $form = [
                'major_code' => trim($data['major_code'] ?? ''),
                'major_name' => trim($data['major_name'] ?? ''),
                'department' => trim($data['department'] ?? ''),
                'description' => trim($data['description'] ?? ''),
                'status' => trim($data['status'] ?? ''),
            ];

            $state['formData'] = $form;
            $state['errors'] = $this->validate($form);

            if (!empty($state['errors'])) {
                return $state;
            }

            $departmentId = (int) $form['department'];

            if (!$this->model->departmentExists($departmentId)) {
                $state['errors']['department'] = 'Khoa trực thuộc không hợp lệ.';
                return $state;
            }

            if ($this->model->existsByNameInDepartmentExcept($form['major_name'], $departmentId, $id)) {
                $state['errors']['major_name'] = 'Ngành học này đã tồn tại trong khoa đã chọn.';
                return $state;
            }

            try {
                $updated = $this->model->update($id, [
                    'department_id' => $departmentId,
                    'name' => $form['major_name'],
                    'short_name' => $form['major_code'],
                    'description' => $form['description'],
                    'status' => $form['status'],
                ]);
            } catch (Throwable $exception) {
                if ($this->model->isDuplicateException($exception)) {
                    $state['errors']['major_name'] = 'Ngành học này đã tồn tại trong khoa đã chọn.';
                    return $state;
                }

                $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi cập nhật ngành học.'];
                return $state;
            }

            $state['toast'] = [
                'type' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Cập nhật ngành học thành công.' : 'Cập nhật ngành học thất bại.',
            ];

            if ($updated) {
                $state['redirect'] = '?page=list_major';
            }

            return $state;
        }

        $major = $this->model->findById($id);
        if ($major === null) {
            $state['toast'] = ['type' => 'error', 'message' => 'Không tìm thấy ngành học.'];
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
        if (trim($page) === 'create_major') {
            return $this->create($post, $method) + ['page' => 'create_major'];
        }

        if (trim($page) === 'list_major') {
            return $this->listing($post, $get, $method) + ['page' => 'list_major'];
        }

        if (trim($page) === 'edit_major') {
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

    private function loadListState(array $state, int $requestedPage): array
    {
        $totalItems = $this->model->countAll();
        $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
        $currentPage = min(max(1, $requestedPage), $totalPages);
        $majors = $totalItems > 0 ? $this->model->listPaginated($currentPage, self::LIST_PER_PAGE) : [];

        $state['majors'] = array_map(fn (array $major): array => $this->formatListRow($major), $majors);
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

    private function handleListAction(array $data): ?array
    {
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

            $this->model->updateStatus($id, $status);
        }

        return ['type' => 'success', 'message' => 'Cập nhật trạng thái ngành học thành công.'];
    }

    private function formatListRow(array $major): array
    {
        $status = (string) ($major['status'] ?? '');

        return [
            'id' => (int) ($major['id'] ?? 0),
            'code' => $this->blank($major['code'] ?? null),
            'name' => $this->blank($major['name'] ?? null),
            'department' => $this->blank($major['department_id'] ?? null),
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

    private function validate(array $form): array
    {
        $errors = [];
        $statusValues = array_column(self::STATUS_OPTIONS, 'value');

        if ($form['major_name'] === '') {
            $errors['major_name'] = 'Tên ngành không được để trống.';
        } elseif ($this->length($form['major_name']) > 50) {
            $errors['major_name'] = 'Tên ngành không được vượt quá 50 ký tự.';
        }

        if ($form['major_code'] !== '' && $this->length($form['major_code']) > 20) {
            $errors['major_code'] = 'Tên viết tắt không được vượt quá 20 ký tự.';
        }

        if ($form['department'] === '') {
            $errors['department'] = 'Vui lòng chọn khoa trực thuộc.';
        } elseif (!ctype_digit($form['department']) || (int) $form['department'] < 1) {
            $errors['department'] = 'Vui lòng chọn khoa trực thuộc.';
        }

        if ($form['status'] === '') {
            $errors['status'] = 'Vui lòng chọn trạng thái.';
        } elseif (!in_array($form['status'], $statusValues, true)) {
            $errors['status'] = 'Vui lòng chọn trạng thái hợp lệ.';
        }

        return $errors;
    }

    private function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    }
}
