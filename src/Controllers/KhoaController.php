<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\KhoaModel;

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
            'ma_khoa' => trim($data['ma_khoa'] ?? ''),
            'ten_khoa' => trim($data['ten_khoa'] ?? ''),
            'email_khoa' => trim($data['email_khoa'] ?? ''),
            'so_dien_thoai_khoa' => trim($data['so_dien_thoai_khoa'] ?? ''),
        ];

        // Validation
        if ($form['ma_khoa'] === '') {
            $state['errors']['ma_khoa'] = 'Vui lòng nhập mã khoa.';
        }

        if ($form['ten_khoa'] === '') {
            $state['errors']['ten_khoa'] = 'Vui lòng nhập tên khoa.';
        }

        if ($form['email_khoa'] !== '' && !filter_var($form['email_khoa'], FILTER_VALIDATE_EMAIL)) {
            $state['errors']['email_khoa'] = 'Email không hợp lệ.';
        }

        if ($form['so_dien_thoai_khoa'] !== '' && !preg_match('/^[0-9+\-\s]{6,20}$/', $form['so_dien_thoai_khoa'])) {
            $state['errors']['so_dien_thoai_khoa'] = 'Số điện thoại không hợp lệ.';
        }

        if (!empty($state['errors'])) {
            $state['formData'] = $form;
            return $state;
        }

        // Duplication checks
        if ($this->model->existsByMa($form['ma_khoa'])) {
            $state['errors']['ma_khoa'] = 'Mã khoa đã tồn tại.';
            $state['formData'] = $form;
            return $state;
        }

        if ($this->model->existsByName($form['ten_khoa'])) {
            $state['errors']['ten_khoa'] = 'Tên khoa đã tồn tại.';
            $state['formData'] = $form;
            return $state;
        }

        try {
            $created = $this->model->create($form);
        } catch (\Throwable $e) {
            if ($this->model->isDuplicateException($e)) {
                $state['errors']['ma_khoa'] = 'Mã hoặc tên khoa trùng lặp.';
                $state['formData'] = $form;
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
        $totalItems = $this->model->countAll();
        $totalPages = max(1, (int) ceil($totalItems / $perPage));

        $khoas = $totalItems > 0 ? $this->model->listPaginated($page, $perPage) : [];

        return [
            'khoas' => $khoas,
            'pagination' => [
                'current_page' => $page,
                'total_items' => $totalItems,
                'items_per_page' => $perPage,
                'total_pages' => $totalPages,
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
            'ten_khoa' => trim($data['ten_khoa'] ?? ''),
            'email_khoa' => trim($data['email_khoa'] ?? ''),
            'so_dien_thoai_khoa' => trim($data['so_dien_thoai_khoa'] ?? ''),
        ];

        if ($form['ten_khoa'] === '') {
            $state['errors']['ten_khoa'] = 'Vui lòng nhập tên khoa.';
            return $state;
        }

        if ($form['email_khoa'] !== '' && !filter_var($form['email_khoa'], FILTER_VALIDATE_EMAIL)) {
            $state['errors']['email_khoa'] = 'Email không hợp lệ.';
            return $state;
        }

        try {
            $updated = $this->model->update($originalMa, $form);
            $state['updated'] = $updated;
            $state['toast'] = [
                'type' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Cập nhật khoa thành công.' : 'Không có thay đổi nào được thực hiện.',
            ];
        } catch (\Throwable $e) {
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi cập nhật khoa.'];
        }

        return $state;
    }

    public function delete(string $ma): array
    {
        try {
            $deleted = $this->model->deleteByMa($ma);
            return ['success' => $deleted, 'message' => $deleted ? 'Xóa khoa thành công.' : 'Xóa thất bại.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Có lỗi khi xóa khoa.'];
        }
    }

    public function listState(array $data, array $query, string $method): array
    {
        $state = ['khoas' => [], 'pagination' => [], 'toast' => null];
        $page = max(1, (int) ($query['page_num'] ?? 1));
        $perPage = 10;

        if ($method === 'POST') {
            $action = $data['action'] ?? '';
            if ($action === 'delete' && !empty($data['ma'])) {
                $deleteState = $this->delete(trim($data['ma']));
                $state['toast'] = ['type' => $deleteState['success'] ? 'success' : 'error', 'message' => $deleteState['message']];
            }
        }

        $totalItems = $this->model->countAll();
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $page = min($page, $totalPages);

        $state['khoas'] = $totalItems > 0 ? $this->model->listPaginated($page, $perPage) : [];
        $state['pagination'] = [
            'current_page' => $page,
            'total_items' => $totalItems,
            'items_per_page' => $perPage,
            'total_pages' => $totalPages,
        ];

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
            $state['formData'] = $data;

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
            'ma_khoa' => $found['ma'],
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
}
