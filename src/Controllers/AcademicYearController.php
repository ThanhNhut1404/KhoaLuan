<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\AcademicYearModel;
use Throwable;

class AcademicYearController
{
    private const LIST_PER_PAGE = 10;

    private AcademicYearModel $years;

    public function __construct(?AcademicYearModel $years = null)
    {
        $this->years = $years ?? new AcademicYearModel(Database::getConnection());
    }

    public function create(array $data, string $method): array
    {
        $statusOptions = $this->years->getStatusOptions();
        $defaultStatus = $this->defaultStatusValue($statusOptions);
        $state = [
            'formData' => $method === 'POST' ? $data : ['status' => $defaultStatus],
            'errors' => [],
            'statusOptions' => $statusOptions,
            'toast' => null,
        ];

        if ($method !== 'POST') {
            return $state;
        }

        $state['formData'] = [
            'year_name' => $data['year_name'] ?? '',
            'start_date' => trim($data['start_date'] ?? ''),
            'end_date' => trim($data['end_date'] ?? ''),
            'status' => trim($data['status'] ?? '') ?: $defaultStatus,
        ];
        $state['errors'] = $this->validate($state['formData'], $statusOptions);

        if (!empty($state['errors'])) {
            return $state;
        }

        try {
            $created = $this->years->create([
                'year_name' => $this->normalizeYearName($state['formData']['year_name']),
                'start_date' => $state['formData']['start_date'],
                'end_date' => $state['formData']['end_date'],
                'status' => $state['formData']['status'],
            ]);
        } catch (Throwable $exception) {
            if ($this->years->isDuplicateException($exception)) {
                $state['errors']['year_name'] = 'Niên khóa đã tồn tại.';
                return $state;
            }

            $state['toast'] = [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi tạo niên khóa. Vui lòng thử lại.',
            ];

            return $state;
        }

        $state['toast'] = [
            'type' => $created ? 'success' : 'error',
            'message' => $created
                ? 'Tạo niên khóa thành công.'
                : 'Có lỗi xảy ra khi tạo niên khóa. Vui lòng thử lại.',
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
            'years' => [],
            'statusOptions' => $this->years->getListStatusOptions(),
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
            $state['toast'] = $this->handleListAction($data, $state['statusOptions']);
        }

        return $this->loadListState($state, $page);
    }

    public function canEdit(int $id): array
    {
        $year = $this->years->findById($id);
        if ($year === null) {
            return [
                'allowed' => false,
                'toast' => ['type' => 'error', 'message' => 'Dữ liệu đã được thay đổi bởi người dùng khác. Vui lòng tải lại danh sách.'],
            ];
        }

        if ($this->years->relatedDataExists($id)) {
            return [
                'allowed' => false,
                'toast' => ['type' => 'error', 'message' => 'Không thể chỉnh sửa niên khóa đã phát sinh dữ liệu.'],
            ];
        }

        return ['allowed' => true, 'toast' => null];
    }

    public function editState(int $id, array $data, string $method): array
    {
        $statusOptions = $this->years->getStatusOptions();
        $state = [
            'formData' => [],
            'errors' => [],
            'statusOptions' => $statusOptions,
            'toast' => null,
            'redirect' => null,
            'isEdit' => true,
        ];

        if ($id < 1) {
            $state['toast'] = ['type' => 'error', 'message' => 'Niên khóa không hợp lệ.'];
            $state['redirect'] = '?page=list_year';
            return $state;
        }

        $canEdit = $this->canEdit($id);
        if (!$canEdit['allowed']) {
            $state['toast'] = $canEdit['toast'];
            $state['redirect'] = '?page=list_year';
            return $state;
        }

        if ($method === 'POST') {
            $state['formData'] = [
                'year_name' => trim($data['year_name'] ?? ''),
                'start_date' => trim($data['start_date'] ?? ''),
                'end_date' => trim($data['end_date'] ?? ''),
                'status' => trim($data['status'] ?? ''),
            ];
            $state['errors'] = $this->validate($state['formData'], $statusOptions, $id);

            if (!empty($state['errors'])) {
                return $state;
            }

            try {
                $updated = $this->update($id, $state['formData']);
                $state['toast'] = [
                    'type' => $updated ? 'success' : 'error',
                    'message' => $updated ? 'Cập nhật niên khóa thành công.' : 'Không có thay đổi nào được thực hiện.',
                ];
                if ($updated) {
                    $state['redirect'] = '?page=list_year';
                }
            } catch (Throwable $exception) {
                $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi xảy ra khi cập nhật niên khóa.'];
            }

            return $state;
        }

        $year = $this->years->findById($id);
        if ($year === null) {
            $state['toast'] = ['type' => 'error', 'message' => 'Niên khóa không tồn tại.'];
            $state['redirect'] = '?page=list_year';
            return $state;
        }

        $state['formData'] = [
            'year_name' => $year['name'] ?? '',
            'start_date' => $year['start_date'] ?? '',
            'end_date' => $year['end_date'] ?? '',
            'status' => $year['status'] ?? '',
        ];

        return $state;
    }

    public function update(int $id, array $data): bool
    {
        $idColumn = $this->years->column('id');
        $nameColumn = $this->years->column('name');
        $startColumn = $this->years->column('start_date');
        $endColumn = $this->years->column('end_date');
        $statusColumn = $this->years->column('status', false);

        $columns = [
            sprintf('%s = :name', $nameColumn),
            sprintf('%s = :start_date', $startColumn),
            sprintf('%s = :end_date', $endColumn),
        ];
        $params = [
            'name' => $data['year_name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'id' => $id,
        ];

        if ($statusColumn !== null) {
            $columns[] = sprintf('%s = :status', $statusColumn);
            $params['status'] = $data['status'];
        }

        $statement = $this->years->getConnection()->prepare(
            sprintf('UPDATE nien_khoa SET %s WHERE %s = :id', implode(', ', $columns), $idColumn)
        );

        $statement->execute($params);

        return $statement->rowCount() > 0;
    }

    public function handle(string $page, array $post, array $get, string $method): array
    {
        $page = trim($page);

        if ($page === 'create_year') {
            return $this->create($post, $method) + ['page' => 'create_year'];
        }

        if ($page === 'list_year') {
            if ($method === 'POST' && ($post['action'] ?? '') === 'edit') {
                $editState = $this->canEdit((int) ($post['year_id'] ?? 0));
                if ($editState['allowed']) {
                    return ['redirect' => '?page=edit_year&id=' . (int) ($post['year_id'] ?? 0), 'toast' => null, 'page' => 'list_year'];
                }
                $state = $this->listing([], $get, 'GET');
                $state['toast'] = $editState['toast'];
                $state['page'] = 'list_year';
                return $state;
            }

            return $this->listing($post, $get, $method) + ['page' => 'list_year'];
        }

        if ($page === 'edit_year') {
            return $this->editState((int) ($get['id'] ?? 0), $post, $method) + ['page' => 'edit_year'];
        }

        return ['page' => $page, 'formData' => [], 'errors' => [], 'statusOptions' => [], 'toast' => null, 'years' => [], 'pagination' => [], 'redirect' => null];
    }

    private function validate(array $data, array $statusOptions, int $excludeId = 0): array
    {
        $errors = [];
        $rawYearName = $data['year_name'] ?? '';
        $yearName = trim($rawYearName);
        $normalizedYearName = $this->normalizeYearName($yearName);
        $statusValues = array_column($statusOptions, 'value');

        if ($rawYearName === '') {
            $errors['year_name'] = 'Tên niên khóa không được để trống.';
        } elseif ($yearName === '') {
            $errors['year_name'] = 'Tên niên khóa không được chỉ chứa khoảng trắng.';
        } elseif (!$this->hasValidYearFormat($yearName)) {
            $errors['year_name'] = 'Tên niên khóa không đúng định dạng.';
        } else {
            [$startYear, $endYear] = array_map('intval', explode('-', $normalizedYearName));

            if ($endYear !== $startYear + 1) {
                $errors['year_name'] = 'Niên khóa phải gồm hai năm liên tiếp.';
            } elseif ($excludeId > 0
                ? $this->years->normalizedNameExistsExcept($normalizedYearName, $excludeId)
                : $this->years->normalizedNameExists($normalizedYearName)) {
                $errors['year_name'] = 'Niên khóa đã tồn tại.';
            }
        }

        $maxNameLength = $this->years->maxNameLength();
        if ($maxNameLength !== null && $this->length($yearName) > $maxNameLength) {
            $errors['year_name'] = 'Tên niên khóa vượt quá số ký tự cho phép.';
        }

        if ($data['start_date'] === '') {
            $errors['start_date'] = 'Vui lòng chọn ngày bắt đầu.';
        } elseif (!$this->isValidDate($data['start_date'])) {
            $errors['start_date'] = 'Ngày bắt đầu không hợp lệ.';
        }

        if ($data['end_date'] === '') {
            $errors['end_date'] = 'Vui lòng chọn ngày kết thúc.';
        } elseif (!$this->isValidDate($data['end_date'])) {
            $errors['end_date'] = 'Ngày kết thúc không hợp lệ.';
        } elseif (!isset($errors['start_date']) && $data['end_date'] <= $data['start_date']) {
            $errors['end_date'] = $data['end_date'] === $data['start_date']
                ? 'Ngày kết thúc phải lớn hơn ngày bắt đầu.'
                : 'Ngày kết thúc phải sau ngày bắt đầu.';
        }

        if ($data['status'] === '') {
            $errors['status'] = 'Vui lòng chọn trạng thái.';
        } elseif (!in_array($data['status'], $statusValues, true)) {
            $errors['status'] = 'Vui lòng chọn trạng thái.';
        } elseif ($this->isActiveStatus($data['status'], $statusOptions)
            && $this->years->activeYearExists($this->activeStatusValues($statusOptions))) {
            $errors['status'] = 'Đã tồn tại một niên khóa đang hoạt động.';
        }

        if (!isset($errors['year_name'])
            && !isset($errors['start_date'])
            && !isset($errors['end_date'])
            && $normalizedYearName !== ''
            && $data['start_date'] !== ''
            && $data['end_date'] !== '') {
            [$startYear, $endYear] = array_map('intval', explode('-', $normalizedYearName));
            if ((int) date('Y', strtotime($data['start_date'])) !== $startYear
                || (int) date('Y', strtotime($data['end_date'])) !== $endYear) {
                $errors['year_name'] = 'Tên niên khóa không khớp với khoảng thời gian đã chọn.';
            }
        }

        if (!isset($errors['start_date'])
            && !isset($errors['end_date'])
            && $data['start_date'] !== ''
            && $data['end_date'] !== ''
            && $this->years->dateRangeOverlaps($data['start_date'], $data['end_date'])) {
            $errors['end_date'] = 'Khoảng thời gian của niên khóa bị trùng với niên khóa khác.';
        }

        return $errors;
    }

    private function loadListState(array $state, int $requestedPage): array
    {
        $totalItems = $this->years->countAll();
        $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
        $currentPage = min(max(1, $requestedPage), $totalPages);
        $years = $totalItems > 0 ? $this->years->listPaginated($currentPage, self::LIST_PER_PAGE) : [];

        $state['years'] = array_map(
            fn (array $year): array => $this->formatListRow($year, $state['statusOptions']),
            $years
        );
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

    private function handleListAction(array $data, array $statusOptions): ?array
    {
        $action = $data['action'] ?? '';
        $id = (int) ($data['year_id'] ?? 0);

        if ($id < 1) {
            return ['type' => 'error', 'message' => 'Dữ liệu đã được thay đổi bởi người dùng khác. Vui lòng tải lại danh sách.'];
        }

        try {
            return match ($action) {
                'update_status' => $this->updateListStatus($id, trim($data['status'] ?? ''), $statusOptions),
                'delete' => $this->deleteFromList($id, $statusOptions),
                default => ['type' => 'error', 'message' => 'Có lỗi xảy ra khi tải dữ liệu.'],
            };
        } catch (Throwable) {
            return ['type' => 'error', 'message' => 'Có lỗi xảy ra khi tải dữ liệu.'];
        }
    }

    private function updateListStatus(int $id, string $status, array $statusOptions): array
    {
        $year = $this->years->findById($id);
        if ($year === null) {
            return ['type' => 'error', 'message' => 'Dữ liệu đã được thay đổi bởi người dùng khác. Vui lòng tải lại danh sách.'];
        }

        if (!$this->isAllowedStatus($status, $statusOptions)) {
            return ['type' => 'error', 'message' => 'Vui lòng chọn trạng thái hợp lệ.'];
        }

        if ($this->isListActiveStatus($status, $statusOptions)
            && $this->years->activeYearExistsExcept($this->activeListStatusValues($statusOptions), $id)) {
            return ['type' => 'error', 'message' => 'Đã tồn tại một niên khóa đang hoạt động.'];
        }

        if (!$this->statusMatchesDateRange($status, $statusOptions, $year)) {
            return ['type' => 'error', 'message' => 'Trạng thái không phù hợp với khoảng thời gian của niên khóa.'];
        }

        if ((string) ($year['status'] ?? '') === $status) {
            return ['type' => 'success', 'message' => 'Cập nhật trạng thái thành công.'];
        }

        if (!$this->years->updateStatus($id, $status)) {
            return ['type' => 'error', 'message' => 'Dữ liệu đã được thay đổi bởi người dùng khác. Vui lòng tải lại danh sách.'];
        }

        return ['type' => 'success', 'message' => 'Cập nhật trạng thái thành công.'];
    }

    private function deleteFromList(int $id, array $statusOptions): array
    {
        $year = $this->years->findById($id);
        if ($year === null) {
            return ['type' => 'error', 'message' => 'Dữ liệu đã được thay đổi bởi người dùng khác. Vui lòng tải lại danh sách.'];
        }

        if ($this->isListActiveStatus((string) ($year['status'] ?? ''), $statusOptions)) {
            return ['type' => 'error', 'message' => 'Không thể xóa niên khóa đang hoạt động.'];
        }

        if ($this->years->relatedDataExists($id)) {
            return ['type' => 'error', 'message' => 'Không thể xóa niên khóa vì đã có dữ liệu liên quan.'];
        }

        try {
            if (!$this->years->deleteById($id)) {
                return ['type' => 'error', 'message' => 'Dữ liệu đã được thay đổi bởi người dùng khác. Vui lòng tải lại danh sách.'];
            }
        } catch (\PDOException $exception) {
            if ($exception->getCode() === '23000') {
                return ['type' => 'error', 'message' => 'Không thể xóa niên khóa vì đã có dữ liệu liên quan.'];
            }

            throw $exception;
        }

        return ['type' => 'success', 'message' => 'Xóa niên khóa thành công.'];
    }

    private function formatListRow(array $year, array $statusOptions): array
    {
        $startDate = $year['start_date'] ?? null;
        $endDate = $year['end_date'] ?? null;

        return [
            'id' => (int) $year['id'],
            'name' => $this->blank($year['name'] ?? null),
            'start_date' => $this->formatDate($startDate),
            'end_date' => $this->formatDate($endDate),
            'time' => $this->formatRange($startDate, $endDate),
            'semesters' => $year['semesters'] ?? null,
            'status' => (string) ($year['status'] ?? ''),
            'status_label' => $this->statusLabel((string) ($year['status'] ?? ''), $statusOptions),
        ];
    }

    private function formatDate(?string $date): string
    {
        if ($date === null || $date === '') {
            return '--';
        }

        $timestamp = strtotime($date);

        return $timestamp === false ? '--' : date('d/m/Y', $timestamp);
    }

    private function formatRange(?string $startDate, ?string $endDate): string
    {
        if (($startDate === null || $startDate === '') && ($endDate === null || $endDate === '')) {
            return '--';
        }

        return $this->formatDate($startDate) . ' - ' . $this->formatDate($endDate);
    }

    private function blank(mixed $value): string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? '--' : $value;
    }

    private function statusLabel(string $status, array $statusOptions): string
    {
        foreach ($statusOptions as $option) {
            if ($option['value'] === $status) {
                return $option['label'];
            }
        }

        return $status === '' ? '--' : $status;
    }

    private function isAllowedStatus(string $status, array $statusOptions): bool
    {
        return in_array($status, array_column($statusOptions, 'value'), true);
    }

    private function statusMatchesDateRange(string $status, array $statusOptions, array $year): bool
    {
        $label = $this->normalizedStatusLabel($status, $statusOptions);
        $startDate = $year['start_date'] ?? '';
        $endDate = $year['end_date'] ?? '';

        if ($startDate === '' || $endDate === '') {
            return $label !== 'dang hoat dong';
        }

        $today = new \DateTimeImmutable('today');
        $start = $this->parseDate($startDate);
        $end = $this->parseDate($endDate);

        if ($start === false || $end === false) {
            return $label !== 'dang hoat dong';
        }

        return match ($label) {
            'sap dien ra' => $today < $start,
            'dang hoat dong' => $today >= $start && $today <= $end,
            'da hoan thanh' => $today > $end,
            default => true,
        };
    }

    private function activeListStatusValues(array $statusOptions): array
    {
        $values = array_values(array_map(
            fn (array $option): string => $option['value'],
            array_filter(
                $statusOptions,
                fn (array $option): bool => $this->matchesActiveText($option['value'])
                    || $this->matchesActiveText($option['label'] ?? $option['value'])
            )
        ));

        return array_values(array_unique(array_merge($values, [
            'active',
            'Đang diễn ra',
            'Đang hoạt động',
            'dang_dien_ra',
            'dang_hoat_dong',
        ])));
    }

    private function parseDate(string $value): \DateTimeImmutable|false
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
        if ($date !== false) {
            return $date;
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (\Throwable) {
            return false;
        }
    }

    private function isListActiveStatus(string $value, array $statusOptions): bool
    {
        return $this->matchesActiveText($value) || $this->matchesActiveText($this->statusLabel($value, $statusOptions));
    }

    private function normalizedStatusLabel(string $status, array $statusOptions): string
    {
        $label = $this->statusLabel($status, $statusOptions);
        $normalized = strtolower($this->removeVietnameseAccents($label));

        return match ($normalized) {
            'sap toi', 'sap dien ra', 'upcoming' => 'sap dien ra',
            'dang dien ra', 'dang hoat dong', 'active' => 'dang hoat dong',
            'da ket thuc', 'da hoan thanh', 'completed' => 'da hoan thanh',
            default => $normalized,
        };
    }

    private function hasValidYearFormat(string $yearName): bool
    {
        return preg_match('/^\d{4}\s*-\s*\d{4}$/', $yearName) === 1;
    }

    private function normalizeYearName(string $yearName): string
    {
        return preg_replace('/\s+/', '', trim($yearName));
    }

    private function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    }

    private function isValidDate(string $value): bool
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);

        return $date !== false && $date->format('Y-m-d') === $value;
    }

    private function isActiveStatus(string $value, array $statusOptions): bool
    {
        foreach ($statusOptions as $option) {
            if ($option['value'] === $value) {
                return $this->matchesActiveText($option['value']) || $this->matchesActiveText($option['label']);
            }
        }

        return false;
    }

    private function activeStatusValues(array $statusOptions): array
    {
        return array_values(array_map(
            fn (array $option): string => $option['value'],
            array_filter($statusOptions, fn (array $option): bool => $this->isActiveStatus($option['value'], $statusOptions))
        ));
    }

    private function matchesActiveText(string $text): bool
    {
        $normalized = strtolower($this->removeVietnameseAccents(trim($text)));

        return in_array($normalized, ['active', 'dang dien ra', 'dang hoat dong'], true);
    }

    private function defaultStatusValue(array $statusOptions): string
    {
        foreach ($statusOptions as $option) {
            if (($option['label'] ?? '') === 'Sắp diễn ra') {
                return $option['value'];
            }
        }

        return $statusOptions[0]['value'] ?? 'Sắp diễn ra';
    }

    private function removeVietnameseAccents(string $value): string
    {
        $value = function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
        $search = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ', 'Đ',
        ];
        $replace = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd', 'd',
        ];

        return str_replace($search, $replace, $value);
    }
}
