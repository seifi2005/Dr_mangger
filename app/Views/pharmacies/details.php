<?php
/** @var array $pharmacy */
$pageTitle = 'جزئیات داروخانه - سیستم مدیریت پزشکان';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .profile-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 30px;
    }

    .profile-header {
        display: flex;
        gap: 25px;
        margin-bottom: 30px;
        align-items: flex-start;
    }

    .profile-avatar-wrapper {
        position: relative;
    }

    .profile-avatar {
        width: 160px;
        height: 160px;
        border-radius: 12px;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #7239ea 0%, #9d5cff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        color: white;
    }

    .online-status {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 18px;
        height: 18px;
        background: #50cd89;
        border: 3px solid white;
        border-radius: 50%;
    }

    .profile-info {
        flex: 1;
    }

    .profile-name-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .profile-name {
        font-size: 28px;
        font-weight: 700;
        color: #181c32;
        margin: 0;
    }

    .verified-badge {
        color: #7239ea;
        font-size: 22px;
    }

    .upgrade-badge {
        background: #e8fff3;
        color: #50cd89;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-meta {
        display: flex;
        gap: 20px;
        color: #a1a5b7;
        font-size: 14px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .profile-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .profile-meta-item i {
        color: #a1a5b7;
    }

    .stats-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .stat-box {
        background: #f9f9f9;
        border-radius: 10px;
        padding: 18px 22px;
        flex: 1;
    }

    .stat-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #a1a5b7;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .stat-label i {
        font-size: 12px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #181c32;
    }

    .stat-caption {
        color: #a1a5b7;
        font-size: 13px;
        margin-top: 2px;
    }

    .details-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #181c32;
        margin: 0;
    }

    .btn-edit-profile {
        background: linear-gradient(135deg, #7239ea 0%, #9d5cff 100%);
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-edit-profile:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(114, 57, 234, 0.3);
    }

    .detail-row {
        display: flex;
        padding: 18px 0;
        border-bottom: 1px solid #eff2f5;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 200px;
        color: #a1a5b7;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-value {
        flex: 1;
        color: #181c32;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .verified-badge-small {
        background: #e8fff3;
        color: #50cd89;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    #pharmacyMap {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        z-index: 1;
    }

    .leaflet-container {
        font-family: 'Segoe UI', Tahoma, Arial, 'Vazir', sans-serif;
    }

    @media (max-width: 991px) {
        .profile-header {
            flex-direction: column;
        }

        .stats-row {
            flex-direction: column;
        }

        .detail-row {
            flex-direction: column;
            gap: 8px;
        }

        .detail-label {
            width: 100%;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">جزئیات داروخانه</h1>
                <p class="breadcrumb-custom">خانه / مدیریت داروخانه / جزئیات داروخانه</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="online-status" style="background: <?php echo $pharmacy['status'] === 'active' ? '#50cd89' : '#f1416c'; ?>;"></div>
                </div>
                
                <div class="profile-info">
                    <div class="profile-name-row">
                        <h2 class="profile-name"><?php echo htmlspecialchars($pharmacy['name']); ?></h2>
                        <i class="fas fa-check-circle verified-badge"></i>
                        <span class="upgrade-badge"><?php echo $pharmacy['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?></span>
                    </div>
                    
                    <div class="profile-meta">
                        <?php if (!empty($pharmacy['license_number'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-id-card"></i>
                            <span>شماره پروانه: <?php echo htmlspecialchars($pharmacy['license_number']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pharmacy['owner_name'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-user"></i>
                            <span>صاحب: <?php echo htmlspecialchars($pharmacy['owner_name']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pharmacy['phone'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-phone"></i>
                            <span><?php echo htmlspecialchars($pharmacy['phone']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pharmacy['address'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo htmlspecialchars($pharmacy['address']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="stats-row">
                        <?php if (!empty($pharmacy['license_number'])): ?>
                        <div class="stat-box">
                            <div class="stat-label">
                                <i class="fas fa-id-card"></i>
                                <span>شماره پروانه</span>
                            </div>
                            <div class="stat-value"><?php echo htmlspecialchars($pharmacy['license_number']); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($pharmacy['created_at'])): ?>
                        <div class="stat-box">
                            <div class="stat-label">
                                <i class="fas fa-calendar"></i>
                                <span>تاریخ ثبت</span>
                            </div>
                            <div class="stat-value"><?php echo toPersianDate($pharmacy['created_at'], 'Y'); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="stat-box">
                            <div class="stat-label">
                                <i class="fas fa-user-check"></i>
                                <span>وضعیت</span>
                            </div>
                            <div class="stat-value"><?php echo $pharmacy['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="details-section">
            <div class="section-header">
                <h3 class="section-title">جزئیات داروخانه</h3>
                <button class="btn-edit-profile" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/edit/<?php echo $pharmacy['id']; ?>'">ویرایش اطلاعات</button>
            </div>
            <div class="detail-row">
                <div class="detail-label">نام داروخانه</div>
                <div class="detail-value"><?php echo htmlspecialchars($pharmacy['name']); ?></div>
            </div>
            <?php if (!empty($pharmacy['license_number'])): ?>
            <div class="detail-row">
                <div class="detail-label">شماره پروانه</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars($pharmacy['license_number']); ?>
                    <span class="verified-badge-small">معتبر</span>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!empty($pharmacy['owner_name'])): ?>
            <div class="detail-row">
                <div class="detail-label">صاحب داروخانه</div>
                <div class="detail-value"><?php echo htmlspecialchars($pharmacy['owner_name']); ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($pharmacy['owner_national_code'])): ?>
            <div class="detail-row">
                <div class="detail-label">کد ملی صاحب</div>
                <div class="detail-value"><?php echo htmlspecialchars($pharmacy['owner_national_code']); ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($pharmacy['mobile'])): ?>
            <div class="detail-row">
                <div class="detail-label">شماره موبایل</div>
                <div class="detail-value"><?php echo htmlspecialchars($pharmacy['mobile']); ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($pharmacy['phone'])): ?>
            <div class="detail-row">
                <div class="detail-label">تلفن</div>
                <div class="detail-value"><?php echo htmlspecialchars($pharmacy['phone']); ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($pharmacy['created_at'])): ?>
            <div class="detail-row">
                <div class="detail-label">تاریخ ثبت</div>
                <div class="detail-value"><?php echo toPersianDate($pharmacy['created_at']); ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($pharmacy['province']) || !empty($pharmacy['city']) || !empty($pharmacy['district'])): ?>
            <div class="detail-row">
                <div class="detail-label">منطقه</div>
                <div class="detail-value">
                    <?php 
                    $location = array_filter([$pharmacy['province'] ?? '', $pharmacy['city'] ?? '', $pharmacy['district'] ?? '']);
                    echo htmlspecialchars(implode('، ', $location));
                    ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!empty($pharmacy['address'])): ?>
            <div class="detail-row">
                <div class="detail-label">آدرس</div>
                <div class="detail-value"><?php echo htmlspecialchars($pharmacy['address']); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Map Section -->
        <?php if (!empty($pharmacy['latitude']) && !empty($pharmacy['longitude'])): ?>
        <div class="details-section">
            <div class="section-header">
                <h3 class="section-title">موقعیت روی نقشه</h3>
            </div>
            <div id="pharmacyMap"></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($pharmacy['latitude']) && !empty($pharmacy['longitude'])): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize Pharmacy Map
    let pharmacyMap;
    function initPharmacyMap() {
        const lat = <?php echo floatval($pharmacy['latitude']); ?>;
        const lng = <?php echo floatval($pharmacy['longitude']); ?>;
        
        pharmacyMap = L.map('pharmacyMap').setView([lat, lng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(pharmacyMap);
        
        // Add marker for pharmacy location
        const customIcon = L.divIcon({
            className: 'custom-pharmacy-icon',
            html: `<div style="background: #7239ea; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-pills"></i></div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
        
        const pharmacyMarker = L.marker([lat, lng], { icon: customIcon }).addTo(pharmacyMap);
        pharmacyMarker.bindPopup('<b><?php echo htmlspecialchars($pharmacy['name'], ENT_QUOTES); ?></b><br><?php echo htmlspecialchars($pharmacy['address'] ?? '', ENT_QUOTES); ?>').openPopup();
    }

    // Initialize map on page load
    document.addEventListener('DOMContentLoaded', function() {
        initPharmacyMap();
    });
</script>
<?php endif; ?>

