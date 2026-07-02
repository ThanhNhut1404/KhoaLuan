-- Quy trình cập nhật quyền truy cập cho module Quản lý tiêu chí điểm
-- 1) Tạo quyền truy cập nếu chưa tồn tại
-- 2) Gán quyền cho ADMIN và vai trò Trường/Nhà trường (DOAN_TRUONG)

INSERT INTO chuc_nang (MA_CHUC_NANG_CODE, TEN_CHUC_NANG, PAGE, MODULE, ICON, TRANG_THAI_CN, THU_TU)
SELECT 'setup_criteria', 'Thiết lập tiêu chí', 'setup_criteria', 'Quản lý tiêu chí điểm', 'criteria', 'HOAT_DONG', 1201
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM chuc_nang WHERE MA_CHUC_NANG_CODE = 'setup_criteria'
);

INSERT INTO vai_tro_chuc_nang (MA_VAI_TRO, MA_CHUC_NANG)
SELECT 1, cn.MA_CHUC_NANG
FROM chuc_nang cn
WHERE cn.MA_CHUC_NANG_CODE = 'setup_criteria'
  AND NOT EXISTS (
      SELECT 1 FROM vai_tro_chuc_nang vtcn
      WHERE vtcn.MA_VAI_TRO = 1
        AND vtcn.MA_CHUC_NANG = cn.MA_CHUC_NANG
  );

INSERT INTO vai_tro_chuc_nang (MA_VAI_TRO, MA_CHUC_NANG)
SELECT 2, cn.MA_CHUC_NANG
FROM chuc_nang cn
WHERE cn.MA_CHUC_NANG_CODE = 'setup_criteria'
  AND NOT EXISTS (
      SELECT 1 FROM vai_tro_chuc_nang vtcn
      WHERE vtcn.MA_VAI_TRO = 2
        AND vtcn.MA_CHUC_NANG = cn.MA_CHUC_NANG
  );
