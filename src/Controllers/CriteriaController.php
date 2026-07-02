<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\CriteriaModel;
use KhoaLuan\QLDRL\Models\SemesterModel;
use Throwable;

class CriteriaController
{
    private CriteriaModel $criteria;
    private SemesterModel $semesters;

    public function __construct(?CriteriaModel $criteria = null, ?SemesterModel $semesters = null)
    {
        $this->criteria = $criteria ?? new CriteriaModel(Database::getConnection());
        $this->semesters = $semesters ?? new SemesterModel(Database::getConnection());
    }

    public function handle(string $page, array $post, array $get, string $method): array
    {
        $semesters = $this->semesters->getAcademicYears();
        $selectedSemesterId = $this->resolveSemesterId($get, $post, $semesters);

        switch ($page) {
            case 'setup_criteria':
                return $this->setupState($semesters, $selectedSemesterId);
            case 'list_criteria':
                return $this->listState($semesters, $selectedSemesterId, $get, $method);
            case 'configure_criteria':
                return $this->configureState($semesters, $selectedSemesterId, $post, $get, $method);
            default:
                return [];
        }
    }

    private function setupState(array $semesters, int $selectedSemesterId): array
    {
        return [
            'semesters' => $semesters,
            'selectedSemesterId' => $selectedSemesterId,
            'criteria' => $this->criteria->listBySemester($selectedSemesterId),
            'toast' => null,
        ];
    }

    private function listState(array $semesters, int $selectedSemesterId, array $get, string $method): array
    {
        $keyword = trim((string) ($get['keyword'] ?? $get['search'] ?? ''));

        return [
            'semesters' => $semesters,
            'selectedSemesterId' => $selectedSemesterId,
            'criteria' => $this->criteria->listBySemester($selectedSemesterId, $keyword),
            'filters' => ['keyword' => $keyword],
            'toast' => null,
        ];
    }

    private function configureState(array $semesters, int $selectedSemesterId, array $post, array $get, string $method): array
    {
        $statusOptions = [
            ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
            ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
        ];

        $criteriaId = (int) ($get['id'] ?? $post['id'] ?? 0);
        $state = [
            'semesters' => $semesters,
            'selectedSemesterId' => $selectedSemesterId,
            'statusOptions' => $statusOptions,
            'formData' => [],
            'errors' => [],
            'toast' => null,
            'redirect' => null,
            'isEdit' => $criteriaId > 0,
        ];

        if ($criteriaId > 0 && $method !== 'POST') {
            $criteria = $this->criteria->findById($criteriaId);
            if ($criteria !== null) {
                $state['formData'] = $criteria;
                $state['selectedSemesterId'] = (int) ($criteria['semester_id'] ?? $state['selectedSemesterId']);
            }
        }

        if ($method === 'POST') {
            $formData = [
                'semester_id' => (int) ($post['semester_id'] ?? 0),
                'name' => trim($post['name'] ?? ''),
                'description' => trim($post['description'] ?? ''),
                'credit' => $this->normalizeNumeric($post['credit'] ?? ''),
                'deduction' => $this->normalizeNumeric($post['deduction'] ?? ''),
                'execution_round' => $this->normalizeNumeric($post['execution_round'] ?? ''),
                'display_order' => $this->normalizeNumeric($post['display_order'] ?? ''),
                'status' => trim($post['status'] ?? ''),
            ];

            $state['formData'] = $formData;
            $state['errors'] = $this->validate($formData, $statusOptions);

            if (empty($state['errors'])) {
                try {
                    if ($criteriaId > 0) {
                        $updated = $this->criteria->update($criteriaId, $formData);
                        $state['toast'] = [
                            'type' => $updated ? 'success' : 'info',
                            'message' => $updated ? 'Cập nhật tiêu chí thành công.' : 'Không có thay đổi nào.',
                        ];
                        $state['redirect'] = '?page=list_criteria&semester_id=' . $formData['semester_id'];
                    } else {
                        $created = $this->criteria->create($formData);
                        $state['toast'] = [
                            'type' => $created ? 'success' : 'error',
                            'message' => $created ? 'Tạo tiêu chí thành công.' : 'Tạo tiêu chí thất bại. Vui lòng thử lại.',
                        ];
                        if ($created) {
                            $state['formData'] = [];
                            $state['redirect'] = '?page=list_criteria&semester_id=' . $formData['semester_id'];
                        }
                    }
                } catch (Throwable $exception) {
                    error_log($exception->getMessage());
                    $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi lưu tiêu chí. Vui lòng thử lại.'];
                }
            }
        }

        return $state;
    }

    private function resolveSemesterId(array $get, array $post, array $semesters): int
    {
        $semesterId = trim((string) ($post['semester_id'] ?? $get['semester_id'] ?? $get['semester'] ?? ''));
        if ($semesterId !== '' && ctype_digit($semesterId) && (int) $semesterId > 0) {
            return (int) $semesterId;
        }

        if (!empty($semesters) && isset($semesters[0]['id'])) {
            return (int) $semesters[0]['id'];
        }

        return 0;
    }

    private function validate(array $formData, array $statusOptions): array
    {
        $errors = [];

        if ($formData['semester_id'] < 1) {
            $errors['semester_id'] = 'Học kỳ không hợp lệ.';
        }

        if ($formData['name'] === '') {
            $errors['name'] = 'Tên tiêu chí là bắt buộc.';
        }

        if ($formData['description'] === '') {
            $errors['description'] = 'Mô tả tiêu chí là bắt buộc.';
        }

        if ($formData['credit'] < 0) {
            $errors['credit'] = 'Điểm cộng phải là số hợp lệ.';
        }

        if ($formData['deduction'] < 0) {
            $errors['deduction'] = 'Điểm trừ phải là số hợp lệ.';
        }

        if ($formData['execution_round'] < 0) {
            $errors['execution_round'] = 'Lần thực hiện phải là số nguyên không âm.';
        }

        if ($formData['display_order'] < 0) {
            $errors['display_order'] = 'Thứ tự hiển thị phải là số nguyên không âm.';
        }

        $validStatuses = array_column($statusOptions, 'value');
        if ($formData['status'] === '' || !in_array($formData['status'], $validStatuses, true)) {
            $errors['status'] = 'Trạng thái tiêu chí là bắt buộc.';
        }

        return $errors;
    }

    private function normalizeNumeric(mixed $value): int
    {
        $value = trim((string) $value);
        if ($value === '' || !is_numeric($value)) {
            return 0;
        }

        return (int) $value;
    }
}
