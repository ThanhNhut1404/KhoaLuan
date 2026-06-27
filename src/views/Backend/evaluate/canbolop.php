<?php
// Sample data (thay bằng dữ liệu thật từ controller khi tích hợp)
$students = $students ?? [
	['mssv'=>'22123456','name'=>'Nguyễn Văn A','total_sv'=>85,'total_lt'=>80,'status'=>'pending'],
	['mssv'=>'22123457','name'=>'Trần Thị B','total_sv'=>90,'total_lt'=>90,'status'=>'approved'],
	['mssv'=>'22123458','name'=>'Lê Văn C','total_sv'=>78,'total_lt'=>78,'status'=>'approved'],
	['mssv'=>'22123459','name'=>'Phạm Thị D','total_sv'=>88,'total_lt'=>80,'status'=>'pending']
];

$active = 0; // index của sinh viên hiện chọn
?>

<div class="evaluate-page">
	<div class="page-panel two-column card">
		<div class="left-panel">
			<div class="panel-header card-header">
				<h3 class="panel-title">Chấm điểm rèn luyện - Lớp trưởng</h3>
			</div>
			<div class="panel-body list-panel card-body">
				<div class="table-wrapper">
					<table class="data-table student-list table table-hover table-bordered align-middle">
						<thead>
							<tr>
								<th></th>
								<th>#</th>
								<th>MSSV</th>
								<th>Họ và tên</th>
								<th>Tổng SV</th>
								<th>Tổng LT</th>
								<th>Trạng thái</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($students as $i => $s): ?>
								<tr data-index="<?= $i ?>" class="<?= $i === $active ? 'selected' : '' ?>">
									<td><input type="checkbox" class="row-check" <?= $i===0? 'checked' : '' ?>></td>
									<td class="col-stt"><?= $i+1 ?></td>
									<td class="col-code"><?= htmlspecialchars($s['mssv']) ?></td>
									<td class="col-name"><?= htmlspecialchars($s['name']) ?></td>
									<td class="col-credits"><?= $s['total_sv'] ?></td>
									<td class="col-dept"><?= $s['total_lt'] ?></td>
									<td class="col-status"><span class="status-<?= $s['status'] ?>"><?= $s['status']==='approved' ? 'Đã duyệt' : 'Chưa duyệt' ?></span></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div class="list-footer">Tổng: <?= count($students) ?> sinh viên</div>
			</div>
		</div>

		<div class="right-panel">
			<div class="panel-header detail-header card-header">
				<div class="student-info">
					<div class="avatar">NV</div>
					<div>
						<div class="student-name" id="stu-name"><?= htmlspecialchars($students[$active]['name']) ?></div>
						<div class="student-meta">Lớp: CNTT K15A1 • Ngày tạo phiếu: 01/06/2024</div>
					</div>
				</div>

				<div class="score-cards">
					<div class="card"><div class="card-title">Tổng SV tự chấm</div><div class="card-value" id="sv-total"><?= $students[$active]['total_sv'] ?></div></div>
					<div class="card"><div class="card-title">Tổng lớp trưởng</div><div class="card-value" id="lt-total"><?= $students[$active]['total_lt'] ?></div></div>
					<div class="card diff"><div class="card-title">Chênh lệch</div><div class="card-value" id="diff-total"><?= $students[$active]['total_sv'] - $students[$active]['total_lt'] ?></div></div>
				</div>
			</div>

			<div class="panel-body detail-body card-body">
				<div class="action-row">
					<button class="btn-accept">Duyệt phiếu</button>
					<button class="btn-reject">Từ chối</button>
					<button class="btn-feedback">Gửi phản hồi</button>
				</div>

				<div class="tabs">
					<button class="tab active" data-tab="score">Phiếu chấm điểm</button>
					<button class="tab" data-tab="evidence">Minh chứng</button>
					<button class="tab" data-tab="history">Lịch sử chỉnh sửa</button>
					<button class="tab" data-tab="note">Ghi chú</button>
				</div>

				<div class="tab-panels">
					<div class="tab-panel active" id="panel-score">
						<form id="score-form">
							<div class="score-section">
								<h4>I. Ý thức học tập (Tối đa: 30 điểm)</h4>
								<table class="score-table">
									<thead>
										<tr><th>#</th><th>Tiêu chí</th><th>Mô tả</th><th>Điểm tối đa</th><th>SV tự chấm</th><th>Lớp trưởng chấm</th><th>Chênh lệch</th></tr>
									</thead>
									<tbody>
										<?php $criteria1 = [
											['title'=>'Đi học đầy đủ','desc'=>'Đi học đầy đủ các buổi theo thời khóa biểu','max'=>10,'sv'=>10,'lt'=>10],
											['title'=>'Chuẩn bị bài','desc'=>'Chuẩn bị bài trước khi đến lớp','max'=>10,'sv'=>8,'lt'=>10],
											['title'=>'Tham gia phát biểu','desc'=>'Tham gia phát biểu xây dựng bài học','max'=>5,'sv'=>5,'lt'=>5],
											['title'=>'Nghiêm túc trong giờ học','desc'=>'Không làm việc riêng, không sử dụng điện thoại','max'=>5,'sv'=>5,'lt'=>5]
										];
										foreach ($criteria1 as $idx=>$c): ?>
											<tr>
												<td><?= $idx+1 ?></td>
												<td><?= htmlspecialchars($c['title']) ?></td>
												<td class="desc"><?= htmlspecialchars($c['desc']) ?></td>
												<td class="max"><?= $c['max'] ?></td>
												<td><input type="number" class="sv-input" min="0" max="<?= $c['max'] ?>" value="<?= $c['sv'] ?>"></td>
												<td><input type="number" class="lt-input" min="0" max="<?= $c['max'] ?>" value="<?= $c['lt'] ?>"></td>
												<td class="col-diff">0</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr><td colspan="3">Tổng điểm mục I</td><td class="total-max">30</td><td id="sv-sum-1">30</td><td id="lt-sum-1">28</td><td id="diff-1">-2</td></tr>
									</tfoot>
								</table>
							</div>

							<div class="score-section">
								<h4>II. Chấp hành nội quy (Tối đa: 20 điểm)</h4>
								<table class="score-table">
									<thead>
										<tr><th>#</th><th>Tiêu chí</th><th>Mô tả</th><th>Điểm tối đa</th><th>SV tự chấm</th><th>Lớp trưởng chấm</th><th>Chênh lệch</th></tr>
									</thead>
									<tbody>
										<?php $criteria2 = [
											['title'=>'Chấp hành nội quy','desc'=>'Chấp hành tốt nội quy của nhà trường','max'=>10,'sv'=>8,'lt'=>10],
											['title'=>'Trang phục','desc'=>'Mặc đồng phục đúng quy định','max'=>5,'sv'=>5,'lt'=>5],
											['title'=>'Giữ gìn vệ sinh','desc'=>'Giữ gìn vệ sinh lớp học, trường','max'=>5,'sv'=>5,'lt'=>5]
										];
										foreach ($criteria2 as $idx=>$c): ?>
											<tr>
												<td><?= $idx+1 ?></td>
												<td><?= htmlspecialchars($c['title']) ?></td>
												<td class="desc"><?= htmlspecialchars($c['desc']) ?></td>
												<td class="max"><?= $c['max'] ?></td>
												<td><input type="number" class="sv-input" min="0" max="<?= $c['max'] ?>" value="<?= $c['sv'] ?>"></td>
												<td><input type="number" class="lt-input" min="0" max="<?= $c['max'] ?>" value="<?= $c['lt'] ?>"></td>
												<td class="col-diff">0</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr><td colspan="3">Tổng điểm mục II</td><td class="total-max">20</td><td id="sv-sum-2">18</td><td id="lt-sum-2">20</td><td id="diff-2">-2</td></tr>
									</tfoot>
								</table>
							</div>

							<div class="totals-row">
								<div><strong>TỔNG CỘNG</strong></div>
								<div class="totals">
									<div>SV tự chấm: <span id="sv-grand">48</span></div>
									<div>Lớp trưởng: <span id="lt-grand">48</span></div>
									<div>Chênh lệch: <span id="grand-diff">0</span></div>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-panel" id="panel-evidence">Minh chứng (upload, xem ảnh...)</div>
					<div class="tab-panel" id="panel-history">Lịch sử chỉnh sửa sẽ hiển thị ở đây</div>
					<div class="tab-panel" id="panel-note">Ghi chú và phản hồi</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	/* Reuse core styles from list_major.php and extend for layout */
	.two-column { display:flex; gap:18px; padding:18px; }
	.left-panel { width:36%; }
	.right-panel { width:64%; }
	.panel-header { padding:12px 16px; background:#f9fafb; border-bottom:1px solid #e5e7eb; }
	.panel-title { margin:0; font-size:14px; font-weight:700; color:#0f2a5a; }
	.list-panel { padding:12px 16px; }
	.student-list th, .student-list td { text-align:left; padding:8px 10px; font-size:13px; }
	.student-list thead { background:#f3f4f6; }
	.student-list tbody tr.selected { background:#eef2ff; }
	.col-status .status-pending { color:#d97706; font-weight:700; }
	.col-status .status-approved { color:#059669; font-weight:700; }
	.list-footer { padding:10px 0; color:#6b7280; font-size:13px; }

	.detail-header { display:flex; justify-content:space-between; align-items:center; }
	.student-info { display:flex; gap:12px; align-items:center; }
	.avatar { width:48px; height:48px; border-radius:50%; background:#c7d2fe; display:flex; align-items:center; justify-content:center; font-weight:700; color:#1e293b }
	.student-name { font-weight:800; color:#0f2a5a }
	.student-meta { color:#6b7280; font-size:13px }

	.score-cards { display:flex; gap:12px; align-items:center }
	.card { background:#fff; border:1px solid #e8ecf3; padding:10px 12px; border-radius:8px; text-align:center }
	.card-title { font-size:12px; color:#6b7280 }
	.card-value { font-size:20px; font-weight:800; color:#0f2a5a }
	.card.diff { border-left:3px solid #ef4444 }

	.action-row { padding:12px 0; display:flex; gap:8px }
	.btn-accept { background:#10b981; color:#fff; padding:8px 12px; border-radius:8px; border:none }
	.btn-reject { background:#ef4444; color:#fff; padding:8px 12px; border-radius:8px; border:none }
	.btn-feedback { background:#fff; border:1px solid #e5e7eb; padding:8px 12px; border-radius:8px }

	.tabs { display:flex; gap:8px; padding:8px 0 }
	.tab { background:#fff; border:1px solid #e5e7eb; padding:8px 12px; border-radius:8px; cursor:pointer }
	.tab.active { background:linear-gradient(180deg,#0f2a5a,#0b1f45); color:#fff; }

	.tab-panels { margin-top:12px }
	.tab-panel { display:none; padding:8px 0 }
	.tab-panel.active { display:block }

	.score-table { width:100%; border-collapse:collapse; margin-bottom:12px }
	.score-table th, .score-table td { border:1px solid #eef2f6; padding:8px; font-size:13px }
	.score-table th { background:#f8fafc; text-align:left }
	.score-table input { width:80px; padding:6px; border:1px solid #e5e7eb; border-radius:6px }
	.totals-row { display:flex; justify-content:space-between; padding:12px 0; font-weight:700 }

	.totals { display:flex; gap:18px; align-items:center }

	@media (max-width:900px) { .two-column { flex-direction:column } .left-panel,.right-panel{width:100%} }
</style>

<script>
	// Simple interaction: chọn sinh viên, tính tổng và chênh lệch
	document.querySelectorAll('.student-list tbody tr').forEach(row=>{
		row.addEventListener('click', ()=>{
			document.querySelectorAll('.student-list tbody tr').forEach(r=>r.classList.remove('selected'));
			row.classList.add('selected');
			const idx = parseInt(row.getAttribute('data-index'));
			// Lấy dữ liệu từ PHP-rendered JS dataset
			const students = <?= json_encode($students) ?>;
			const s = students[idx];
			document.getElementById('stu-name').textContent = s.name;
			document.getElementById('sv-total').textContent = s.total_sv;
			document.getElementById('lt-total').textContent = s.total_lt;
			document.getElementById('diff-total').textContent = s.total_sv - s.total_lt;
		});
	});

	function recalcSection(sectionIdx){
		const svInputs = Array.from(document.querySelectorAll('.score-section')[sectionIdx].querySelectorAll('.sv-input'));
		const ltInputs = Array.from(document.querySelectorAll('.score-section')[sectionIdx].querySelectorAll('.lt-input'));
		const svSum = svInputs.reduce((s,i)=>s+Number(i.value||0),0);
		const ltSum = ltInputs.reduce((s,i)=>s+Number(i.value||0),0);
		document.getElementById('sv-sum-'+(sectionIdx+1)).textContent = svSum;
		document.getElementById('lt-sum-'+(sectionIdx+1)).textContent = ltSum;
		document.getElementById('diff-'+(sectionIdx+1)).textContent = svSum - ltSum;
		// update per-row diffs
		const rows = document.querySelectorAll('.score-section')[sectionIdx].querySelectorAll('tbody tr');
		rows.forEach((r, i)=>{
			const sv = Number(r.querySelector('.sv-input').value||0);
			const lt = Number(r.querySelector('.lt-input').value||0);
			r.querySelector('.col-diff').textContent = sv - lt;
		});
	}

	document.querySelectorAll('.sv-input, .lt-input').forEach(inp=>inp.addEventListener('input', ()=>{
		recalcSection(0); recalcSection(1);
		const svGrand = Number(document.getElementById('sv-sum-1').textContent) + Number(document.getElementById('sv-sum-2').textContent);
		const ltGrand = Number(document.getElementById('lt-sum-1').textContent) + Number(document.getElementById('lt-sum-2').textContent);
		document.getElementById('sv-grand').textContent = svGrand;
		document.getElementById('lt-grand').textContent = ltGrand;
		document.getElementById('grand-diff').textContent = svGrand - ltGrand;
	}));

	// Init sums
	recalcSection(0); recalcSection(1);

	// Tabs
	document.querySelectorAll('.tab').forEach(tab=>{
		tab.addEventListener('click', ()=>{
			document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
			tab.classList.add('active');
			document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
			document.getElementById('panel-'+tab.dataset.tab).classList.add('active');
		});
	});
</script>

