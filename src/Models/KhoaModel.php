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
        $sql = 'INSERT INTO khoa_bo_mon (MA_KHOA, TEN_KHOA, EMAIL_KHOA, SO_DIEN_THOAI_KHOA) VALUES (:ma, :ten, :email, :phone)';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'ma' => $data['ma_khoa'],
            'ten' => $data['ten_khoa'],
            'email' => $data['email_khoa'] ?? null,
            'phone' => $data['so_dien_thoai_khoa'] ?? null,
        ]);
    }

    public function existsByMa(string $ma): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM khoa_bo_mon WHERE MA_KHOA = :ma LIMIT 1');
        $stmt->execute(['ma' => $ma]);
        return (bool) $stmt->fetchColumn();
    }

    public function existsByName(string $name): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM khoa_bo_mon WHERE TEN_KHOA = :ten LIMIT 1');
        $stmt->execute(['ten' => $name]);
        return (bool) $stmt->fetchColumn();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT MA_KHOA AS ma, TEN_KHOA AS ten, EMAIL_KHOA AS email, SO_DIEN_THOAI_KHOA AS phone FROM khoa_bo_mon ORDER BY MA_KHOA DESC');
        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM khoa_bo_mon');
        return (int) $stmt->fetchColumn();
    }

    public function listPaginated(int $page, int $perPage): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = $this->db->prepare('SELECT MA_KHOA AS ma, TEN_KHOA AS ten, EMAIL_KHOA AS email, SO_DIEN_THOAI_KHOA AS phone FROM khoa_bo_mon ORDER BY MA_KHOA DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByMa(string $ma): ?array
    {
        $stmt = $this->db->prepare('SELECT MA_KHOA AS ma, TEN_KHOA AS ten, EMAIL_KHOA AS email, SO_DIEN_THOAI_KHOA AS phone FROM khoa_bo_mon WHERE MA_KHOA = :ma LIMIT 1');
        $stmt->execute(['ma' => $ma]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(string $originalMa, array $data): bool
    {
        // Do not allow changing primary key here to keep logic simple
        $sql = 'UPDATE khoa_bo_mon SET TEN_KHOA = :ten, EMAIL_KHOA = :email, SO_DIEN_THOAI_KHOA = :phone WHERE MA_KHOA = :ma';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
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
}
