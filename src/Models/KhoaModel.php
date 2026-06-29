<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;
use PDOException;

class KhoaModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO khoa_bo_mon (TEN_VIET_TAT_KHOA, TEN_KHOA, EMAIL_KHOA, SO_DIEN_THOAI_KHOA) VALUES (:ma, :ten, :email, :phone)';
        $startedTransaction = !$this->db->inTransaction();

        if ($startedTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $stmt = $this->db->prepare($sql);
            $created = $stmt->execute([
                'ma' => $data['ma_khoa'],
                'ten' => $data['ten_khoa'],
                'email' => ($data['email_khoa'] ?? '') === '' ? null : $data['email_khoa'],
                'phone' => ($data['so_dien_thoai_khoa'] ?? '') === '' ? null : $data['so_dien_thoai_khoa'],
            ]);

            if (!$created || $stmt->rowCount() < 1) {
                if ($startedTransaction) {
                    $this->db->rollBack();
                }

                return false;
            }

            if ($startedTransaction) {
                $this->db->commit();
            }

            return true;
        } catch (\Throwable $exception) {
            if ($startedTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }

    public function existsByMa(string $ma): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM khoa_bo_mon WHERE TRIM(CAST(MA_KHOA AS CHAR)) = :ma LIMIT 1');
        $stmt->execute(['ma' => $ma]);
        return (bool) $stmt->fetchColumn();
    }

    public function existsByAbbreviation(string $abbreviation): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM khoa_bo_mon WHERE UPPER(TRIM(TEN_VIET_TAT_KHOA)) = :ma LIMIT 1');
        $stmt->execute(['ma' => $this->normalizeAbbreviation($abbreviation)]);
        return (bool) $stmt->fetchColumn();
    }

    public function existsByAbbreviationExceptMa(string $abbreviation, string $ma): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1
             FROM khoa_bo_mon
             WHERE UPPER(TRIM(TEN_VIET_TAT_KHOA)) = :ten_viet_tat
                AND TRIM(CAST(MA_KHOA AS CHAR)) <> :ma
             LIMIT 1'
        );
        $stmt->execute([
            'ten_viet_tat' => $this->normalizeAbbreviation($abbreviation),
            'ma' => $ma,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function existsByName(string $name): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1
             FROM khoa_bo_mon
             WHERE LOWER(REPLACE(TRIM(TEN_KHOA), " ", "")) = :ten
             LIMIT 1'
        );
        $stmt->execute(['ten' => $this->normalizeNameForLookup($name)]);
        return (bool) $stmt->fetchColumn();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT MA_KHOA AS ma, TEN_VIET_TAT_KHOA AS ten_viet_tat, TEN_KHOA AS ten, EMAIL_KHOA AS email, SO_DIEN_THOAI_KHOA AS phone FROM khoa_bo_mon ORDER BY MA_KHOA DESC');
        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM khoa_bo_mon');
        return (int) $stmt->fetchColumn();
    }

    public function countFiltered(string $keyword = ''): int
    {
        [$where, $params] = $this->filterClause($keyword);
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM khoa_bo_mon' . $where);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = $this->db->prepare('SELECT MA_KHOA AS ma, TEN_VIET_TAT_KHOA AS ten_viet_tat, TEN_KHOA AS ten, EMAIL_KHOA AS email, SO_DIEN_THOAI_KHOA AS phone FROM khoa_bo_mon ORDER BY MA_KHOA DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listFilteredPaginated(int $page, int $perPage, string $keyword = ''): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        [$where, $params] = $this->filterClause($keyword);
        $stmt = $this->db->prepare(
            'SELECT MA_KHOA AS ma, TEN_VIET_TAT_KHOA AS ten_viet_tat, TEN_KHOA AS ten, EMAIL_KHOA AS email, SO_DIEN_THOAI_KHOA AS phone
             FROM khoa_bo_mon'
             . $where .
            ' ORDER BY MA_KHOA DESC
             LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $name => $value) {
            $stmt->bindValue(':' . ltrim((string) $name, ':'), $value);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findByMa(string $ma): ?array
    {
        $stmt = $this->db->prepare('SELECT MA_KHOA AS ma, TEN_VIET_TAT_KHOA AS ten_viet_tat, TEN_KHOA AS ten, EMAIL_KHOA AS email, SO_DIEN_THOAI_KHOA AS phone FROM khoa_bo_mon WHERE TRIM(CAST(MA_KHOA AS CHAR)) = :ma LIMIT 1');
        $stmt->execute(['ma' => $ma]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(string $originalMa, array $data): bool
    {
        $sql = 'UPDATE khoa_bo_mon SET TEN_VIET_TAT_KHOA = :ten_viet_tat, TEN_KHOA = :ten, EMAIL_KHOA = :email, SO_DIEN_THOAI_KHOA = :phone WHERE MA_KHOA = :ma';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'ten_viet_tat' => $data['ma_khoa'],
            'ten' => $data['ten_khoa'],
            'email' => $data['email_khoa'] ?? null,
            'phone' => $data['so_dien_thoai_khoa'] ?? null,
            'ma' => $originalMa,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function deleteByMa(string $ma): bool
    {
        $stmt = $this->db->prepare('DELETE FROM khoa_bo_mon WHERE MA_KHOA = :ma');
        $stmt->execute(['ma' => $ma]);
        return $stmt->rowCount() > 0;
    }

    public function isDuplicateException(\Throwable $exception): bool
    {
        return $exception instanceof PDOException && $exception->getCode() === '23000';
    }

    private function filterClause(string $keyword): array
    {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return ['', []];
        }

        $textKeyword = function_exists('mb_strtolower') ? mb_strtolower($keyword, 'UTF-8') : strtolower($keyword);

        return [
            ' WHERE CAST(MA_KHOA AS CHAR) LIKE :keyword_ma
                OR LOWER(TEN_VIET_TAT_KHOA) LIKE :keyword_ten_viet_tat
                OR LOWER(TEN_KHOA) LIKE :keyword_ten
                OR LOWER(EMAIL_KHOA) LIKE :keyword_email
                OR CAST(SO_DIEN_THOAI_KHOA AS CHAR) LIKE :keyword_phone',
            [
                'keyword_ma' => '%' . $keyword . '%',
                'keyword_ten_viet_tat' => '%' . $textKeyword . '%',
                'keyword_ten' => '%' . $textKeyword . '%',
                'keyword_email' => '%' . $textKeyword . '%',
                'keyword_phone' => '%' . $keyword . '%',
            ],
        ];
    }

    private function normalizeNameForLookup(string $name): string
    {
        $name = preg_replace('/\s+/u', '', trim($name));

        return function_exists('mb_strtolower') ? mb_strtolower($name, 'UTF-8') : strtolower($name);
    }

    private function normalizeAbbreviation(string $abbreviation): string
    {
        $abbreviation = trim($abbreviation);

        return function_exists('mb_strtoupper') ? mb_strtoupper($abbreviation, 'UTF-8') : strtoupper($abbreviation);
    }
}
