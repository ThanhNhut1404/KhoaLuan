<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;

class AcademicYearModel
{
    private array $columns;
    private array $tableColumnCache = [];

    public function __construct(private PDO $db)
    {
        $this->columns = $this->loadColumns();
    }

    public function getStatusOptions(): array
    {
        $statusColumn = $this->column('status', false);
        if ($statusColumn === null) {
            return $this->defaultStatusOptions();
        }

        $type = $this->columns[$statusColumn]['Type'] ?? '';
        if (preg_match("/^enum\((.*)\)$/i", $type, $matches)) {
            $values = str_getcsv($matches[1], ',', "'");

            return $this->formatStatusOptions($values);
        }

        $statement = $this->db->query(
            sprintf(
                'SELECT DISTINCT %s AS status FROM nien_khoa WHERE %s IS NOT NULL AND %s <> ""',
                $statusColumn,
                $statusColumn,
                $statusColumn
            )
        );
        $values = array_column($statement->fetchAll(), 'status');

        return $this->formatStatusOptions($values ?: array_column($this->defaultStatusOptions(), 'value'));
    }

    public function getListStatusOptions(): array
    {
        $statusColumn = $this->column('status', false);
        if ($statusColumn === null) {
            return $this->defaultListStatusOptions();
        }

        $type = $this->columns[$statusColumn]['Type'] ?? '';
        if (preg_match("/^enum\((.*)\)$/i", $type, $matches)) {
            $values = str_getcsv($matches[1], ',', "'");

            return $this->formatListStatusOptions($values);
        }

        $statement = $this->db->query(
            sprintf(
                'SELECT DISTINCT %s AS status FROM nien_khoa WHERE %s IS NOT NULL AND %s <> ""',
                $statusColumn,
                $statusColumn,
                $statusColumn
            )
        );
        $values = array_column($statement->fetchAll(), 'status');

        return $this->formatListStatusOptions($values ?: array_column($this->defaultListStatusOptions(), 'value'));
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM nien_khoa')->fetchColumn();
    }

    public function countFiltered(string $keyword = '', string $status = ''): int
    {
        [$where, $params] = $this->filterClause($keyword, $status);
        $statement = $this->db->prepare('SELECT COUNT(*) FROM nien_khoa nk' . $where);
        $this->bindNamedParams($statement, $params);
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $idColumn = $this->column('id');
        $nameColumn = $this->column('name');
        $startColumn = $this->column('start_date');
        $endColumn = $this->column('end_date');
        $statusColumn = $this->column('status', false);
        $offset = max(0, ($page - 1) * $perPage);

        $statusSelect = $statusColumn !== null ? sprintf('nk.%s AS status', $statusColumn) : 'NULL AS status';
        $semesterJoin = $this->hasColumn('hoc_ky', 'MA_NIEN_KHOA')
            ? 'LEFT JOIN hoc_ky hk ON hk.MA_NIEN_KHOA = nk.' . $idColumn
            : '';
        $semesterCount = $semesterJoin !== '' ? 'COUNT(hk.MA_NIEN_KHOA)' : '0';

        $statement = $this->db->prepare(
            sprintf(
                'SELECT nk.%s AS id,
                        nk.%s AS name,
                        nk.%s AS start_date,
                        nk.%s AS end_date,
                        %s,
                        %s AS semesters
                 FROM nien_khoa nk
                 %s
                 GROUP BY nk.%s, nk.%s, nk.%s, nk.%s%s
                 ORDER BY nk.%s DESC
                 LIMIT :limit OFFSET :offset',
                $idColumn,
                $nameColumn,
                $startColumn,
                $endColumn,
                $statusSelect,
                $semesterCount,
                $semesterJoin,
                $idColumn,
                $nameColumn,
                $startColumn,
                $endColumn,
                $statusColumn !== null ? ', nk.' . $statusColumn : '',
                $idColumn
            )
        );
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function listFilteredPaginated(int $page, int $perPage, string $keyword = '', string $status = ''): array
    {
        $idColumn = $this->column('id');
        $nameColumn = $this->column('name');
        $startColumn = $this->column('start_date');
        $endColumn = $this->column('end_date');
        $statusColumn = $this->column('status', false);
        $offset = max(0, ($page - 1) * $perPage);

        [$where, $params] = $this->filterClause($keyword, $status);
        $statusSelect = $statusColumn !== null ? sprintf('nk.%s AS status', $statusColumn) : 'NULL AS status';
        $semesterJoin = $this->hasColumn('hoc_ky', 'MA_NIEN_KHOA')
            ? 'LEFT JOIN hoc_ky hk ON hk.MA_NIEN_KHOA = nk.' . $idColumn
            : '';
        $semesterCount = $semesterJoin !== '' ? 'COUNT(hk.MA_NIEN_KHOA)' : '0';

        $statement = $this->db->prepare(
            sprintf(
                'SELECT nk.%s AS id,
                        nk.%s AS name,
                        nk.%s AS start_date,
                        nk.%s AS end_date,
                        %s,
                        %s AS semesters
                 FROM nien_khoa nk
                 %s
                 %s
                 GROUP BY nk.%s, nk.%s, nk.%s, nk.%s%s
                 ORDER BY nk.%s DESC
                 LIMIT :limit OFFSET :offset',
                $idColumn,
                $nameColumn,
                $startColumn,
                $endColumn,
                $statusSelect,
                $semesterCount,
                $semesterJoin,
                $where,
                $idColumn,
                $nameColumn,
                $startColumn,
                $endColumn,
                $statusColumn !== null ? ', nk.' . $statusColumn : '',
                $idColumn
            )
        );

        $this->bindNamedParams($statement, $params);

        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $idColumn = $this->column('id');
        $nameColumn = $this->column('name');
        $startColumn = $this->column('start_date');
        $endColumn = $this->column('end_date');
        $statusColumn = $this->column('status', false);
        $statusSelect = $statusColumn !== null ? sprintf('%s AS status', $statusColumn) : 'NULL AS status';

        $statement = $this->db->prepare(
            sprintf(
                'SELECT %s AS id, %s AS name, %s AS start_date, %s AS end_date, %s
                 FROM nien_khoa
                 WHERE %s = :id
                 LIMIT 1',
                $idColumn,
                $nameColumn,
                $startColumn,
                $endColumn,
                $statusSelect,
                $idColumn
            )
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function allForStatusSync(): array
    {
        $idColumn = $this->column('id');
        $startColumn = $this->column('start_date');
        $endColumn = $this->column('end_date');
        $statusColumn = $this->column('status', false);

        if ($statusColumn === null) {
            return [];
        }

        $statement = $this->db->query(
            sprintf(
                'SELECT %s AS id, %s AS start_date, %s AS end_date, %s AS status FROM nien_khoa',
                $idColumn,
                $startColumn,
                $endColumn,
                $statusColumn
            )
        );

        return $statement->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $idColumn = $this->column('id');
        $statusColumn = $this->column('status');
        $statement = $this->db->prepare(
            sprintf('UPDATE nien_khoa SET %s = :status WHERE %s = :id', $statusColumn, $idColumn)
        );
        $statement->execute([
            'status' => $status,
            'id' => $id,
        ]);

        return $statement->rowCount() > 0;
    }

    public function deleteById(int $id): bool
    {
        $idColumn = $this->column('id');
        $statement = $this->db->prepare(sprintf('DELETE FROM nien_khoa WHERE %s = :id', $idColumn));
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    public function relatedDataExists(int $id): bool
    {
        foreach ($this->relatedChecks() as [$table, $column]) {
            if (!$this->hasColumn($table, $column)) {
                continue;
            }

            $statement = $this->db->prepare(sprintf('SELECT 1 FROM %s WHERE %s = :id LIMIT 1', $table, $column));
            $statement->execute(['id' => $id]);

            if ($statement->fetchColumn()) {
                return true;
            }
        }

        return false;
    }

    public function hasStatusColumn(): bool
    {
        return $this->column('status', false) !== null;
    }

    public function maxNameLength(): ?int
    {
        $nameColumn = $this->column('name');
        $type = $this->columns[$nameColumn]['Type'] ?? '';

        return preg_match('/^(?:var)?char\((\d+)\)/i', $type, $matches) ? (int) $matches[1] : null;
    }

    public function normalizedNameExists(string $normalizedName): bool
    {
        $nameColumn = $this->column('name');
        $statement = $this->db->prepare(
            sprintf("SELECT 1 FROM nien_khoa WHERE REPLACE(%s, ' ', '') = :name LIMIT 1", $nameColumn)
        );
        $statement->execute(['name' => $normalizedName]);

        return (bool) $statement->fetchColumn();
    }

    public function normalizedNameExistsExcept(string $normalizedName, int $exceptId): bool
    {
        $nameColumn = $this->column('name');
        $idColumn = $this->column('id');
        $statement = $this->db->prepare(
            sprintf(
                "SELECT 1 FROM nien_khoa WHERE REPLACE(%s, ' ', '') = :name AND %s <> :except LIMIT 1",
                $nameColumn,
                $idColumn
            )
        );
        $statement->execute(['name' => $normalizedName, 'except' => $exceptId]);

        return (bool) $statement->fetchColumn();
    }

    public function activeYearExists(array $activeValues): bool
    {
        $statusColumn = $this->column('status', false);
        if ($statusColumn === null || empty($activeValues)) {
            return false;
        }

        $placeholders = [];
        $params = [];
        foreach (array_values($activeValues) as $index => $value) {
            $key = 'status_' . $index;
            $placeholders[] = ':' . $key;
            $params[$key] = $value;
        }

        $statement = $this->db->prepare(
            sprintf('SELECT 1 FROM nien_khoa WHERE %s IN (%s) LIMIT 1', $statusColumn, implode(', ', $placeholders))
        );
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }

    public function activeYearExistsExcept(array $activeValues, int $exceptId): bool
    {
        $statusColumn = $this->column('status', false);
        $idColumn = $this->column('id');
        if ($statusColumn === null || empty($activeValues)) {
            return false;
        }

        $placeholders = [];
        $params = ['except_id' => $exceptId];
        foreach (array_values($activeValues) as $index => $value) {
            $key = 'status_' . $index;
            $placeholders[] = ':' . $key;
            $params[$key] = $value;
        }

        $statement = $this->db->prepare(
            sprintf(
                'SELECT 1 FROM nien_khoa WHERE %s IN (%s) AND %s <> :except_id LIMIT 1',
                $statusColumn,
                implode(', ', $placeholders),
                $idColumn
            )
        );
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }

    public function dateRangeOverlaps(string $startDate, string $endDate, int $exceptId = 0): bool
    {
        $startColumn = $this->column('start_date');
        $endColumn = $this->column('end_date');
        $idColumn = $this->column('id');
        $exceptClause = $exceptId > 0 ? sprintf(' AND %s <> :except_id', $idColumn) : '';
        $statement = $this->db->prepare(
            sprintf(
                'SELECT 1 FROM nien_khoa WHERE %s <= :end_date AND %s >= :start_date%s LIMIT 1',
                $startColumn,
                $endColumn,
                $exceptClause
            )
        );
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        if ($exceptId > 0) {
            $params['except_id'] = $exceptId;
        }
        $statement->execute($params);

        return (bool) $statement->fetchColumn();
    }

    public function create(array $data): bool
    {
        $idColumn = $this->column('id', false);
        $nameColumn = $this->column('name');
        $startColumn = $this->column('start_date');
        $endColumn = $this->column('end_date');
        $statusColumn = $this->column('status', false);

        $columns = [$nameColumn, $startColumn, $endColumn];
        $params = [
            'name' => $data['year_name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ];
        $placeholders = [':name', ':start_date', ':end_date'];

        if ($statusColumn !== null) {
            $columns[] = $statusColumn;
            $placeholders[] = ':status';
            $params['status'] = $data['status'];
        }

        if ($idColumn !== null && !$this->isAutoIncrement($idColumn)) {
            array_unshift($columns, $idColumn);
            array_unshift($placeholders, ':id');
            $params['id'] = $this->nextId($idColumn);
        }

        $statement = $this->db->prepare(
            sprintf(
                'INSERT INTO nien_khoa (%s) VALUES (%s)',
                implode(', ', $columns),
                implode(', ', $placeholders)
            )
        );

        return $statement->execute($params);
    }

    public function isDuplicateException(\Throwable $exception): bool
    {
        return $exception instanceof \PDOException && $exception->getCode() === '23000';
    }

    public function getConnection(): \PDO
    {
        return $this->db;
    }

    public function getColumn(string $type, bool $required = true): ?string
    {
        return $this->column($type, $required);
    }

    private function loadColumns(): array
    {
        $columns = [];
        $statement = $this->db->query('SHOW COLUMNS FROM nien_khoa');

        foreach ($statement->fetchAll() as $column) {
            $columns[$column['Field']] = $column;
        }

        return $columns;
    }

    public function column(string $type, bool $required = true): ?string
    {
        $candidates = [
            'id' => ['MA_NIEN_KHOA'],
            'name' => ['TEN_NIEN_KHOA'],
            'start_date' => ['THOI_GIAN_BDNK', 'NGAY_BAT_DAU', 'NGAY_BD', 'THOI_GIAN_BD'],
            'end_date' => ['THOI_GIAN_KTNK', 'NGAY_KET_THUC', 'NGAY_KT', 'THOI_GIAN_KT'],
            'status' => ['TRANG_THAI_NK', 'TRANG_THAI', 'STATUS'],
        ];

        foreach ($candidates[$type] ?? [] as $column) {
            if (array_key_exists($column, $this->columns)) {
                return $column;
            }
        }

        if ($required) {
            throw new \RuntimeException('Cấu trúc bảng niên khóa chưa phù hợp.');
        }

        return null;
    }

    private function isAutoIncrement(string $column): bool
    {
        return str_contains(strtolower($this->columns[$column]['Extra'] ?? ''), 'auto_increment');
    }

    private function hasColumn(string $table, string $column): bool
    {
        if (!isset($this->tableColumnCache[$table])) {
            try {
                $statement = $this->db->query('SHOW COLUMNS FROM ' . $table);
                $this->tableColumnCache[$table] = array_column($statement->fetchAll(), 'Field');
            } catch (\Throwable) {
                $this->tableColumnCache[$table] = [];
            }
        }

        return in_array($column, $this->tableColumnCache[$table], true);
    }

    private function relatedChecks(): array
    {
        return [
            ['hoc_ky', 'MA_NIEN_KHOA'],
            ['lop_hoc', 'MA_NIEN_KHOA'],
            ['hoat_dong', 'MA_NIEN_KHOA'],
            ['bang_drl', 'MA_NIEN_KHOA'],
            ['sinh_vien', 'MA_NIEN_KHOA'],
        ];
    }

    private function filterClause(string $keyword, string $status = ''): array
    {
        $conditions = [];
        $params = [];
        $keyword = trim($keyword);

        if ($keyword !== '') {
            $nameColumn = $this->column('name');
            $startColumn = $this->column('start_date');
            $endColumn = $this->column('end_date');
            $conditions[] = sprintf(
                '(nk.%s LIKE :keyword_name
                    OR DATE_FORMAT(nk.%s, "%%Y-%%m-%%d") LIKE :keyword_start_iso
                    OR DATE_FORMAT(nk.%s, "%%d/%%m/%%Y") LIKE :keyword_start_display
                    OR DATE_FORMAT(nk.%s, "%%Y-%%m-%%d") LIKE :keyword_end_iso
                    OR DATE_FORMAT(nk.%s, "%%d/%%m/%%Y") LIKE :keyword_end_display)',
                $nameColumn,
                $startColumn,
                $startColumn,
                $endColumn,
                $endColumn
            );
            $keywordParam = '%' . $keyword . '%';
            $params['keyword_name'] = $keywordParam;
            $params['keyword_start_iso'] = $keywordParam;
            $params['keyword_start_display'] = $keywordParam;
            $params['keyword_end_iso'] = $keywordParam;
            $params['keyword_end_display'] = $keywordParam;
        }

        $status = trim($status);
        $statusColumn = $this->column('status', false);
        if ($statusColumn !== null) {
            if ($status !== '') {
                $conditions[] = sprintf('nk.%s = :status', $statusColumn);
                $params['status'] = $status;
            }
        }

        return [empty($conditions) ? '' : ' WHERE ' . implode(' AND ', $conditions), $params];
    }

    private function bindNamedParams(\PDOStatement $statement, array $params): void
    {
        foreach ($params as $name => $value) {
            $placeholder = ':' . ltrim((string) $name, ':');
            $statement->bindValue($placeholder, $value);
        }
    }

    private function nextId(string $column): int
    {
        return (int) $this->db->query(sprintf('SELECT COALESCE(MAX(%s), 0) + 1 FROM nien_khoa', $column))->fetchColumn();
    }

    private function defaultStatusOptions(): array
    {
        return [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
            ['value' => 'Đã kết thúc', 'label' => 'Đã kết thúc'],
        ];
    }

    private function defaultListStatusOptions(): array
    {
        return [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang hoạt động', 'label' => 'Đang hoạt động'],
            ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
        ];
    }

    private function formatStatusOptions(array $values): array
    {
        $labels = [
            'upcoming' => 'Sắp diễn ra',
            'active' => 'Đang diễn ra',
            'completed' => 'Đã kết thúc',
            'sap_dien_ra' => 'Sắp diễn ra',
            'dang_dien_ra' => 'Đang diễn ra',
            'da_ket_thuc' => 'Đã kết thúc',
            'Sắp tới' => 'Sắp diễn ra',
            'Sắp diễn ra' => 'Sắp diễn ra',
            'Đang diễn ra' => 'Đang diễn ra',
            'Đã hoàn thành' => 'Đã kết thúc',
            'Đã kết thúc' => 'Đã kết thúc',
        ];

        $options = array_values(array_map(
            fn (string $value): array => ['value' => $value, 'label' => $labels[$value] ?? $value],
            array_unique(array_filter($values, fn ($value): bool => $value !== null && $value !== ''))
        ));

        foreach ($this->defaultStatusOptions() as $defaultOption) {
            $hasLabel = false;
            foreach ($options as $option) {
                if ($option['label'] === $defaultOption['label']) {
                    $hasLabel = true;
                    break;
                }
            }

            if (!$hasLabel) {
                $options[] = $defaultOption;
            }
        }

        $uniqueOptions = [];
        foreach ($options as $option) {
            $uniqueOptions[$option['label']] ??= $option;
        }
        $options = array_values($uniqueOptions);

        usort($options, function (array $first, array $second): int {
            $order = ['Sắp diễn ra' => 0, 'Đang diễn ra' => 1, 'Đã kết thúc' => 2];

            return ($order[$first['label']] ?? 99) <=> ($order[$second['label']] ?? 99);
        });

        return $options ?: $this->defaultStatusOptions();
    }

    private function formatListStatusOptions(array $values): array
    {
        $labels = [
            'upcoming' => 'Sắp diễn ra',
            'active' => 'Đang hoạt động',
            'completed' => 'Đã hoàn thành',
            'sap_dien_ra' => 'Sắp diễn ra',
            'dang_dien_ra' => 'Đang hoạt động',
            'dang_hoat_dong' => 'Đang hoạt động',
            'da_ket_thuc' => 'Đã hoàn thành',
            'da_hoan_thanh' => 'Đã hoàn thành',
            'Sắp tới' => 'Sắp diễn ra',
            'Sắp diễn ra' => 'Sắp diễn ra',
            'Đang diễn ra' => 'Đang hoạt động',
            'Đang hoạt động' => 'Đang hoạt động',
            'Đã kết thúc' => 'Đã hoàn thành',
            'Đã hoàn thành' => 'Đã hoàn thành',
        ];

        $options = array_values(array_map(
            fn (string $value): array => ['value' => $value, 'label' => $labels[$value] ?? $value],
            array_unique(array_filter($values, fn ($value): bool => $value !== null && $value !== ''))
        ));

        foreach ($this->defaultListStatusOptions() as $defaultOption) {
            $hasLabel = false;
            foreach ($options as $option) {
                if ($option['label'] === $defaultOption['label']) {
                    $hasLabel = true;
                    break;
                }
            }

            if (!$hasLabel) {
                $options[] = $defaultOption;
            }
        }

        $uniqueOptions = [];
        foreach ($options as $option) {
            $uniqueOptions[$option['label']] ??= $option;
        }
        $options = array_values($uniqueOptions);

        usort($options, function (array $first, array $second): int {
            $order = ['Sắp diễn ra' => 0, 'Đang hoạt động' => 1, 'Đã hoàn thành' => 2];

            return ($order[$first['label']] ?? 99) <=> ($order[$second['label']] ?? 99);
        });

        return $options ?: $this->defaultListStatusOptions();
    }
}
