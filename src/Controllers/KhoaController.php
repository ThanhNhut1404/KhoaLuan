<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\KhoaModel;
use Throwable;

class KhoaController
{
    private KhoaModel $model;

    public function __construct(?KhoaModel $model = null)
    {
        $this->model = $model ?? new KhoaModel(Database::getConnection());
    }

    public function create(array $data, string $method): array
    {
        $state = [
            'formData' => $method === 'POST' ? $data : [],
            'errors' => [],
            'toast' => null,
        ];

        if ($method !== 'POST') {
            return $state;
        }

        $form = [
            'ma_khoa' => $this->normalizeAbbreviation($data['ma_khoa'] ?? ''),
            'ten_khoa' => trim($data['ten_khoa'] ?? ''),
            'email_khoa' => trim($data['email_khoa'] ?? ''),
            'so_dien_thoai_khoa' => trim($data['so_dien_thoai_khoa'] ?? ''),
        ];

        $state['formData'] = $form;
        $state['errors'] = $this->validateCreate($form);

        if (!empty($state['errors'])) {
            return $state;
        }

        if ($this->model->existsByAbbreviation($form['ma_khoa'])) {
            $state['errors']['ma_khoa'] = 'Mã khoa đã tồn tại.';
            return $state;
        }

        if ($this->model->existsByName($form['ten_khoa'])) {
            $state['errors']['ten_khoa'] = 'Tên khoa đã tồn tại.';
            return $state;
        }

        try {
            $created = $this->model->create($form);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            if ($this->model->isDuplicateException($e)) {
                if ($this->model->existsByAbbreviation($form['ma_khoa'])) {
                    $state['errors']['ma_khoa'] = 'Mã khoa đã tồn tại.';
                    return $state;
                }

                if ($this->model->existsByName($form['ten_khoa'])) {
                    $state['errors']['ten_khoa'] = 'Tên khoa đã tồn tại.';
                    return $state;
                }

                $state['toast'] = ['type' => 'error', 'message' => 'Tạo khoa thất bại.'];
                return $state;
            }

            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tạo khoa.'];
            return $state;
        }

        $state['toast'] = [
            'type' => $created ? 'success' : 'error',
            'message' => $created ? 'Tạo khoa thành công.' : 'Tạo khoa thất bại.',
        ];

        if ($created) {
            $state['formData'] = [];
        }

        return $state;
    }

    public function listing(array $data, array $query, string $method): array
    {
        $page = max(1, (int) ($query['page_num'] ?? 1));
        $perPage = 10;
        $keyword = trim($query['keyword'] ?? $query['q'] ?? $query['search'] ?? '');
        $totalItems = $keyword === '' ? $this->model->countAll() : $this->model->countFiltered($keyword);
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $page = min($page, $totalPages);

        $khoas = $totalItems > 0
            ? ($keyword === ''
                ? $this->model->listPaginated($page, $perPage)
                : $this->model->listFilteredPaginated($page, $perPage, $keyword))
            : [];

        return [
            'khoas' => $khoas,
            'filters' => ['keyword' => $keyword],
            'emptyMessage' => $keyword === '' ? 'Chưa có khoa/bộ môn nào.' : 'Không tìm thấy khoa/bộ môn phù hợp.',
            'pagination' => [
                'current_page' => $page,
                'total_items' => $totalItems,
                'items_per_page' => $perPage,
                'total_pages' => $totalPages,
                'from' => $totalItems === 0 ? 0 : (($page - 1) * $perPage) + 1,
                'to' => min($totalItems, $page * $perPage),
            ],
        ];
    }

    public function find(string $ma): ?array
    {
        return $this->model->findByMa($ma);
    }

    public function update(string $originalMa, array $data): array
    {
        $state = ['errors' => [], 'toast' => null, 'updated' => false];

        $form = [
            'ma_khoa' => $this->normalizeAbbreviation($data['ma_khoa'] ?? ''),
            'ten_khoa' => trim($data['ten_khoa'] ?? ''),
            'email_khoa' => trim($data['email_khoa'] ?? ''),
            'so_dien_thoai_khoa' => trim($data['so_dien_thoai_khoa'] ?? ''),
        ];

        if ($form['ma_khoa'] === '') {
            $state['errors']['ma_khoa'] = 'Vui lòng nhập mã khoa.';
            return $state;
        }

        if ($form['ten_khoa'] === '') {
            $state['errors']['ten_khoa'] = 'Vui lòng nhập tên khoa.';
            return $state;
        }

        if ($form['email_khoa'] !== '' && !filter_var($form['email_khoa'], FILTER_VALIDATE_EMAIL)) {
            $state['errors']['email_khoa'] = 'Email không hợp lệ.';
            return $state;
        }

        if ($this->model->existsByAbbreviationExceptMa($form['ma_khoa'], $originalMa)) {
            $state['errors']['ma_khoa'] = 'Mã khoa đã tồn tại.';
            return $state;
        }

        try {
            $updated = $this->model->update($originalMa, $form);
            $state['updated'] = $updated;
            $state['toast'] = [
                'type' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Cập nhật khoa thành công.' : 'Không có thay đổi nào được thực hiện.',
            ];
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi cập nhật khoa.'];
        }

        return $state;
    }

    public function delete(string $ma): array
    {
        try {
            $deleted = $this->model->deleteByMa($ma);
            return ['success' => $deleted, 'message' => $deleted ? 'Xóa khoa thành công.' : 'Xóa thất bại.'];
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi khi xóa khoa.'];
        }
    }

    public function listState(array $data, array $query, string $method): array
    {
        $state = ['khoas' => [], 'pagination' => [], 'toast' => null, 'filters' => [], 'emptyMessage' => 'Chưa có khoa/bộ môn nào.'];
        $page = max(1, (int) ($query['page_num'] ?? 1));
        $perPage = 10;
        $keyword = trim($query['keyword'] ?? $query['q'] ?? $query['search'] ?? '');
        $state['filters'] = ['keyword' => $keyword];

        if ($method === 'POST') {
            $action = $data['action'] ?? '';
            if ($action === 'delete' && !empty($data['ma'])) {
                $deleteState = $this->delete(trim($data['ma']));
                $state['toast'] = ['type' => $deleteState['success'] ? 'success' : 'error', 'message' => $deleteState['message']];
            }
        }

        try {
            $totalItems = $keyword === '' ? $this->model->countAll() : $this->model->countFiltered($keyword);
            $totalPages = max(1, (int) ceil($totalItems / $perPage));
            $page = min($page, $totalPages);

            $state['khoas'] = $totalItems > 0
                ? ($keyword === ''
                    ? $this->model->listPaginated($page, $perPage)
                    : $this->model->listFilteredPaginated($page, $perPage, $keyword))
                : [];
            $state['emptyMessage'] = $keyword === '' ? 'Chưa có khoa/bộ môn nào.' : 'Không tìm thấy khoa/bộ môn phù hợp.';
            $state['pagination'] = [
                'current_page' => $page,
                'total_items' => $totalItems,
                'items_per_page' => $perPage,
                'total_pages' => $totalPages,
                'from' => $totalItems === 0 ? 0 : (($page - 1) * $perPage) + 1,
                'to' => min($totalItems, $page * $perPage),
            ];
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $state['khoas'] = [];
            $state['emptyMessage'] = $keyword === '' ? 'Chưa có khoa/bộ môn nào.' : 'Không tìm thấy khoa/bộ môn phù hợp.';
            $state['pagination'] = [
                'current_page' => 1,
                'total_items' => 0,
                'items_per_page' => $perPage,
                'total_pages' => 1,
                'from' => 0,
                'to' => 0,
            ];
            $state['toast'] = [
                'type' => 'error',
                'message' => $keyword === '' ? 'Không thể tải danh sách khoa.' : 'Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại.',
            ];
        }

        return $state;
    }

    public function editState(string $ma, array $data, string $method): array
    {
        $state = [
            'formData' => [],
            'errors' => [],
            'toast' => null,
            'redirect' => null,
            'isEdit' => true,
        ];

        $ma = trim($ma);
        if ($ma === '') {
            $state['toast'] = ['type' => 'error', 'message' => 'Mã khoa không hợp lệ.'];
            $state['redirect'] = '?page=list_khoa';
            return $state;
        }

        if ($method === 'POST') {
            $originalMa = trim($data['original_ma'] ?? '');
            $updateState = $this->update($originalMa, $data);
            $state['errors'] = $updateState['errors'] ?? [];
            $state['toast'] = $updateState['toast'] ?? null;
            $state['formData'] = [
                'original_ma' => $originalMa,
                'ma_khoa' => $this->normalizeAbbreviation($data['ma_khoa'] ?? ''),
                'ten_khoa' => trim($data['ten_khoa'] ?? ''),
                'email_khoa' => trim($data['email_khoa'] ?? ''),
                'so_dien_thoai_khoa' => trim($data['so_dien_thoai_khoa'] ?? ''),
            ];

            if (!empty($updateState['updated'])) {
                $state['redirect'] = '?page=list_khoa';
            }

            return $state;
        }

        $found = $this->find($ma);
        if ($found === null) {
            $state['toast'] = ['type' => 'error', 'message' => 'Không tìm thấy khoa.'];
            $state['redirect'] = '?page=list_khoa';
            return $state;
        }

        $state['formData'] = [
            'original_ma' => $found['ma'],
            'ma_khoa' => $found['ten_viet_tat'],
            'ten_khoa' => $found['ten'],
            'email_khoa' => $found['email'],
            'so_dien_thoai_khoa' => $found['phone'],
        ];

        return $state;
    }

    public function handle(string $page, array $post, array $get, string $method): array
    {
        $page = trim($page);

        if ($page === 'create_khoa') {
            $state = $this->create($post, $method);
            $state['page'] = 'create_khoa';
            return $state;
        }

        if ($page === 'list_khoa') {
            $state = $this->listState($post, $get, $method);
            $state['page'] = 'list_khoa';
            return $state;
        }

        if ($page === 'edit_khoa') {
            $ma = trim($get['ma'] ?? '');
            $state = $this->editState($ma, $post, $method);
            $state['page'] = 'edit_khoa';
            return $state;
        }

        return ['page' => $page, 'formData' => [], 'errors' => [], 'toast' => null, 'khoas' => [], 'pagination' => [], 'redirect' => null, 'isEdit' => false];
    }

    private function validateCreate(array $form): array
    {
        $errors = [];

        if ($form['ma_khoa'] === '') {
            $errors['ma_khoa'] = 'Vui lòng nhập mã khoa.';
        }

        if ($form['ten_khoa'] === '') {
            $errors['ten_khoa'] = 'Vui lòng nhập tên khoa.';
        }

        if ($form['email_khoa'] !== '' && !filter_var($form['email_khoa'], FILTER_VALIDATE_EMAIL)) {
            $errors['email_khoa'] = 'Email không hợp lệ (ví dụ: tennguoidung@truonghoc.edu.vn).';
        }

        if ($form['so_dien_thoai_khoa'] !== '' && !preg_match('/^0\d{9,10}$/', $form['so_dien_thoai_khoa'])) {
            $errors['so_dien_thoai_khoa'] = 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng (chỉ gồm số, 10–11 chữ số và phải bắt đầu bằng số 0).';
        }

        return $errors;
    }

    private function normalizeAbbreviation(string $abbreviation): string
    {
        $abbreviation = trim($abbreviation);

        return function_exists('mb_strtoupper') ? mb_strtoupper($abbreviation, 'UTF-8') : strtoupper($abbreviation);
    }
}
