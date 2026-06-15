<?php
// Activity detail modal include (markup + JS only)
?>

<div class="activity-detail-overlay" id="activityDetailOverlay" aria-hidden="true">
    <div class="activity-detail-card" role="dialog" aria-modal="true" aria-labelledby="activityDetailTitle">
        <div class="activity-detail-header">
            <span class="modal-title" id="activityDetailTitle"></span>
            <button class="activity-detail-close" type="button" aria-label="Đóng" onclick="closeActivityDetail()">✕</button>
        </div>
        <div class="activity-detail-body">
            <div class="activity-detail-top">
                <div class="activity-detail-image">
                    <img id="activityDetailImage" src="" alt="Chi tiết hoạt động" />
                </div>
                <div class="activity-detail-summary">
                    <div class="activity-detail-summary-grid">
                        <div class="activity-detail-summary-item">
                            <div class="activity-detail-summary-label"><i class="fa-solid fa-university" style="margin-right: 6px;"></i>Đơn vị</div>
                            <div class="activity-detail-summary-value" id="activityDetailUnit"></div>
                        </div>
                        <div class="activity-detail-summary-item">
                            <div class="activity-detail-summary-label"><i class="fa-solid fa-calendar-days" style="margin-right: 6px;"></i>Thời gian</div>
                            <div class="activity-detail-summary-value" id="activityDetailTime"></div>
                        </div>
                        <div class="activity-detail-summary-item">
                            <div class="activity-detail-summary-label"><i class="fa-solid fa-location-dot" style="margin-right: 6px;"></i>Địa điểm</div>
                            <div class="activity-detail-summary-value" id="activityDetailLocation"></div>
                        </div>
                        <div class="activity-detail-summary-item">
                            <div class="activity-detail-summary-label"><i class="fa-solid fa-tag" style="margin-right: 6px;"></i>Trạng thái</div>
                            <div class="activity-detail-summary-value" id="activityDetailTag"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="activity-detail-grid">
                <div class="detail-box benefits-box">
                    <div class="detail-label"><i class="fa-solid fa-gift"></i>Quyền lợi</div>
                    <div class="detail-value" id="activityDetailBenefits"></div>
                </div>
                <div class="detail-box">
                    <div class="detail-label"><i class="fa-solid fa-shirt"></i>Trang phục</div>
                    <div class="detail-value" id="activityDetailClothing"></div>
                </div>
                <div class="detail-box audience-box">
                    <div class="detail-label"><i class="fa-solid fa-users"></i>Đối tượng</div>
                    <div class="detail-value" id="activityDetailAudience"></div>
                </div>
                <div class="detail-box">
                    <div class="detail-label"><i class="fa-solid fa-file-lines"></i>Nội dung</div>
                    <div class="detail-value" id="activityDetailContent"></div>
                </div>
                <div class="detail-box stat-box point-box">
                    <div class="detail-label"><i class="fa-solid fa-circle-plus"></i>Điểm cộng</div>
                    <div class="detail-value" id="activityDetailPoint"></div>
                </div>
                <div class="detail-box stat-box remaining-box">
                    <div class="detail-label"><i class="fa-solid fa-user-clock"></i>Còn lại</div>
                    <div class="detail-value" id="activityDetailRemaining"></div>
                </div>
                <div class="detail-box full-width contact-box">
                    <div class="detail-label"><i class="fa-solid fa-address-card"></i>Liên hệ</div>
                    <div class="detail-contact">
                        <div class="detail-contact-item">
                            <div class="detail-label"><i class="fa-solid fa-user-tie"></i>Người đại diện</div>
                            <div class="detail-value" id="activityDetailContactName"></div>
                        </div>
                        <div class="detail-contact-item">
                            <div class="detail-label"><i class="fa-solid fa-phone"></i>Số điện thoại</div>
                            <div class="detail-value" id="activityDetailContactPhone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="activity-detail-footer">
            <button class="detail-action" type="button" onclick="closeActivityDetail()">Đóng</button>
            <button class="detail-action primary" type="button">Đăng ký</button>
        </div>
    </div>
</div>

<script>
    (function() {
        const overlay = document.getElementById('activityDetailOverlay');
        const cards = document.querySelectorAll('.activity-card');

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value || '';
        }

        window.openActivityDetail = function(card) {
            if (!overlay || !card) return;

            setText('activityDetailTitle', card.dataset.title);
            setText('activityDetailUnit', card.dataset.unit);
            setText('activityDetailTime', card.dataset.time);
            setText('activityDetailLocation', card.dataset.location);
            
            // Set tag and apply category-based badge styling
            const tagEl = document.getElementById('activityDetailTag');
            if (tagEl) {
                tagEl.textContent = card.dataset.tag || '';
                tagEl.className = 'activity-detail-summary-value activity-badge-inline';
                
                const tagLower = (card.dataset.tag || '').toLowerCase();
                if (tagLower.includes('tình nguyện')) {
                    tagEl.classList.add('badge-tinhnguyen');
                } else if (tagLower.includes('học thuật')) {
                    tagEl.classList.add('badge-hocthuat');
                } else if (tagLower.includes('thể thao')) {
                    tagEl.classList.add('badge-thethao');
                } else if (tagLower.includes('văn hóa')) {
                    tagEl.classList.add('badge-vanhoa');
                } else if (tagLower.includes('kỹ năng')) {
                    tagEl.classList.add('badge-kynang');
                } else {
                    tagEl.classList.add('badge-khac');
                }
            }

            setText('activityDetailBenefits', card.dataset.benefits);
            setText('activityDetailClothing', card.dataset.clothing);
            setText('activityDetailAudience', card.dataset.audience);
            setText('activityDetailContent', card.dataset.content);
            setText('activityDetailPoint', card.dataset.point);
            setText('activityDetailRemaining', card.dataset.remaining);
            setText('activityDetailContactName', card.dataset.contactName);
            setText('activityDetailContactPhone', card.dataset.contactPhone);

            const img = document.getElementById('activityDetailImage');
            if (img) {
                img.src = card.dataset.image || '';
                img.alt = card.dataset.title || 'Chi tiết hoạt động';
            }

            overlay.classList.add('active');
            overlay.setAttribute('aria-hidden', 'false');
        };

        window.closeActivityDetail = function() {
            if (!overlay) return;
            overlay.classList.remove('active');
            overlay.setAttribute('aria-hidden', 'true');
        };

        cards.forEach(function(card) {
            card.addEventListener('click', function() {
                window.openActivityDetail(card);
            });
            card.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    window.openActivityDetail(card);
                }
            });
        });

        if (overlay) {
            overlay.addEventListener('click', function(event) {
                if (event.target === overlay) window.closeActivityDetail();
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') window.closeActivityDetail();
        });
    })();
</script>
