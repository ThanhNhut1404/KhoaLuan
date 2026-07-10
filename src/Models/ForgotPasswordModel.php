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
                    nd.TEN_DANG_NHAP AS account_username,
                    nd.TRANG_THAI_ND AS account_status,
                    nd.MAT_KHAU AS password_hash,
                    COALESCE(MAX(CASE WHEN vt.TEN_VAI_TRO = \'SINH_VIEN\' THEN 1 ELSE 0 END), 0) AS is_student_role
             FROM sinh_vien sv
             LEFT JOIN nguoi_dung nd ON nd.TEN_DANG_NHAP = sv.TEN_DANG_NHAP
             LEFT JOIN nguoi_dung_vai_tro ndvt ON ndvt.TEN_DANG_NHAP = nd.TEN_DANG_NHAP
             LEFT JOIN vai_tro vt ON vt.MA_VAI_TRO = ndvt.MA_VAI_TRO
             WHERE LOWER(sv.EMAIL_SV) = LOWER(:email)
             GROUP BY sv.TEN_DANG_NHAP, sv.MSSV, sv.HO_TEN, sv.EMAIL_SV,
                nd.TEN_DANG_NHAP, nd.TRANG_THAI_ND, nd.MAT_KHAU
             LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function findOtpByIdForUsername(int $otpId, string $username): ?array
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
             WHERE ID = :id
               AND TEN_DANG_NHAP = :username
             LIMIT 1'
        );
        $statement->execute([
            'id' => $otpId,
            'username' => $username,
        ]);
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

    public function getPasswordHash(string $username): ?string
    {
        $statement = $this->db->prepare(
            'SELECT MAT_KHAU
             FROM nguoi_dung
             WHERE TEN_DANG_NHAP = :username
             LIMIT 1'
        );
        $statement->execute(['username' => $username]);
        $passwordHash = $statement->fetchColumn();

        return $passwordHash === false ? null : (string) $passwordHash;
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
