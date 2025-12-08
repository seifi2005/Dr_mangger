<?php
/** @var array $doctor */
/** @var array $payments */
$pageTitle = 'جزئیات پزشک - سیستم مدیریت پزشکان';
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
    .stats-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    .stat-box {
        background: #f9f9f9;
        padding: 15px 20px;
        border-radius: 8px;
        min-width: 150px;
    }
    .stat-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #a1a5b7;
        font-size: 12px;
        margin-bottom: 8px;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #181c32;
    }
    .tabs-container {
        display: flex;
        gap: 10px;
        padding: 20px 30px;
        border-bottom: 2px solid #eff2f5;
        background: white;
        border-radius: 12px 12px 0 0;
        flex-wrap: wrap;
    }
    .tab-btn {
        background: transparent;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #a1a5b7;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tab-btn.active {
        background: #f1edff;
        color: #7239ea;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
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
    }
    #clinicMap {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
    }
    .payment-form {
        margin-bottom: 30px;
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
                <h1 class="page-title">جزئیات پزشک</h1>
                <p class="breadcrumb-custom">خانه / مدیریت پزشکان / جزئیات پزشک</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar-wrapper">
                    <?php if (!empty($doctor['image'])): ?>
                        <img src="<?php echo $baseUrl; ?>/uploads/<?php echo htmlspecialchars($doctor['image']); ?>" alt="<?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?>" class="profile-avatar">
                    <?php else: ?>
                        <img src="https://i.pravatar.cc/160?img=<?php echo $doctor['id']; ?>" alt="<?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?>" class="profile-avatar">
                    <?php endif; ?>
                    <div class="online-status"></div>
                </div>
                
                <div class="profile-info">
                    <div class="profile-name-row">
                        <h2 class="profile-name">دکتر <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></h2>
                        <i class="fas fa-check-circle verified-badge"></i>
                        <span class="upgrade-badge"><?php echo $doctor['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?></span>
                    </div>
                    
                    <div class="profile-meta">
                        <?php if (!empty($doctor['specialty_name'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-stethoscope"></i>
                            <span><?php echo htmlspecialchars($doctor['specialty_name']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($doctor['clinic_name'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-hospital"></i>
                            <span><?php echo htmlspecialchars($doctor['clinic_name']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($doctor['email'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-envelope"></i>
                            <span><?php echo htmlspecialchars($doctor['email']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($doctor['mobile'])): ?>
                        <div class="profile-meta-item">
                            <i class="fas fa-phone"></i>
                            <span><?php echo htmlspecialchars($doctor['mobile']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="stats-row">
                        <div class="stat-box">
                            <div class="stat-label">
                                <i class="fas fa-id-card"></i>
                                <span>شماره نظام پزشکی</span>
                            </div>
                            <div class="stat-value"><?php echo htmlspecialchars($doctor['medical_license']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="details-section" style="padding: 0;">
            <div class="tabs-container" style="flex-wrap: wrap;">
                <button class="tab-btn active" onclick="switchTab('identity')">
                    <i class="fas fa-id-card"></i>
                    اطلاعات فردی
                </button>
                <button class="tab-btn" onclick="switchTab('professional')">
                    <i class="fas fa-user-md"></i>
                    اطلاعات پزشکی
                </button>
                <button class="tab-btn" onclick="switchTab('contact')">
                    <i class="fas fa-phone"></i>
                    اطلاعات تماس
                </button>
                <button class="tab-btn" onclick="switchTab('location')">
                    <i class="fas fa-map-marker-alt"></i>
                    اطلاعات آدرس
                </button>
                <button class="tab-btn" onclick="switchTab('organizational')">
                    <i class="fas fa-building"></i>
                    اطلاعات سازمانی
                </button>
                <button class="tab-btn" onclick="switchTab('payments')">
                    <i class="fas fa-money-bill-wave"></i>
                    پرداخت
                </button>
            </div>
        </div>

        <!-- Tab 1: اطلاعات فردی -->
        <div class="tab-content active" id="identityTab">
            <div class="details-section">
                <div class="section-header">
                    <h3 class="section-title">اطلاعات فردی</h3>
                    <a href="<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>" class="btn-edit-profile">ویرایش</a>
                </div>
                <div class="detail-row">
                    <div class="detail-label">نام</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['first_name']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">نام خانوادگی</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['last_name']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">کد ملی</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['national_code']); ?></div>
                </div>
                <?php if (!empty($doctor['id_number'])): ?>
                <div class="detail-row">
                    <div class="detail-label">شماره شناسنامه</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['id_number']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['birth_date'])): ?>
                <div class="detail-row">
                    <div class="detail-label">تاریخ تولد</div>
                    <div class="detail-value"><?php echo toPersianDate($doctor['birth_date']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['gender'])): ?>
                <div class="detail-row">
                    <div class="detail-label">جنسیت</div>
                    <div class="detail-value"><?php echo $doctor['gender'] === 'male' ? 'مرد' : 'زن'; ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['father_name'])): ?>
                <div class="detail-row">
                    <div class="detail-label">نام پدر</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['father_name']); ?></div>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <div class="detail-label">وضعیت حیات</div>
                    <div class="detail-value"><?php echo $doctor['is_deceased'] ? 'فوت شده' : 'زنده'; ?></div>
                </div>
            </div>
        </div>

        <!-- Tab 2: اطلاعات پزشکی -->
        <div class="tab-content" id="professionalTab">
            <div class="details-section">
                <div class="section-header">
                    <h3 class="section-title">اطلاعات پزشکی</h3>
                    <a href="<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>" class="btn-edit-profile">ویرایش</a>
                </div>
                <div class="detail-row">
                    <div class="detail-label">شماره نظام پزشکی</div>
                    <div class="detail-value">
                        <?php echo htmlspecialchars($doctor['medical_license']); ?>
                        <span class="verified-badge-small">تایید شده</span>
                    </div>
                </div>
                <?php if (!empty($doctor['specialty_name'])): ?>
                <div class="detail-row">
                    <div class="detail-label">تخصص</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['specialty_name']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['employment_status'])): ?>
                <div class="detail-row">
                    <div class="detail-label">وضعیت شغلی</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['employment_status']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['medical_org_membership'])): ?>
                <div class="detail-row">
                    <div class="detail-label">عضویت (سازمان نظام پزشکی)</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['medical_org_membership']); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab 3: اطلاعات تماس -->
        <div class="tab-content" id="contactTab">
            <div class="details-section">
                <div class="section-header">
                    <h3 class="section-title">اطلاعات تماس</h3>
                    <a href="<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>" class="btn-edit-profile">ویرایش</a>
                </div>
                <?php if (!empty($doctor['mobile'])): ?>
                <div class="detail-row">
                    <div class="detail-label">موبایل</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['mobile']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['clinic_phone'])): ?>
                <div class="detail-row">
                    <div class="detail-label">شماره مطب</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['clinic_phone']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['home_phone'])): ?>
                <div class="detail-row">
                    <div class="detail-label">شماره منزل</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['home_phone']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['email'])): ?>
                <div class="detail-row">
                    <div class="detail-label">ایمیل</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['email']); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab 4: اطلاعات آدرس -->
        <div class="tab-content" id="locationTab">
            <div class="details-section">
                <div class="section-header">
                    <h3 class="section-title">اطلاعات آدرس</h3>
                    <a href="<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>" class="btn-edit-profile">ویرایش</a>
                </div>
                <div class="detail-row">
                    <div class="detail-label">از قم رفته</div>
                    <div class="detail-value">
                        <span class="badge-<?php echo $doctor['from_qom'] ? 'enabled' : 'disabled'; ?>">
                            <?php echo $doctor['from_qom'] ? 'بله' : 'خیر'; ?>
                        </span>
                    </div>
                </div>
                <?php if (!empty($doctor['clinic_postal_address'])): ?>
                <div class="detail-row">
                    <div class="detail-label">آدرس پستی مطب</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['clinic_postal_address']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['work_address'])): ?>
                <div class="detail-row">
                    <div class="detail-label">آدرس محل کار</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['work_address']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['home_postal_address'])): ?>
                <div class="detail-row">
                    <div class="detail-label">آدرس پستی منزل</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['home_postal_address']); ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Map Section -->
            <?php if (!empty($doctor['clinic_latitude']) && !empty($doctor['clinic_longitude'])): ?>
            <div class="details-section" style="margin-top: 20px;">
                <div class="section-header">
                    <h3 class="section-title">مکان نقشه مطب</h3>
                </div>
                <div id="clinicMap" style="height: 400px; border-radius: 8px; overflow: hidden;"></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tab 5: اطلاعات سازمانی -->
        <div class="tab-content" id="organizationalTab">
            <div class="details-section">
                <div class="section-header">
                    <h3 class="section-title">اطلاعات سازمانی</h3>
                    <a href="<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>" class="btn-edit-profile">ویرایش</a>
                </div>
                <?php if (!empty($doctor['clinic_name'])): ?>
                <div class="detail-row">
                    <div class="detail-label">درمانگاه</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['clinic_name']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['description'])): ?>
                <div class="detail-row">
                    <div class="detail-label">توضیحات</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['description']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['registration_date'])): ?>
                <div class="detail-row">
                    <div class="detail-label">تاریخ ثبت</div>
                    <div class="detail-value"><?php echo toPersianDate($doctor['registration_date']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($doctor['file_number'])): ?>
                <div class="detail-row">
                    <div class="detail-label">شماره پرونده</div>
                    <div class="detail-value"><?php echo htmlspecialchars($doctor['file_number']); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payments Tab -->
        <div class="tab-content" id="paymentsTab">
            <!-- Add Payment Section -->
            <div class="details-section">
                <div class="section-header">
                    <h3 class="section-title">افزودن پرداخت</h3>
                </div>
                <form id="addPaymentForm" class="payment-form" method="POST" action="<?php echo $baseUrl; ?>/doctors/add-payment" enctype="multipart/form-data">
                    <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label-modal">شماره رسید <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="receipt_number" id="receiptNumber" placeholder="شماره رسید را وارد کنید" required>
                            <div class="error-message-modal" id="receiptNumberError"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-modal">تاریخ <span class="required">*</span></label>
                            <input type="date" class="form-control-modal" name="payment_date" id="paymentDate" required>
                            <div class="error-message-modal" id="paymentDateError"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-modal">مبلغ (تومان) <span class="required">*</span></label>
                            <input type="number" class="form-control-modal" name="amount" id="paymentAmount" placeholder="مبلغ پرداخت" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label-modal">تصویر رسید <span class="required">*</span></label>
                            <div class="image-upload-wrapper">
                                <input type="file" class="image-input" name="receipt_image" id="receiptImage" accept="image/*" required onchange="previewReceiptImage(this)">
                                <label for="receiptImage" class="image-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>برای آپلود تصویر رسید کلیک کنید</span>
                                </label>
                                <div class="image-preview" id="receiptPreview" style="display: none;">
                                    <img id="receiptPreviewImg" src="" alt="پیش‌نمایش تصویر">
                                    <button type="button" class="remove-image" onclick="removeReceiptImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="error-message-modal" id="receiptImageError"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn-modal-cancel" onclick="resetPaymentForm()">انصراف</button>
                        <button type="submit" class="btn-modal-submit">
                            <i class="fas fa-save"></i>
                            ثبت پرداخت
                        </button>
                    </div>
                </form>
            </div>

            <!-- Payments List -->
            <div class="details-section">
                <div class="section-header">
                    <h3 class="section-title">لیست پرداخت‌ها</h3>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>شماره رسید</th>
                                <th>تاریخ</th>
                                <th>مبلغ</th>
                                <th>تصویر رسید</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTableBody">
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($payment['receipt_number']); ?></strong></td>
                                    <td><?php echo toPersianDate($payment['payment_date']); ?></td>
                                    <td><strong style="color: #50cd89;"><?php echo number_format($payment['amount']); ?> تومان</strong></td>
                                    <td>
                                        <?php if (!empty($payment['receipt_image'])): ?>
                                        <button class="btn-action" onclick="viewReceiptImage('<?php echo $baseUrl; ?>/uploads/<?php echo htmlspecialchars($payment['receipt_image']); ?>')">
                                            <i class="fas fa-eye"></i>
                                            مشاهده
                                        </button>
                                        <?php else: ?>
                                        <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn-action" onclick="deletePayment(<?php echo $payment['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">هیچ پرداختی ثبت نشده است</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let clinicMap;
    
    function initClinicMap() {
        <?php if (!empty($doctor['clinic_latitude']) && !empty($doctor['clinic_longitude'])): ?>
        if (!clinicMap) {
            clinicMap = L.map('clinicMap').setView([<?php echo $doctor['clinic_latitude']; ?>, <?php echo $doctor['clinic_longitude']; ?>], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(clinicMap);
            
            const customIcon = L.divIcon({
                className: 'custom-clinic-icon',
                html: `<div style="background: #50cd89; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-hospital"></i></div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
            
            const clinicMarker = L.marker([<?php echo $doctor['clinic_latitude']; ?>, <?php echo $doctor['clinic_longitude']; ?>], { icon: customIcon }).addTo(clinicMap);
            clinicMarker.bindPopup('<b>مکان مطب</b>').openPopup();
        }
        <?php endif; ?>
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName + 'Tab').classList.add('active');
        event.target.closest('.tab-btn').classList.add('active');

        if (tabName === 'location') {
            setTimeout(() => {
                initClinicMap();
            }, 100);
        }
    }

    function previewReceiptImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('receiptPreviewImg').src = e.target.result;
                document.getElementById('receiptPreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeReceiptImage() {
        document.getElementById('receiptImage').value = '';
        document.getElementById('receiptPreview').style.display = 'none';
        document.getElementById('receiptPreviewImg').src = '';
    }

    function resetPaymentForm() {
        document.getElementById('addPaymentForm').reset();
        removeReceiptImage();
    }

    function viewReceiptImage(imageUrl) {
        window.open(imageUrl, '_blank');
    }

    function deletePayment(id) {
        if (!confirm('آیا از حذف این پرداخت اطمینان دارید؟')) {
            return;
        }

        fetch('<?php echo $baseUrl; ?>/doctors/delete-payment/' + id, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('خطا: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در حذف پرداخت');
        });
    }

    // Handle payment form submission with AJAX
    document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('<?php echo $baseUrl; ?>/doctors/add-payment', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                this.reset();
                removeReceiptImage();
                location.reload();
            } else {
                alert('خطا: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در ثبت پرداخت');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('locationTab').classList.contains('active')) {
            setTimeout(() => {
                initClinicMap();
            }, 100);
        }
    });
</script>

