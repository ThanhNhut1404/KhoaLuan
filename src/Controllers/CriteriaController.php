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

    public function getSemestersByAcademicYear(int $academicYearId): array
    {
        return $this->semesters->getSemestersByAcademicYear($academicYearId);
    }

    public function getCategoriesBySemester(int $semesterId): array
    {
        return $this->criteria->listCategoriesBySemester($semesterId);
    }

    public function getMasterTemplates(): array
    {
        return $this->criteria->listMasterTemplates();
    }

    public function getAppliedTemplateBySemester(int $semesterId): ?array
    {
        return $this->criteria->getAppliedTemplateForSemester($semesterId);
    }

    public function getTemplateCategories(int $templateId): array
    {
        return $this->criteria->listCategoriesByTemplate($templateId);
    }

    public function getTemplateCriteria(int $templateId): array
    {
        return $this->criteria->listCriteriaByTemplate($templateId);
    }

    public function handle(string $page, array $post, array $get, string $method): array
    {
        $hocKyList = $this->semesters->getActiveSemesters();
        $selectedSemesterId = $this->resolveSemesterId($get, $post, $hocKyList);

        switch ($page) {
            case 'setup_criteria':
                return $this->setupState($hocKyList, $selectedSemesterId, $post, $get, $method);
            case 'apply_criteria':
                return $this->applyCriteriaState($post, $get, $method);
            case 'list_criteria':
                return $this->listState($hocKyList, $selectedSemesterId, $post, $get, $method);
            case 'configure_criteria':
                return $this->configureState($hocKyList, $selectedSemesterId, $post, $get, $method);
            default:
                return [];
        }
    }

    private function setupState(array $semesters, int $selectedSemesterId, array $post = [], array $get = [], string $method = 'GET'): array
    {
        $masterTemplates = $this->getMasterTemplates();
        $selectedTemplateId = $this->normalizeInt($post['template_id'] ?? $get['template_id'] ?? 0);
        $selectedTemplate = $selectedTemplateId > 0 ? $this->criteria->findMasterTemplateById($selectedTemplateId) : null;
        $categories = [];
        $criteria = [];
        $criteriaByCategory = [];
        $templateTotalPoints = 0.0;

        if ($selectedTemplateId > 0) {
            $categories = $this->getTemplateCategories($selectedTemplateId);
            $criteria = $this->getTemplateCriteria($selectedTemplateId);
            foreach ($criteria as $item) {
                $criteriaByCategory[$item['category_id']][] = $item;
            }
            $templateTotalPoints = $this->criteria->sumTemplateCategoryPoints($selectedTemplateId);
        }

        $state = [
            'academicYears' => [],
            'semesters' => [],
            'selectedAcademicYearId' => 0,
            'selectedSemesterId' => 0,
            'masterTemplates' => $masterTemplates,
            'selectedTemplateId' => $selectedTemplateId,
            'selectedTemplate' => $selectedTemplate,
            'templateTotalPoints' => $templateTotalPoints,
            'appliedTemplate' => null,
            'categories' => $categories,
            'criteria' => $criteria,
            'criteriaByCategory' => $criteriaByCategory,
            'formData' => [],
            'errors' => [],
            'toast' => null,
            'formType' => '',
            'redirect' => null,
        ];

        if ($method === 'POST') {
            $formType = trim((string) ($post['form_type'] ?? ''));
            $state['formType'] = $formType;
            $state['formData'] = $post;

            switch ($formType) {
                case 'template_master':
                    $result = $this->storeMasterTemplate($post, 'setup_criteria');
                    break;
                case 'template_master_update':
                    $result = $this->updateMasterTemplate($post, 'setup_criteria');
                    break;
                case 'template_master_lock':
                    $result = $this->lockMasterTemplate($post, 'setup_criteria');
                    break;
                case 'template_master_delete':
                    $result = $this->deleteMasterTemplate($post, 'setup_criteria');
                    break;
                case 'template_category':
                    $result = $this->storeTemplateCategory($post, 'setup_criteria');
                    break;
                case 'template_category_update':
                    $result = $this->updateTemplateCategory($post, 'setup_criteria');
                    break;
                case 'template_category_delete':
                    $result = $this->deleteTemplateCategory($post, 'setup_criteria');
                    break;
                case 'template_criteria':
                    $result = $this->storeTemplateCriteria($post, 'setup_criteria');
                    break;
                case 'template_criteria_update':
                    $result = $this->updateTemplateCriteria($post, 'setup_criteria');
                    break;
                case 'template_criteria_delete':
                    $result = $this->deleteTemplateCriteria($post, 'setup_criteria');
                    break;
                default:
                    $result = ['formData' => $post, 'errors' => ['form_type' => 'Yêu cầu không hợp lệ.'], 'toast' => null, 'redirect' => null];
                    break;
            }

            $state = array_merge($state, $result);
            if (!empty($result['redirect'])) {
                return $state;
            }

            $selectedTemplateId = $this->normalizeInt($post['template_id'] ?? $selectedTemplateId);
            if ($selectedTemplateId > 0) {
                $state['categories'] = $this->getTemplateCategories($selectedTemplateId);
                $state['criteria'] = $this->getTemplateCriteria($selectedTemplateId);
                $state['criteriaByCategory'] = [];
                foreach ($state['criteria'] as $item) {
                    $state['criteriaByCategory'][$item['category_id']][] = $item;
                }
                $state['selectedTemplateId'] = $selectedTemplateId;
                $state['selectedTemplate'] = $this->criteria->findMasterTemplateById($selectedTemplateId);
                $state['templateTotalPoints'] = $this->criteria->sumTemplateCategoryPoints($selectedTemplateId);
            }
        }

        return $state;
    }

    private function applyCriteriaState(array $post, array $get, string $method): array
    {
        $academicYears = $this->semesters->getAcademicYears();

        $selectedAcademicYearId = $this->normalizeInt(
            $post['MA_NIEN_KHOA'] ?? $get['MA_NIEN_KHOA'] ?? ''
        );

        $semesters = [];
        if ($selectedAcademicYearId > 0) {
            $semesters = $this->semesters->getSemestersByAcademicYear($selectedAcademicYearId);
        }

        error_log('apply_criteria selectedAcademicYearId=' . $selectedAcademicYearId);
        error_log('apply_criteria semesters count=' . count($semesters));

        $semesterRows = [];
        foreach ($semesters as $semester) {
            $semesterId = (int)($semester['MA_HOC_KY'] ?? $semester['id'] ?? 0);

            $semesterRows[] = [
                'id' => $semesterId,
                'name' => $semester['TEN_HOC_KY'] ?? $semester['name'] ?? '',
                'start_date' => $semester['THOI_GIAN_BDHK'] ?? $semester['start_date'] ?? '',
                'end_date' => $semester['THOI_GIAN_KTHK'] ?? $semester['end_date'] ?? '',
                'status' => $semester['TRANG_THAI_HK'] ?? $semester['status'] ?? '',
                'appliedTemplate' => $semesterId > 0
                    ? $this->criteria->getAppliedTemplateForSemester($semesterId)
                    : null,
            ];
        }

        error_log('apply_criteria semesterRows count=' . count($semesterRows));

        $state = [
            'academicYears' => $academicYears,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'semesterRows' => $semesterRows,
            'masterTemplates' => $this->criteria->listMasterTemplates(),
            'formData' => [],
            'errors' => [],
            'toast' => null,
            'formType' => '',
            'redirect' => null,
        ];

        if ($method === 'POST') {
            $formType = trim((string) ($post['form_type'] ?? ''));
            $state['formType'] = $formType;
            $state['formData'] = $post;

            if ($formType === 'apply_template') {
                $semesterId = $this->normalizeInt($post['semester_id'] ?? '');
                $templateId = $this->normalizeInt($post['apply_template_id'] ?? $post['template_id'] ?? '');
                error_log('apply_template semester_id=' . $semesterId);
                error_log('apply_template template_id=' . $templateId);

                $result = $this->applyTemplate($post, 'apply_criteria');
                $state = array_merge($state, $result);
                error_log('apply_template result=' . (!empty($result['redirect']) ? 'success' : 'fail'));

                if (!empty($result['redirect'])) {
                    return $state;
                }
            }
        }

        return $state;
    }

    private function listState(array $semesters, int $selectedSemesterId, array $post, array $get, string $method): array
    {
        $academicYears = $this->semesters->getAcademicYears();
        $selectedAcademicYearId = $this->normalizeInt($post['MA_NIEN_KHOA'] ?? $get['MA_NIEN_KHOA'] ?? '');

        if ($selectedAcademicYearId === 0 && $selectedSemesterId > 0) {
            $selectedAcademicYearId = $this->semesters->getAcademicYearIdBySemester($selectedSemesterId);
        }

        if ($selectedAcademicYearId === 0 && !empty($academicYears)) {
            $selectedAcademicYearId = (int) ($academicYears[0]['id'] ?? $academicYears[0]['MA_NIEN_KHOA'] ?? 0);
        }

        $semesterOptions = $selectedAcademicYearId > 0
            ? $this->semesters->getActiveSemestersByAcademicYear($selectedAcademicYearId)
            : [];

        if ($selectedSemesterId === 0 && !empty($semesterOptions)) {
            $selectedSemesterId = (int) $semesterOptions[0]['MA_HOC_KY'];
        }

        if ($selectedSemesterId > 0 && !$this->isSemesterInList($selectedSemesterId, $semesterOptions)) {
            $selectedSemesterId = !empty($semesterOptions) ? (int) $semesterOptions[0]['MA_HOC_KY'] : 0;
        }

        $state = [
            'academicYears' => $academicYears,
            'semesters' => $semesterOptions,
            'semesterOptions' => $semesterOptions,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedSemesterId' => $selectedSemesterId,
            'categories' => $selectedSemesterId > 0 ? $this->criteria->listCategoriesBySemester($selectedSemesterId) : [],
            'criteria' => $selectedSemesterId > 0 ? $this->criteria->listBySemester($selectedSemesterId) : [],
            'formData' => [],
            'errors' => [],
            'toast' => null,
            'formType' => '',
            'redirect' => null,
        ];

        if ($method === 'POST') {
            $formType = trim((string) ($post['form_type'] ?? ''));
            $state['formType'] = $formType;
            $state['formData'] = $post;

            if ($formType === 'category') {
                $result = $this->storeCategory($post, 'list_criteria');
            } elseif ($formType === 'criteria') {
                $result = $this->storeSubCriteria($post);
            } else {
                $result = ['formData' => $post, 'errors' => ['form_type' => 'Yêu cầu không hợp lệ.'], 'toast' => null, 'redirect' => null];
            }

            $state = array_merge($state, $result);
            if (!empty($result['redirect'])) {
                return $state;
            }

            $selectedSemesterId = isset($post['semester_id']) && $post['semester_id'] !== ''
                ? $this->normalizeInt($post['semester_id'])
                : $selectedSemesterId;
            $state['categories'] = $selectedSemesterId > 0 ? $this->criteria->listCategoriesBySemester($selectedSemesterId) : [];
            $state['criteria'] = $selectedSemesterId > 0 ? $this->criteria->listBySemester($selectedSemesterId) : [];
        }

        $criteriaByCategory = [];
        foreach ($state['criteria'] as $item) {
            $criteriaByCategory[$item['category_id']][] = $item;
        }

        $state['criteriaByCategory'] = $criteriaByCategory;

        return $state;
    }

    private function isSemesterInList(int $semesterId, array $semesterOptions): bool
    {
        foreach ($semesterOptions as $semester) {
            if ((int) ($semester['MA_HOC_KY'] ?? $semester['id'] ?? 0) === $semesterId) {
                return true;
            }
        }

        return false;
    }

    private function configureState(array $semesters, int $selectedSemesterId, array $post, array $get, string $method): array
    {
        $criteriaId = (int) ($get['id'] ?? $post['id'] ?? 0);
        $selectedAcademicYearId = $this->normalizeInt($post['MA_NIEN_KHOA'] ?? $get['MA_NIEN_KHOA'] ?? '');

        if ($selectedAcademicYearId === 0 && $selectedSemesterId > 0) {
            $selectedAcademicYearId = $this->semesters->getAcademicYearIdBySemester($selectedSemesterId);
        }

        $filteredSemesters = $selectedAcademicYearId > 0
            ? $this->semesters->getActiveSemestersByAcademicYear($selectedAcademicYearId)
            : $semesters;

        $state = [
            'semesters' => $filteredSemesters,
            'hoc_ky_list' => $filteredSemesters,
            'academicYears' => $this->semesters->getAcademicYears(),
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedSemesterId' => $selectedSemesterId,
            'statusOptions' => $this->getStatusOptions(),
            'typeOptions' => $this->getCriteriaTypeOptions(),
            'categories' => [],
            'formData' => [],
            'errors' => [],
            'toast' => null,
            'redirect' => null,
            'isEdit' => $criteriaId > 0,
            'formType' => 'criteria',
        ];

        if ($criteriaId > 0 && $method !== 'POST') {
            $criteria = $this->criteria->findById($criteriaId);
            if ($criteria !== null) {
                $state['formData'] = $criteria;
                $state['selectedSemesterId'] = (int) ($criteria['semester_id'] ?? $state['selectedSemesterId']);
                $state['selectedAcademicYearId'] = $state['selectedAcademicYearId'] ?: $this->semesters->getAcademicYearIdBySemester($state['selectedSemesterId']);
                if ($state['selectedAcademicYearId'] > 0) {
                    $state['semesters'] = $this->semesters->getActiveSemestersByAcademicYear($state['selectedAcademicYearId']);
                }
            }
        }

        $state['categories'] = $this->criteria->listCategoriesBySemester($state['selectedSemesterId']);

        if ($method === 'POST') {
            $formType = trim((string) ($post['form_type'] ?? 'criteria'));
            $formData = [
                'semester_id' => $this->normalizeInt($post['semester_id'] ?? ''),
                'MA_NIEN_KHOA' => $this->normalizeInt($post['MA_NIEN_KHOA'] ?? ''),
                'category_id' => $this->normalizeInt($post['category_id'] ?? ''),
                'category_name' => trim($post['category_name'] ?? ''),
                'category_max_points' => $this->normalizeDecimal($post['category_max_points'] ?? ''),
                'category_display_order' => $this->normalizeInt($post['category_display_order'] ?? ''),
                'name' => trim($post['name'] ?? ''),
                'description' => trim($post['description'] ?? ''),
                'type' => trim($post['type'] ?? ''),
                'fixed_point' => $this->normalizeDecimal($post['fixed_point'] ?? ''),
                'add_point' => $this->normalizeDecimal($post['add_point'] ?? ''),
                'deduct_point' => $this->normalizeDecimal($post['deduct_point'] ?? ''),
                'max_point' => $this->normalizeDecimal($post['max_point'] ?? ''),
                'max_times' => $this->normalizeInt($post['max_times'] ?? ''),
                'is_activity' => $this->normalizeBoolean($post['is_activity'] ?? ''),
                'use_for_activity' => $this->normalizeBoolean($post['use_for_activity'] ?? $post['is_activity'] ?? ''),
                'display_order' => $this->normalizeInt($post['display_order'] ?? ''),
                'status' => trim($post['status'] ?? ''),
            ];

            $state['formType'] = $formType;
            $state['formData'] = $formData;
            $state['selectedAcademicYearId'] = $state['selectedAcademicYearId'] ?: $formData['MA_NIEN_KHOA'];

            if ($formType === 'category') {
                $result = $this->storeCategory($formData);
            } else {
                $result = $this->storeCriteria($formData, $criteriaId);
            }

            $state = array_merge($state, $result);
            $state['categories'] = $this->criteria->listCategoriesBySemester($state['formData']['semester_id'] ?: $selectedSemesterId);
        }

        return $state;
    }

    public function storeCategory(array $formData, string $redirectPage = 'configure_criteria'): array
    {
        $errors = $this->validateCategory($formData);
        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $created = $this->criteria->createCategory([
                'semester_id' => $formData['semester_id'],
                'name' => $formData['category_name'],
                'max_points' => $formData['category_max_points'],
                'display_order' => $formData['category_display_order'],
            ]);

            if ($created) {
                return [
                    'formData' => [],
                    'errors' => [],
                    'toast' => ['type' => 'success', 'message' => 'Tạo danh mục tiêu chí thành công.'],
                    'redirect' => '?page=' . $redirectPage . '&semester_id=' . $formData['semester_id'] . '&MA_NIEN_KHOA=' . ($formData['MA_NIEN_KHOA'] ?? ''),
                ];
            }

            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Tạo danh mục thất bại. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        } catch (\InvalidArgumentException $exception) {
            return [
                'formData' => $formData,
                'errors' => ['category_general' => $exception->getMessage()],
                'toast' => null,
                'redirect' => null,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi tạo danh mục. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function storeSubCriteria(array $formData, string $redirectPage = 'list_criteria'): array
    {
        $formData = $this->normalizeCriteriaFormData($formData);
        return $this->storeCriteria($formData, 0, $redirectPage);
    }

    public function storeMasterTemplate(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $data = [
            'template_name' => trim($formData['template_name'] ?? ''),
            'template_description' => trim($formData['template_description'] ?? ''),
            'template_status' => trim($formData['template_status'] ?? '1'),
        ];

        $errors = $this->validateMasterTemplate($data);
        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $templateId = $this->criteria->createTemplate([
                'name' => $data['template_name'],
                'description' => $data['template_description'],
                'status' => $data['template_status'],
            ]);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => 'success', 'message' => 'Tạo bộ tiêu chí mẫu thành công.'],
                'redirect' => '?page=' . $redirectPage . '&template_id=' . $templateId,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi tạo bộ tiêu chí mẫu. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function updateMasterTemplate(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $templateId = $this->normalizeInt($formData['template_id'] ?? 0);
        $data = [
            'template_name' => trim($formData['template_name'] ?? ''),
            'template_description' => trim($formData['template_description'] ?? ''),
            'template_status' => trim($formData['template_status'] ?? '1'),
        ];

        $errors = $this->validateMasterTemplate($data);
        if ($templateId < 1) {
            $errors['template_id'] = 'Vui lòng chọn bộ tiêu chí mẫu.';
        }

        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $updated = $this->criteria->updateTemplate($templateId, [
                'name' => $data['template_name'],
                'description' => $data['template_description'],
                'status' => $data['template_status'],
            ]);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => $updated ? 'success' : 'info', 'message' => $updated ? 'Cập nhật bộ tiêu chí mẫu thành công.' : 'Không có thay đổi nào.'],
                'redirect' => '?page=' . $redirectPage . '&template_id=' . $templateId,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi cập nhật bộ tiêu chí mẫu. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function lockMasterTemplate(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $templateId = $this->normalizeInt($formData['template_id'] ?? 0);
        if ($templateId < 1) {
            return [
                'formData' => $formData,
                'errors' => ['template_id' => 'Vui lòng chọn bộ tiêu chí mẫu.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $this->criteria->deleteOrLockTemplate($templateId);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => 'success', 'message' => 'Đã khóa bộ tiêu chí mẫu.'],
                'redirect' => '?page=' . $redirectPage,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi khóa bộ tiêu chí mẫu. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function deleteMasterTemplate(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $templateId = $this->normalizeInt($formData['template_id'] ?? 0);
        if ($templateId < 1) {
            return [
                'formData' => $formData,
                'errors' => ['template_id' => 'Vui lòng chọn bộ tiêu chí mẫu.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $appliedCount = $this->criteria->countAppliedTemplatesByTemplate($templateId);
            if ($appliedCount > 0) {
                return [
                    'formData' => $formData,
                    'errors' => ['template_id' => 'Bộ tiêu chí đã được áp dụng, không thể xóa. Hãy khóa bộ mẫu thay thế.'],
                    'toast' => ['type' => 'error', 'message' => 'Bộ tiêu chí đã được áp dụng, không thể xóa. Hãy khóa bộ mẫu thay thế.'],
                    'redirect' => null,
                ];
            }

            $deleted = $this->criteria->deleteTemplateWithRelations($templateId);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => $deleted ? 'success' : 'info', 'message' => $deleted ? 'Xóa bộ tiêu chí mẫu thành công.' : 'Không thể xóa bộ tiêu chí mẫu này.'],
                'redirect' => '?page=' . $redirectPage,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi xóa bộ tiêu chí mẫu. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function storeTemplateCategory(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $formData = [
            'template_id' => $this->normalizeInt($formData['template_id'] ?? ''),
            'category_name' => trim($formData['category_name'] ?? ''),
            'category_max_points' => $this->normalizeDecimal($formData['category_max_points'] ?? ''),
            'category_display_order' => $this->normalizeInt($formData['category_display_order'] ?? ''),
        ];

        $errors = $this->validateTemplateCategory($formData);
        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        $currentTemplatePoints = $this->criteria->sumTemplateCategoryPoints($formData['template_id']);
        if ($currentTemplatePoints + $formData['category_max_points'] > 100.0) {
            return [
                'formData' => $formData,
                'errors' => ['category_max_points' => 'Tổng điểm tối đa của các danh mục trong bộ mẫu không được vượt quá 100 điểm.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $created = $this->criteria->createTemplateCategory([
                'template_id' => $formData['template_id'],
                'name' => $formData['category_name'],
                'max_points' => $formData['category_max_points'],
                'display_order' => $formData['category_display_order'],
            ]);

            if ($created) {
                return [
                    'formData' => [],
                    'errors' => [],
                    'toast' => ['type' => 'success', 'message' => 'Tạo danh mục trong bộ mẫu thành công.'],
                    'redirect' => '?page=' . $redirectPage . '&template_id=' . $formData['template_id'],
                ];
            }

            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Tạo danh mục thất bại. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        } catch (\InvalidArgumentException $exception) {
            return [
                'formData' => $formData,
                'errors' => ['category_general' => $exception->getMessage()],
                'toast' => null,
                'redirect' => null,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi tạo danh mục bộ mẫu. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function updateTemplateCategory(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $categoryId = $this->normalizeInt($formData['category_id'] ?? 0);
        $templateId = $this->normalizeInt($formData['template_id'] ?? 0);
        $data = [
            'template_id' => $templateId,
            'category_name' => trim($formData['category_name'] ?? ''),
            'category_max_points' => $this->normalizeDecimal($formData['category_max_points'] ?? ''),
            'category_display_order' => $this->normalizeInt($formData['category_display_order'] ?? ''),
        ];

        $errors = $this->validateTemplateCategory($data);
        if ($categoryId < 1) {
            $errors['category_id'] = 'Vui lòng chọn danh mục.';
        }

        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        $currentCategory = $this->criteria->getCategoryMaxPoints($categoryId);
        $currentTemplatePoints = $this->criteria->sumTemplateCategoryPoints($templateId);
        $newTemplatePoints = $currentTemplatePoints - $currentCategory + $data['category_max_points'];
        if ($newTemplatePoints > 100.0) {
            return [
                'formData' => $formData,
                'errors' => ['category_max_points' => 'Tổng điểm tối đa của các danh mục trong bộ mẫu không được vượt quá 100 điểm.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $updated = $this->criteria->updateTemplateCategory($categoryId, [
                'template_id' => $templateId,
                'name' => $data['category_name'],
                'max_points' => $data['category_max_points'],
                'display_order' => $data['category_display_order'],
            ]);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => $updated ? 'success' : 'info', 'message' => $updated ? 'Cập nhật danh mục thành công.' : 'Không có thay đổi nào.'],
                'redirect' => '?page=' . $redirectPage . '&template_id=' . $templateId,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi cập nhật danh mục. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function deleteTemplateCategory(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $categoryId = $this->normalizeInt($formData['category_id'] ?? 0);
        $templateId = $this->normalizeInt($formData['template_id'] ?? 0);

        if ($categoryId < 1) {
            return [
                'formData' => $formData,
                'errors' => ['category_id' => 'Vui lòng chọn danh mục.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        $criteriaCount = $this->criteria->countCriteriaByCategory($categoryId);
        if ($criteriaCount > 0) {
            return [
                'formData' => $formData,
                'errors' => ['category_id' => 'Không thể xóa danh mục vì vẫn còn tiêu chí con.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $deleted = $this->criteria->deleteTemplateCategory($categoryId);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => $deleted ? 'success' : 'info', 'message' => $deleted ? 'Xóa danh mục thành công.' : 'Không thể xóa danh mục này.'],
                'redirect' => '?page=' . $redirectPage . '&template_id=' . $templateId,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi xóa danh mục. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function storeTemplateCriteria(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $formData = $this->normalizeCriteriaFormData($formData);
        if ($formData['type'] === 'CO_DINH') {
            $formData['max_point'] = $formData['fixed_point'];
        } elseif ($formData['type'] === 'CONG_THEO_LAN') {
            $formData['max_point'] = $formData['add_point'] * $formData['max_times'];
        } elseif ($formData['type'] === 'TRU_THEO_LAN') {
            $formData['max_point'] = $formData['deduct_point'] * $formData['max_times'];
        }

        $errors = $this->validateTemplateCriteria($formData);
        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        $categoryMaxPoints = $this->criteria->getCategoryMaxPoints($formData['category_id']);
        $currentSum = $this->criteria->sumCriteriaPointsByCategory($formData['category_id']);
        if ($currentSum + $formData['max_point'] > $categoryMaxPoints) {
            return [
                'formData' => $formData,
                'errors' => [
                    'max_point' => 'Tổng điểm tối đa của các tiêu chí con không được vượt quá điểm tối đa của danh mục cha (Hiện tại: ' . number_format($categoryMaxPoints, 2, ',', '.') . ' điểm)!',
                ],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $created = $this->criteria->createTemplateCriteria($formData);
            if ($created) {
                return [
                    'formData' => [],
                    'errors' => [],
                    'toast' => ['type' => 'success', 'message' => 'Tạo tiêu chí trong bộ mẫu thành công.'],
                    'redirect' => '?page=' . $redirectPage . '&template_id=' . $formData['template_id'],
                ];
            }

            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Tạo tiêu chí thất bại. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi tạo tiêu chí bộ mẫu. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function updateTemplateCriteria(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $formData = $this->normalizeCriteriaFormData($formData);
        $criteriaId = $this->normalizeInt($formData['criteria_id'] ?? 0);
        if ($criteriaId < 1) {
            return [
                'formData' => $formData,
                'errors' => ['criteria_id' => 'Vui lòng chọn tiêu chí.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        if ($formData['type'] === 'CO_DINH') {
            $formData['max_point'] = $formData['fixed_point'];
        } elseif ($formData['type'] === 'CONG_THEO_LAN') {
            $formData['max_point'] = $formData['add_point'] * $formData['max_times'];
        } elseif ($formData['type'] === 'TRU_THEO_LAN') {
            $formData['max_point'] = $formData['deduct_point'] * $formData['max_times'];
        }

        $errors = $this->validateTemplateCriteria($formData);
        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        $categoryMaxPoints = $this->criteria->getCategoryMaxPoints($formData['category_id']);
        $currentSum = $this->criteria->sumCriteriaPointsByCategory($formData['category_id'], $criteriaId);
        if ($currentSum + $formData['max_point'] > $categoryMaxPoints) {
            return [
                'formData' => $formData,
                'errors' => [
                    'max_point' => 'Tổng điểm tối đa của các tiêu chí con không được vượt quá điểm tối đa của danh mục cha (Hiện tại: ' . number_format($categoryMaxPoints, 2, ',', '.') . ' điểm)!',
                ],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $updated = $this->criteria->updateTemplateCriteria($criteriaId, $formData);
            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => $updated ? 'success' : 'info', 'message' => $updated ? 'Cập nhật tiêu chí thành công.' : 'Không có thay đổi nào.'],
                'redirect' => '?page=' . $redirectPage . '&template_id=' . $formData['template_id'],
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi cập nhật tiêu chí. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function deleteTemplateCriteria(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        $criteriaId = $this->normalizeInt($formData['criteria_id'] ?? 0);
        $templateId = $this->normalizeInt($formData['template_id'] ?? 0);

        if ($criteriaId < 1) {
            return [
                'formData' => $formData,
                'errors' => ['criteria_id' => 'Vui lòng chọn tiêu chí.'],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $deleted = $this->criteria->deleteTemplateCriteria($criteriaId);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => $deleted ? 'success' : 'info', 'message' => $deleted ? 'Xóa tiêu chí thành công.' : 'Không thể xóa tiêu chí này.'],
                'redirect' => '?page=' . $redirectPage . '&template_id=' . $templateId,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi xóa tiêu chí. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    public function applyTemplate(array $formData, string $redirectPage = 'setup_criteria'): array
    {
        error_log(print_r($_POST, true));

        $data = [
            'semester_id' => $this->normalizeInt($formData['semester_id'] ?? ''),
            'template_id' => $this->normalizeInt($formData['apply_template_id'] ?? $formData['template_id'] ?? ''),
            'MA_NIEN_KHOA' => $this->normalizeInt($formData['MA_NIEN_KHOA'] ?? ''),
        ];

        $errors = [];
        if ($data['semester_id'] < 1) {
            $errors['semester_id'] = 'Vui lòng chọn học kỳ để áp dụng bộ tiêu chí.';
        }
        if ($data['template_id'] < 1) {
            $errors['apply_template_id'] = 'Vui lòng chọn bộ tiêu chí mẫu để áp dụng.';
        }

        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            $username = $_SESSION['admin']['TEN_DANG_NHAP'] ?? ($_SESSION['admin']['username'] ?? '');
            $saved = $this->criteria->applyTemplateToSemester($data['semester_id'], $data['template_id'], $username);

            return [
                'formData' => [],
                'errors' => [],
                'toast' => ['type' => $saved ? 'success' : 'error', 'message' => $saved ? 'Áp dụng bộ tiêu chí mẫu thành công cho học kỳ.' : 'Không thể áp dụng bộ tiêu chí mẫu cho học kỳ.'],
                'redirect' => $saved ? '?page=' . $redirectPage . '&MA_NIEN_KHOA=' . $data['MA_NIEN_KHOA'] : null,
            ];
        } catch (\InvalidArgumentException $exception) {
            return [
                'formData' => $formData,
                'errors' => ['apply_template' => $exception->getMessage()],
                'toast' => null,
                'redirect' => null,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi áp dụng bộ tiêu chí. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    private function validateMasterTemplate(array $formData): array
    {
        $errors = [];

        if (trim((string) ($formData['template_name'] ?? '')) === '') {
            $errors['template_name'] = 'Tên bộ tiêu chí mẫu là bắt buộc.';
        }

        if (($formData['template_display_order'] ?? 0) < 0) {
            $errors['template_display_order'] = 'Thứ tự hiển thị phải là số nguyên không âm.';
        }

        return $errors;
    }

    private function validateTemplateCategory(array $formData): array
    {
        $errors = [];

        if (($formData['template_id'] ?? 0) < 1) {
            $errors['template_id'] = 'Vui lòng chọn bộ tiêu chí mẫu.';
        }

        if (trim((string) ($formData['category_name'] ?? '')) === '') {
            $errors['category_name'] = 'Tên danh mục là bắt buộc.';
        }

        if (($formData['category_max_points'] ?? 0) <= 0) {
            $errors['category_max_points'] = 'Điểm tối đa danh mục phải lớn hơn 0.';
        }

        if (($formData['category_display_order'] ?? 0) < 0) {
            $errors['category_display_order'] = 'Thứ tự hiển thị phải là số nguyên không âm.';
        }

        return $errors;
    }

    private function validateTemplateCriteria(array $formData): array
    {
        $errors = [];
        $allowedTypes = array_column($this->getCriteriaTypeOptions(), 'value');

        if ($formData['template_id'] < 1) {
            $errors['template_id'] = 'Vui lòng chọn bộ tiêu chí mẫu.';
        }

        if ($formData['category_id'] < 1) {
            $errors['category_id'] = 'Vui lòng chọn danh mục cho tiêu chí.';
        }

        if ($formData['name'] === '') {
            $errors['name'] = 'Tên tiêu chí là bắt buộc.';
        }

        if ($formData['description'] === '') {
            $errors['description'] = 'Mô tả tiêu chí là bắt buộc.';
        }

        if (!in_array($formData['type'], $allowedTypes, true)) {
            $errors['type'] = 'Loại tiêu chí không hợp lệ.';
        }

        if ($formData['display_order'] < 0) {
            $errors['display_order'] = 'Thứ tự hiển thị phải là số nguyên không âm.';
        }

        if (!in_array($formData['status'], array_column($this->getStatusOptions(), 'value'), true)) {
            $errors['status'] = 'Trạng thái tiêu chí không hợp lệ.';
        }

        switch ($formData['type']) {
            case 'CONG_THEO_LAN':
                if ($formData['add_point'] <= 0) {
                    $errors['add_point'] = 'Điểm cộng mỗi lần phải lớn hơn 0.';
                }
                if ($formData['max_times'] <= 0) {
                    $errors['max_times'] = 'Số lần tối đa phải lớn hơn 0.';
                }
                break;
            case 'TRU_THEO_LAN':
                if ($formData['deduct_point'] <= 0) {
                    $errors['deduct_point'] = 'Điểm trừ mỗi lần phải lớn hơn 0.';
                }
                if ($formData['max_times'] <= 0) {
                    $errors['max_times'] = 'Số lần tối đa phải lớn hơn 0.';
                }
                break;
            case 'CO_DINH':
                if ($formData['fixed_point'] <= 0) {
                    $errors['fixed_point'] = 'Điểm cố định phải lớn hơn 0.';
                }
                break;
        }

        return $errors;
    }

    private function normalizeCriteriaFormData(array $post): array
    {
        return [
            'template_id' => $this->normalizeInt($post['template_id'] ?? ''),
            'semester_id' => $this->normalizeInt($post['semester_id'] ?? ''),
            'MA_NIEN_KHOA' => $this->normalizeInt($post['MA_NIEN_KHOA'] ?? ''),
            'category_id' => $this->normalizeInt($post['category_id'] ?? ''),
            'category_name' => trim($post['category_name'] ?? ''),
            'category_max_points' => $this->normalizeDecimal($post['category_max_points'] ?? ''),
            'category_display_order' => $this->normalizeInt($post['category_display_order'] ?? ''),
            'name' => trim($post['name'] ?? ''),
            'description' => trim($post['description'] ?? ''),
            'type' => trim($post['type'] ?? ''),
            'fixed_point' => $this->normalizeDecimal($post['fixed_point'] ?? ''),
            'add_point' => $this->normalizeDecimal($post['add_point'] ?? ''),
            'deduct_point' => $this->normalizeDecimal($post['deduct_point'] ?? ''),
            'max_point' => $this->normalizeDecimal($post['max_point'] ?? ''),
            'max_times' => $this->normalizeInt($post['max_times'] ?? ''),
            'is_activity' => $this->normalizeBoolean($post['is_activity'] ?? ''),
            'use_for_activity' => $this->normalizeBoolean($post['use_for_activity'] ?? $post['is_activity'] ?? ''),
            'display_order' => $this->normalizeInt($post['display_order'] ?? ''),
            'status' => trim($post['status'] ?? '1'),
        ];
    }

    public function storeCriteria(array $formData, int $criteriaId = 0, string $redirectPage = 'list_criteria'): array
    {
        if ($formData['type'] === 'CONG_THEO_LAN') {
            // match frontend: add_point * max_times
            $formData['max_point'] = $formData['add_point'] * $formData['max_times'];
        }

        $errors = $this->validateCriteria($formData);
        if (!empty($errors)) {
            return [
                'formData' => $formData,
                'errors' => $errors,
                'toast' => null,
                'redirect' => null,
            ];
        }

        $categoryMaxPoints = $this->criteria->getCategoryMaxPoints($formData['category_id']);
        $currentSum = $this->criteria->sumCriteriaPointsByCategory($formData['category_id'], $criteriaId);
        if ($currentSum + $formData['max_point'] > $categoryMaxPoints) {
            return [
                'formData' => $formData,
                'errors' => [
                    'max_point' => 'Tổng điểm tối đa của các tiêu chí con không được vượt quá điểm tối đa của danh mục cha (Hiện tại: ' . number_format($categoryMaxPoints, 2, ',', '.') . ' điểm)!',
                ],
                'toast' => null,
                'redirect' => null,
            ];
        }

        try {
            if ($criteriaId > 0) {
                $updated = $this->criteria->updateCriteria($criteriaId, $formData);
                return [
                    'formData' => $formData,
                    'errors' => [],
                    'toast' => [
                        'type' => $updated ? 'success' : 'info',
                        'message' => $updated ? 'Cập nhật tiêu chí thành công.' : 'Không có thay đổi nào.',
                    ],
                    'redirect' => '?page=' . $redirectPage . '&semester_id=' . $formData['semester_id'],
                ];
            }

            $created = $this->criteria->createCriteria($formData);
            if ($created) {
                return [
                    'formData' => [],
                    'errors' => [],
                    'toast' => ['type' => 'success', 'message' => 'Tạo tiêu chí đánh giá thành công.'],
                    'redirect' => '?page=' . $redirectPage . '&semester_id=' . $formData['semester_id'],
                ];
            }

            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Tạo tiêu chí thất bại. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [
                'formData' => $formData,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi lưu tiêu chí. Vui lòng thử lại.'],
                'redirect' => null,
            ];
        }
    }

    private function validateCategory(array $formData): array
    {
        $errors = [];

        if ($formData['semester_id'] < 1) {
            $errors['category_semester_id'] = 'Vui lòng chọn học kỳ cho danh mục.';
        }

        if ($formData['category_name'] === '') {
            $errors['category_name'] = 'Tên danh mục là bắt buộc.';
        }

        if ($formData['category_max_points'] <= 0) {
            $errors['category_max_points'] = 'Điểm tối đa danh mục phải lớn hơn 0.';
        }

        if ($formData['category_display_order'] < 0) {
            $errors['category_display_order'] = 'Thứ tự hiển thị phải là số nguyên không âm.';
        }

        return $errors;
    }

    private function validateCriteria(array $formData): array
    {
        $errors = [];
        $allowedTypes = array_column($this->getCriteriaTypeOptions(), 'value');

        if ($formData['semester_id'] < 1) {
            $errors['semester_id'] = 'Vui lòng chọn học kỳ cho tiêu chí.';
        }

        if ($formData['category_id'] < 1) {
            $errors['category_id'] = 'Vui lòng chọn danh mục cho tiêu chí.';
        }

        if ($formData['name'] === '') {
            $errors['name'] = 'Tên tiêu chí là bắt buộc.';
        }

        if ($formData['description'] === '') {
            $errors['description'] = 'Mô tả tiêu chí là bắt buộc.';
        }

        if (!in_array($formData['type'], $allowedTypes, true)) {
            $errors['type'] = 'Loại tiêu chí không hợp lệ.';
        }

        if ($formData['display_order'] < 0) {
            $errors['display_order'] = 'Thứ tự hiển thị phải là số nguyên không âm.';
        }

        if (!in_array($formData['status'], array_column($this->getStatusOptions(), 'value'), true)) {
            $errors['status'] = 'Trạng thái tiêu chí không hợp lệ.';
        }

        switch ($formData['type']) {
            case 'CONG_THEO_LAN':
                if ($formData['fixed_point'] <= 0) {
                    $errors['fixed_point'] = 'Điểm cố định mỗi lần phải lớn hơn 0.';
                }
                if ($formData['max_times'] <= 0) {
                    $errors['max_times'] = 'Số lần tối đa phải lớn hơn 0.';
                }
                break;
            case 'TRU_THEO_LAN':
                if ($formData['deduct_point'] <= 0) {
                    $errors['deduct_point'] = 'Điểm trừ mỗi lần phải lớn hơn 0.';
                }
                if ($formData['max_point'] <= 0) {
                    $errors['max_point'] = 'Điểm tối đa phải lớn hơn 0.';
                }
                if ($formData['max_times'] <= 0) {
                    $errors['max_times'] = 'Số lần tối đa phải lớn hơn 0.';
                }
                break;
            case 'CO_DINH':
                if ($formData['max_point'] <= 0) {
                    $errors['max_point'] = 'Điểm cố định phải lớn hơn 0.';
                }
                break;
        }

        return $errors;
    }

    private function getStatusOptions(): array
    {
        return [
            ['value' => '1', 'label' => 'Hoạt động'],
            ['value' => '0', 'label' => 'Không hoạt động'],
        ];
    }

    private function getCriteriaTypeOptions(): array
    {
        return [
            ['value' => 'CONG_THEO_LAN', 'label' => 'Cộng theo lần'],
            ['value' => 'TRU_THEO_LAN', 'label' => 'Trừ theo lần'],
            ['value' => 'CO_DINH', 'label' => 'Cố định'],
        ];
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

    private function normalizeDecimal(mixed $value): float
    {
        $value = trim((string) $value);
        if ($value === '' || !is_numeric($value)) {
            return 0.0;
        }

        return (float) $value;
    }

    private function normalizeInt(mixed $value): int
    {
        $value = trim((string) $value);
        if ($value === '' || !is_numeric($value)) {
            return 0;
        }

        return (int) $value;
    }

    private function normalizeBoolean(mixed $value): bool
    {
        return in_array($value, [1, '1', 'true', 'on', true], true);
    }
}
