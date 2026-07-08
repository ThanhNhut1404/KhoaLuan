<?php

namespace KhoaLuan\QLDRL\Models;

use PDO;

class ForgotPasswordModel
{
    public function __construct(private PDO $db)
    {
    }

    public function findStudentByEmail(string $email): ?array
    {
        $statement = $this->db->prepare(
            'SELECT sv.TEN_DANG_NHAP AS username,
                    sv.MSSV AS mssv,
                    sv.HO_TEN AS full_name,
                    sv.EMAIL_SV AS email,
                    nd.TRANG_THAI_ND AS account_status
             FROM sinh_vien sv
             INNER JOIN nguoi_dung nd ON nd.TEN_DANG_NHAP = sv.TEN_DANG_NHAP
             WHERE LOWER(sv.EMAIL_SV) = LOWER(:email)
             LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function disableOldOtps(string $username): bool
    {
        $statement = $this->db->prepare(
            'UPDATE otp_dat_lai_mat_khau
             SET THOI_GIAN_DA_SU_DUNG = NOW()
             WHERE TEN_DANG_NHAP = :username
               AND THOI_GIAN_DA_SU_DUNG IS NULL'
        );

        return $statement->execute(['username' => $username]);
    }

    public function createOtp(string $username, string $hashedOtp, string $expiresAt): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO otp_dat_lai_mat_khau
                (TEN_DANG_NHAP, MA_BAM_OTP, THOI_GIAN_HET_HAN)
             VALUES
                (:username, :hashed_otp, :expires_at)'
        );
        $statement->execute([
            'username' => $username,
            'hashed_otp' => $hashedOtp,
            'expires_at' => $expiresAt,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function findLatestOtp(string $username): ?array
    {
        $statement = $this->db->prepare(
            'SELECT ID,
                    TEN_DANG_NHAP,
                    MA_BAM_OTP,
                    THOI_GIAN_HET_HAN,
                    THOI_GIAN_DA_SU_DUNG,
                    SO_LAN_NHAP_SAI,
                    NGAY_TAO
             FROM otp_dat_lai_mat_khau
             WHERE TEN_DANG_NHAP = :username
               AND THOI_GIAN_DA_SU_DUNG IS NULL
             ORDER BY NGAY_TAO DESC, ID DESC
             LIMIT 1'
        );
        $statement->execute(['username' => $username]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function increaseAttempts(int $otpId): bool
    {
        $statement = $this->db->prepare(
            'UPDATE otp_dat_lai_mat_khau
             SET SO_LAN_NHAP_SAI = SO_LAN_NHAP_SAI + 1
             WHERE ID = :id'
        );

        return $statement->execute(['id' => $otpId]);
    }

    public function markOtpUsed(int $otpId): bool
    {
        $statement = $this->db->prepare(
            'UPDATE otp_dat_lai_mat_khau
             SET THOI_GIAN_DA_SU_DUNG = NOW()
             WHERE ID = :id
               AND THOI_GIAN_DA_SU_DUNG IS NULL'
        );

        return $statement->execute(['id' => $otpId]) && $statement->rowCount() > 0;
    }

    public function updatePassword(string $username, string $password): bool
    {
        $statement = $this->db->prepare(
            'UPDATE nguoi_dung
             SET MAT_KHAU = :password
             WHERE TEN_DANG_NHAP = :username'
        );

        return $statement->execute([
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'username' => $username,
        ]) && $statement->rowCount() > 0;
    }
}
