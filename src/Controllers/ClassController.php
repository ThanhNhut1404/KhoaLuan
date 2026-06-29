<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\ClassModel;
use Throwable;

class ClassController
{
    private const MAX_CODE_LENGTH = 50;
    private const MAX_NAME_LENGTH = 100;
    private const MAX_CAPACITY = 200;

    private const STATUS_OPTIONS = [
        ['value' => 'upcoming', 'label' => 'Sắp tới'],
        ['value' => 'active', 'label' => 'Đang diễn ra'],
        ['value' => 'completed', 'label' => 'Đã hoàn thành'],
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
}
