<?php
    use KhoaLuan\QLDRL\Config\Database;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $academic_years = [];

    try {
        $db = Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'academic_year' => trim($_POST['academic_year'] ?? ''),
                'semester_name' => trim($_POST['semester_name'] ?? ''),
                'start_date' => trim($_POST['start_date'] ?? ''),
                'end_date' => trim($_POST['end_date'] ?? ''),
                'status' => trim($_POST['status'] ?? ''),
            ];

            if ($formData['academic_year'] === '') {
                $errors['academic_year'] = 'Vui long chon nien khoa';
            }

            if ($formData['semester_name'] === '') {
                $errors['semester_name'] = 'Vui long nhap ten hoc ky';
            }

            if ($formData['start_date'] === '') {
                $errors['start_date'] = 'Vui long chon ngay bat dau';
            }

            if ($formData['end_date'] === '') {
                $errors['end_date'] = 'Vui long chon ngay ket thuc';
            }

            if ($formData['start_date'] !== '' && $formData['end_date'] !== '' && $formData['start_date'] >= $formData['end_date']) {
                $errors['end_date'] = 'Ngay ket thuc phai sau ngay bat dau';
            }

            if (!in_array($formData['status'], ['upcoming', 'active', 'completed'], true)) {
                $errors['status'] = 'Vui long chon trang thai';
            }

            if (empty($errors)) {
                $db->beginTransaction();

                $nextBangDrlId = (int) $db->query('SELECT COALESCE(MAX(MA_BANG_DRL), 0) + 1 FROM bang_drl')->fetchColumn();
                $insertBangDrl = $db->prepare(
                    'INSERT INTO bang_drl (MA_BANG_DRL, TONG_DIEM, XEP_LOAI, TRANG_THAI_DRL, NGAY_TAO_TB, GHI_CHU)
                     VALUES (:id, NULL, NULL, NULL, :created_at, NULL)'
                );
                $insertBangDrl->execute([
                    ':id' => $nextBangDrlId,
                    ':created_at' => date('Y-m-d'),
                ]);

                $nextSemesterId = (int) $db->query('SELECT COALESCE(MAX(MA_HOC_KY), 0) + 1 FROM hoc_ky')->fetchColumn();
                $insertSemester = $db->prepare(
                    'INSERT INTO hoc_ky (MA_HOC_KY, MA_NIEN_KHOA, MA_BANG_DRL, TEN_HOC_KY, THOI_GIAN_BDHK, THOI_GIAN_KTHK, TRANG_THAI_HK)
                     VALUES (:semester_id, :year_id, :bang_drl_id, :name, :start_date, :end_date, :status)'
                );
                $insertSemester->execute([
                    ':semester_id' => $nextSemesterId,
                    ':year_id' => (int) $formData['academic_year'],
                    ':bang_drl_id' => $nextBangDrlId,
                    ':name' => $formData['semester_name'],
                    ':start_date' => $formData['start_date'],
                    ':end_date' => $formData['end_date'],
                    ':status' => $formData['status'],
                ]);

                $db->commit();

                $_SESSION['message'] = 'Tao hoc ky thanh cong';
                $_SESSION['message_type'] = 'success';
                echo '<script>window.location.href="?page=list_semester";</script>';
                exit;
            }
        }

        $academic_years = $db->query(
            'SELECT MA_NIEN_KHOA AS id, TEN_NIEN_KHOA AS name
             FROM nien_khoa
             ORDER BY MA_NIEN_KHOA DESC'
        )->fetchAll();
    } catch (Throwable $exception) {
        if (isset($db) && $db->inTransaction()) {
            $db->rollBack();
        }

        $errors['database'] = $exception->getMessage();
    }
?>

<div class="create-semester-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO HỌC KỲ</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createSemesterForm" method="POST" action="?page=create_semester">
                <?php if(isset($errors['database'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($errors['database']) ?>
                    </div>
                <?php endif; ?>

                <div class="form-grid">
                    <!-- Niên khóa -->
                    <div class="form-field">
                        <label class="field-label form-label" for="academic_year">
                            Niên khóa <span class="required">*</span>
                        </label>
                        <select id="academic_year" name="academic_year" class="field-input form-select" required>
                            <option value="">-- Chọn niên khóa --</option>
                            <?php foreach ($academic_years as $year): ?>
                                <option value="<?= $year['id'] ?>" <?= (isset($formData['academic_year']) && $formData['academic_year'] == $year['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($year['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-error<?= isset($errors['academic_year']) ? '' : ' is-empty' ?>"><?= isset($errors['academic_year']) ? htmlspecialchars($errors['academic_year']) : '&nbsp;' ?></span>
                    </div>

                    <!-- Tên học kỳ -->
                    <div class="form-field">
                        <label class="field-label form-label" for="semester_name">
                            Tên học kỳ <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="semester_name" 
                            name="semester_name" 
                            class="field-input form-control" 
                            placeholder="Nhập tên học kỳ"
                            value="<?= isset($formData['semester_name']) ? htmlspecialchars($formData['semester_name']) : '' ?>"
                            required 
                        />
                        <small class="field-hint">Ví dụ: Học kỳ 1</small>
                        <span class="field-error<?= isset($errors['semester_name']) ? '' : ' is-empty' ?>"><?= isset($errors['semester_name']) ? htmlspecialchars($errors['semester_name']) : '&nbsp;' ?></span>
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div class="form-field">
                        <label class="field-label form-label" for="start_date">
                            Ngày bắt đầu <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="start_date" 
                            name="start_date" 
                            class="field-input form-control" 
                            value="<?= isset($formData['start_date']) ? htmlspecialchars($formData['start_date']) : '' ?>"
                            required 
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <span class="field-error<?= isset($errors['start_date']) ? '' : ' is-empty' ?>"><?= isset($errors['start_date']) ? htmlspecialchars($errors['start_date']) : '&nbsp;' ?></span>
                    </div>

                    <!-- Ngày kết thúc -->
                    <div class="form-field">
                        <label class="field-label form-label" for="end_date">
                            Ngày kết thúc <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="end_date" 
                            name="end_date" 
                            class="field-input form-control" 
                            value="<?= isset($formData['end_date']) ? htmlspecialchars($formData['end_date']) : '' ?>"
                            required 
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <span class="field-error<?= isset($errors['end_date']) ? '' : ' is-empty' ?>"><?= isset($errors['end_date']) ? htmlspecialchars($errors['end_date']) : '&nbsp;' ?></span>
                    </div>

                    

                    <!-- Trạng thái -->
                    <div class="form-field">
                        <label class="field-label form-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="upcoming" <?= (isset($formData['status']) && $formData['status'] === 'upcoming') ? 'selected' : '' ?>>Sắp tới</option>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Đang diễn ra</option>
                            <option value="completed" <?= (isset($formData['status']) && $formData['status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                        </select>
                        <span class="field-error<?= isset($errors['status']) ? '' : ' is-empty' ?>"><?= isset($errors['status']) ? htmlspecialchars($errors['status']) : '&nbsp;' ?></span>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="?page=list_semester" class="action-btn secondary cancel-btn btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary btn btn-primary">
                        Tạo học kỳ
                    </button>
                </div>

                <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<style>
    .create-semester-page {
        display: grid;
        gap: 0;
        padding: 24px;
    }

    .page-header {
        margin-bottom: 0;
    }

    .page-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.6px;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    .page-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .panel-header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-title svg {
        width: 18px;
        height: 18px;
        color: #0f2a5a;
    }

    .panel-body {
        padding: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 14px;
        margin-bottom: 8px;
        align-items: start; /* ensure fields align to top */
    }

    .form-field {
        display: grid;
        gap: 3px;
    }

    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.4px;
        display: block;
    }

    .required {
        color: #dc2626;
        font-weight: 700;
    }

    .field-input {
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
        height: 40px;
        box-sizing: border-box;
    }

    .field-input:focus {
        outline: none;
        border-color: #0f2a5a;
        box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08);
        background: #ffffff;
    }

    select.field-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231f2937' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 32px;
    }

    .field-hint {
        font-size: 11px;
        color: #9ca3af;
        display: block;
    }

    .field-error {
        font-size: 12px;
        color: #dc2626;
        display: block;
        line-height: 1.2;
        min-height: 18px;
        overflow-wrap: anywhere;
    }

    .field-error.is-empty {
        visibility: hidden;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid #e8ecf3;
    }

    .action-btn {
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #0f2a5a;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .action-btn:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
        font-weight: 700;
    }

    .action-btn.primary:hover {
        background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%);
        border-color: #0a1838;
    }

    .alert {
        margin-top: 16px;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e3a8a;
        border-color: #93c5fd;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.getElementById('createSemesterForm')?.addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate >= endDate) {
            e.preventDefault();
            alert('Ngày bắt đầu phải trước ngày kết thúc!');
            return false;
        }
    });
</script>
