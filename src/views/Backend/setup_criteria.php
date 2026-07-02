<?php
// Page: Thiết lập tiêu chí
$title = 'Thiết lập tiêu chí';
?>

<div class="create-year-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h3 class="panel-title">THIẾT LẬP TIÊU CHÍ</h3>
        </div>
        <div class="panel-body card-body">
            <div class="criteria-controls" style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <label class="field-label form-label" style="margin-right:6px;">Học kỳ áp dụng:</label>
                    <select class="field-input form-select">
                        <option>Học kỳ 1 (2026 - 2027)</option>
                        <option>Học kỳ 2 (2026 - 2027)</option>
                    </select>
                    <button class="action-btn secondary btn btn-outline-secondary" style="margin-left:8px;">Đang áp dụng</button>
                </div>

                <div style="display:flex;gap:10px;align-items:center;">
                    <a class="action-btn secondary btn btn-outline-secondary" href="#">Xem lịch sử tiêu chí</a>
                    <button class="action-btn primary btn btn-primary">Thêm tiêu chí lớn</button>
                </div>
            </div>

            <div class="criteria-list">
        <div class="criteria-card">
            <div class="criteria-header" tabindex="0">
                <div class="criteria-badge" style="background:#60a5fa;">1</div>
                <div class="criteria-title">Ý THỨC THAM GIA HỌC TẬP <span class="sub-count">4 tiêu chí con</span></div>
                <div class="criteria-right">Tối đa: <strong>20 điểm</strong></div>
                <button class="criteria-toggle" aria-expanded="true">▾</button>
            </div>
            <div class="criteria-body">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:40px;">STT</th>
                            <th>Tên tiêu chí con</th>
                            <th>Nội dung tiêu chí</th>
                            <th>Người chấm</th>
                            <th>Yêu cầu minh chứng</th>
                            <th>Điểm tối đa</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Tham gia nghiên cứu khoa học</td>
                            <td>Sinh viên tham gia các đề tài NCKH</td>
                            <td>Sinh viên tự chấm</td>
                            <td class="text-success">Có</td>
                            <td>5 điểm</td>
                            <td>✎ 🗑</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Tham gia hội thảo học thuật</td>
                            <td>Tham gia các hội thảo, toạ đàm học thuật</td>
                            <td>Sinh viên tự chấm</td>
                            <td class="text-success">Có</td>
                            <td>3 điểm</td>
                            <td>✎ 🗑</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Đạt thành tích học tập tốt</td>
                            <td>Đạt học bổng, sinh viên giỏi, xuất sắc...</td>
                            <td>Sinh viên tự chấm</td>
                            <td class="text-success">Có</td>
                            <td>7 điểm</td>
                            <td>✎ 🗑</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Tham gia CLB học thuật</td>
                            <td>Tham gia các CLB học thuật của trường</td>
                            <td>Sinh viên tự chấm</td>
                            <td class="text-success">Có</td>
                            <td>5 điểm</td>
                            <td>✎ 🗑</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional criteria cards (collapsed by default) -->
        <div class="criteria-card collapsed">
            <div class="criteria-header" tabindex="0">
                <div class="criteria-badge" style="background:#34d399;">2</div>
                <div class="criteria-title">CHẤP HÀNH NỘI QUY <span style="font-size:12px;color:#34d399;margin-left:8px;padding:3px 6px;border-radius:8px;background:rgba(52,211,153,0.12);">3 tiêu chí con</span></div>
                <div class="criteria-right">Tối đa: <strong>25 điểm</strong></div>
                <button class="criteria-toggle" aria-expanded="false">▸</button>
            </div>
            <div class="criteria-body" style="display:none;padding:12px 0;"></div>
            </div>

            <div class="criteria-card collapsed">
            <div class="criteria-header" tabindex="0">
                <div class="criteria-badge" style="background:#f59e0b;">3</div>
                <div class="criteria-title">HOẠT ĐỘNG CHÍNH TRỊ - XÃ HỘI <span style="font-size:12px;color:#f59e0b;margin-left:8px;padding:3px 6px;border-radius:8px;background:rgba(245,158,11,0.12);">4 tiêu chí con</span></div>
                <div class="criteria-right">Tối đa: <strong>20 điểm</strong></div>
                <button class="criteria-toggle" aria-expanded="false">▸</button>
            </div>
            <div class="criteria-body" style="display:none;padding:12px 0;"></div>
        </div>

        <div class="criteria-card collapsed">
            <div class="criteria-header" tabindex="0">
                <div class="criteria-badge" style="background:#60a5fa;">4</div>
                <div class="criteria-title">Ý THỨC CÔNG DÂN <span style="font-size:12px;color:#60a5fa;margin-left:8px;padding:3px 6px;border-radius:8px;background:rgba(96,165,250,0.12);">3 tiêu chí con</span></div>
                <div class="criteria-right">Tối đa: <strong>25 điểm</strong></div>
                <button class="criteria-toggle" aria-expanded="false">▸</button>
            </div>
            <div class="criteria-body" style="display:none;padding:12px 0;"></div>
        </div>

        <div class="criteria-card collapsed">
            <div class="criteria-header" tabindex="0">
                <div class="criteria-badge" style="background:#ef4444;">5</div>
                <div class="criteria-title">CÔNG TÁC LỚP - ĐOÀN HỘI <span style="font-size:12px;color:#ef4444;margin-left:8px;padding:3px 6px;border-radius:8px;background:rgba(239,68,68,0.12);">3 tiêu chí con</span></div>
                <div class="criteria-right">Tối đa: <strong>10 điểm</strong></div>
                <button class="criteria-toggle" aria-expanded="false">▸</button>
            </div>
            <div class="criteria-body" style="display:none;padding:12px 0;"></div>
        </div>

            </div>

            <div style="display:flex;justify-content:center;margin-top:26px;">
                <button class="action-btn primary btn btn-primary">Lưu cấu hình thang điểm</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Reuse styles from create_year.php and add criteria-specific tweaks */
    .create-year-page { display: grid; gap: 0; padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .panel-title { font-size: 14px; font-weight: 700; color: #0f2a5a; margin: 0; display: flex; align-items: center; gap: 8px; }
    .panel-body { padding: 20px; }

    .criteria-card { background:#fff;border:1px solid #e8ecf3;border-radius:12px;padding:10px 14px;margin-bottom:12px;box-shadow:0 6px 18px rgba(15,23,42,0.06); }
    .criteria-header { display:flex;align-items:center;gap:12px;cursor:pointer; }
    .criteria-badge { width:34px;height:34px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700; }
    .criteria-title { font-weight:700;color:#0f2a5a;flex:1; }
    .criteria-right { font-size:13px;color:#64748b;margin-right:8px; }
    .criteria-toggle { background:transparent;border:none;font-size:16px;color:#94a3b8;cursor:pointer; }

    .sub-count { font-size:12px;color:var(--brand);margin-left:8px;padding:3px 6px;border-radius:8px;background:rgba(16,185,129,0.06); }

    .table { width:100%; border-collapse: collapse; }
    .table th, .table td { padding:10px; border-bottom:1px solid #e8ecf3; text-align:left; color:#334155; font-size:13px; }
    .table thead th { color:#64748b; font-weight:700; }
    .text-success { color:#10b981; font-weight:700; }

    /* Buttons (from create_year) */
    .action-btn { padding: 8px 20px; border-radius: 10px; border: 1px solid #e5e7eb; background: #ffffff; color: #0f2a5a; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; white-space: nowrap; }
    .action-btn:hover { background: #f3f4f6; border-color: #d1d5db; }
    .action-btn.primary { background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%); border-color: #0f2a5a; color: #ffffff; }
    .action-btn.primary:hover { background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%); }
    .action-btn.secondary { background:#fff; }

    @media (max-width: 768px) {
        .criteria-controls { flex-direction: column; gap:12px; }
        .action-btn { width: 100%; justify-content: center; }
    }

    /* keep previous JS behavior */
</style>

<script>
document.querySelectorAll('.criteria-header').forEach(function(h){
    h.addEventListener('click', function(){
        var card = h.closest('.criteria-card');
        var body = card.querySelector('.criteria-body');
        var toggle = card.querySelector('.criteria-toggle');
        var isOpen = body.style.display !== 'none' && body.style.display !== '';
        if(isOpen){
            body.style.display = 'none';
            toggle.textContent = '▸';
            toggle.setAttribute('aria-expanded','false');
        } else {
            body.style.display = '';
            toggle.textContent = '▾';
            toggle.setAttribute('aria-expanded','true');
        }
    });
});
</script>
