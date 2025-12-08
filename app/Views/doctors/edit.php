<?php
/** @var array $doctor */
/** @var array $specialties */
/** @var array $errors */
$pageTitle = 'ویرایش پزشک - سیستم مدیریت پزشکان';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #clinicLocationMap {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e4e6ef;
        margin-top: 10px;
    }
    .leaflet-container {
        font-family: 'Segoe UI', Tahoma, Arial, 'Vazir', sans-serif;
    }
</style>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">ویرایش پزشک</h1>
                <p class="breadcrumb-custom">خانه / مدیریت پزشکان / ویرایش پزشک</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom" style="padding: 0;">
            <!-- Tabs Navigation -->
            <div class="form-tabs-container">
                <button class="form-tab-btn active" onclick="switchFormTab('identity', event)">
                    <i class="fas fa-id-card"></i>
                    اطلاعات فردی
                </button>
                <button class="form-tab-btn" onclick="switchFormTab('professional', event)">
                    <i class="fas fa-user-md"></i>
                    اطلاعات پزشکی
                </button>
                <button class="form-tab-btn" onclick="switchFormTab('contact', event)">
                    <i class="fas fa-phone"></i>
                    اطلاعات تماس
                </button>
                <button class="form-tab-btn" onclick="switchFormTab('location', event)">
                    <i class="fas fa-map-marker-alt"></i>
                    اطلاعات آدرس
                </button>
                <button class="form-tab-btn" onclick="switchFormTab('organizational', event)">
                    <i class="fas fa-building"></i>
                    اطلاعات سازمانی
                </button>
            </div>

            <form id="editDoctorForm" method="POST" action="<?php echo $baseUrl; ?>/doctors/update/<?php echo $doctor['id']; ?>" enctype="multipart/form-data" style="padding: 30px;">
                <!-- Tab 1: اطلاعات فردی -->
                <div class="form-tab-content active" id="identityTab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modal">نام <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="firstName" id="firstName" placeholder="نام را وارد کنید" value="<?php echo htmlspecialchars($doctor['first_name'] ?? ''); ?>" required>
                            <div class="error-message-modal" id="firstNameError"><?php echo $errors['firstName'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">نام خانوادگی <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="lastName" id="lastName" placeholder="نام خانوادگی را وارد کنید" value="<?php echo htmlspecialchars($doctor['last_name'] ?? ''); ?>" required>
                            <div class="error-message-modal" id="lastNameError"><?php echo $errors['lastName'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">کد ملی <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="nationalCode" id="nationalCode" placeholder="کد ملی را وارد کنید" maxlength="10" value="<?php echo htmlspecialchars($doctor['national_code'] ?? ''); ?>" required>
                            <div class="error-message-modal" id="nationalCodeError"><?php echo $errors['nationalCode'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره شناسنامه</label>
                            <input type="text" class="form-control-modal" name="idNumber" id="idNumber" placeholder="شماره شناسنامه را وارد کنید" value="<?php echo htmlspecialchars($doctor['id_number'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">تاریخ تولد</label>
                            <input type="date" class="form-control-modal" name="birthDate" id="birthDate" value="<?php echo htmlspecialchars($doctor['birth_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">جنسیت <span class="required">*</span></label>
                            <select class="form-control-modal" name="gender" id="gender" required>
                                <option value="">انتخاب کنید</option>
                                <option value="male" <?php echo ($doctor['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>مرد</option>
                                <option value="female" <?php echo ($doctor['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>زن</option>
                            </select>
                            <div class="error-message-modal" id="genderError"><?php echo $errors['gender'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">نام پدر</label>
                            <input type="text" class="form-control-modal" name="fatherName" id="fatherName" placeholder="نام پدر را وارد کنید" value="<?php echo htmlspecialchars($doctor['father_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">وضعیت حیات</label>
                            <select class="form-control-modal" name="isDeceased" id="lifeStatus">
                                <option value="0" <?php echo ($doctor['is_deceased'] ?? 0) == 0 ? 'selected' : ''; ?>>زنده</option>
                                <option value="1" <?php echo ($doctor['is_deceased'] ?? 0) == 1 ? 'selected' : ''; ?>>فوت شده</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: اطلاعات پزشکی -->
                <div class="form-tab-content" id="professionalTab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره نظام پزشکی <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="medicalLicense" id="medicalLicense" placeholder="شماره نظام پزشکی را وارد کنید" value="<?php echo htmlspecialchars($doctor['medical_license'] ?? ''); ?>" required>
                            <div class="error-message-modal" id="medicalLicenseError"><?php echo $errors['medicalLicense'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">تخصص <span class="required">*</span></label>
                            <select class="form-control-modal" name="specialtyId" id="specialty" required>
                                <option value="">انتخاب تخصص</option>
                                <?php foreach ($specialties as $specialty): ?>
                                    <option value="<?php echo $specialty['id']; ?>" <?php echo ($doctor['specialty_id'] ?? '') == $specialty['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($specialty['name_fa']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="error-message-modal" id="specialtyError"><?php echo $errors['specialtyId'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">وضعیت شغلی</label>
                            <select class="form-control-modal" name="employmentStatus" id="jobStatus">
                                <option value="">انتخاب کنید</option>
                                <option value="active" <?php echo ($doctor['employment_status'] ?? '') === 'active' ? 'selected' : ''; ?>>فعال</option>
                                <option value="inactive" <?php echo ($doctor['employment_status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                                <option value="retired" <?php echo ($doctor['employment_status'] ?? '') === 'retired' ? 'selected' : ''; ?>>بازنشسته</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">عضویت (سازمان نظام پزشکی)</label>
                            <input type="text" class="form-control-modal" name="medicalOrgMembership" id="membership" placeholder="عضویت سازمان نظام پزشکی" value="<?php echo htmlspecialchars($doctor['medical_org_membership'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Tab 3: اطلاعات تماس -->
                <div class="form-tab-content" id="contactTab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modal">موبایل</label>
                            <input type="tel" class="form-control-modal" name="mobile" id="mobile" placeholder="09123456789" maxlength="11" value="<?php echo htmlspecialchars($doctor['mobile'] ?? ''); ?>">
                            <div class="error-message-modal" id="mobileError"><?php echo $errors['mobile'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره مطب</label>
                            <input type="tel" class="form-control-modal" name="clinicPhone" id="clinicPhone" placeholder="021-12345678" value="<?php echo htmlspecialchars($doctor['clinic_phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره منزل</label>
                            <input type="tel" class="form-control-modal" name="homePhone" id="homePhone" placeholder="021-87654321" value="<?php echo htmlspecialchars($doctor['home_phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">ایمیل</label>
                            <input type="email" class="form-control-modal" name="email" id="email" placeholder="email@example.com" value="<?php echo htmlspecialchars($doctor['email'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Tab 4: اطلاعات آدرس -->
                <div class="form-tab-content" id="locationTab">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="fromQom" id="fromQom" value="1" <?php echo ($doctor['from_qom'] ?? 0) == 1 ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="fromQom">
                                    از قم رفته
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">آدرس پستی مطب</label>
                            <textarea class="form-control-modal" name="clinicPostalAddress" id="clinicPostalAddress" rows="3" placeholder="آدرس پستی مطب را وارد کنید"><?php echo htmlspecialchars($doctor['clinic_postal_address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">آدرس محل کار</label>
                            <textarea class="form-control-modal" name="workAddress" id="workAddress" rows="3" placeholder="آدرس محل کار را وارد کنید"><?php echo htmlspecialchars($doctor['work_address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">آدرس پستی منزل</label>
                            <textarea class="form-control-modal" name="homePostalAddress" id="homePostalAddress" rows="3" placeholder="آدرس پستی منزل را وارد کنید"><?php echo htmlspecialchars($doctor['home_postal_address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">مکان نقشه مطب</label>
                            <p style="font-size: 13px; color: #a1a5b7; margin-bottom: 10px;">برای انتخاب موقعیت مطب، روی نقشه کلیک کنید</p>
                            <div id="clinicLocationMap"></div>
                            <input type="hidden" name="clinicLatitude" id="clinicLatitude" value="<?php echo htmlspecialchars($doctor['clinic_latitude'] ?? ''); ?>">
                            <input type="hidden" name="clinicLongitude" id="clinicLongitude" value="<?php echo htmlspecialchars($doctor['clinic_longitude'] ?? ''); ?>">
                            <small class="text-muted mt-2 d-block">مختصات انتخاب شده: <span id="coordinatesDisplay">-</span></small>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: اطلاعات سازمانی -->
                <div class="form-tab-content" id="organizationalTab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modal">مرکز درمانی</label>
                            <select class="form-control-modal" name="clinicName" id="clinic">
                                <option value="">انتخاب کنید</option>
                                <?php if (isset($medicalCenters) && !empty($medicalCenters)): ?>
                                    <?php foreach ($medicalCenters as $center): ?>
                                        <option value="<?php echo htmlspecialchars($center['name']); ?>" 
                                            <?php echo (isset($doctor['clinic_name']) && $doctor['clinic_name'] === $center['name']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($center['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">وضعیت <span class="required">*</span></label>
                            <select class="form-control-modal" name="status" id="status" required>
                                <option value="active" <?php echo ($doctor['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>فعال</option>
                                <option value="inactive" <?php echo ($doctor['status'] ?? 'active') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">توضیحات</label>
                            <textarea class="form-control-modal" name="description" id="description" rows="4" placeholder="توضیحات را وارد کنید"><?php echo htmlspecialchars($doctor['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">تاریخ ثبت</label>
                            <input type="date" class="form-control-modal" name="registrationDate" id="registrationDate" value="<?php echo htmlspecialchars($doctor['registration_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره پرونده</label>
                            <input type="text" class="form-control-modal" name="fileNumber" id="fileNumber" placeholder="شماره پرونده را وارد کنید" value="<?php echo htmlspecialchars($doctor['file_number'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">تصویر پزشک</label>
                            <?php if (!empty($doctor['image'])): ?>
                                <div class="mb-3">
                                    <img src="<?php echo $baseUrl; ?>/uploads/<?php echo htmlspecialchars($doctor['image']); ?>" alt="تصویر فعلی" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #e4e6ef;">
                                    <p class="text-muted mt-2" style="font-size: 13px;">تصویر فعلی</p>
                                </div>
                            <?php endif; ?>
                            <div class="image-upload-wrapper">
                                <input type="file" class="image-input" name="image" id="doctorImage" accept="image/*" onchange="previewImage(this)">
                                <label for="doctorImage" class="image-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>برای تغییر تصویر کلیک کنید</span>
                                </label>
                                <div class="image-preview" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview">
                                    <button type="button" class="remove-image" onclick="removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn-modal-cancel" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/list'">انصراف</button>
                    <button type="submit" class="btn-modal-submit">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let clinicLocationMap;
    let clinicLocationMarker;
    let mapInitialized = false;

    window.switchFormTab = function(tabName, event) {
        document.querySelectorAll('.form-tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.form-tab-btn').forEach(btn => btn.classList.remove('active'));
        
        const targetTab = document.getElementById(tabName + 'Tab');
        if (targetTab) {
            targetTab.classList.add('active');
        }
        
        if (event && event.target) {
            event.target.closest('.form-tab-btn').classList.add('active');
        } else {
            // Fallback: find button by tab name
            const buttons = document.querySelectorAll('.form-tab-btn');
            buttons.forEach(btn => {
                if (btn.onclick && btn.onclick.toString().includes(tabName)) {
                    btn.classList.add('active');
                }
            });
        }

        if (tabName === 'location') {
            // Wait for tab to be fully visible before initializing map
            setTimeout(() => {
                initializeMapWhenVisible();
            }, 100);
        }
    }

    window.initializeMapWhenVisible = function(retryCount = 0) {
        const maxRetries = 10;
        const mapElement = document.getElementById('clinicLocationMap');
        
        if (!mapElement) {
            console.error('Map element not found');
            return;
        }

        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded');
            if (retryCount < maxRetries) {
                setTimeout(() => {
                    initializeMapWhenVisible(retryCount + 1);
                }, 200);
            }
            return;
        }

        const isVisible = mapElement.offsetParent !== null && 
                         mapElement.offsetWidth > 0 && 
                         mapElement.offsetHeight > 0;
        
        if (!isVisible) {
            if (retryCount < maxRetries) {
                setTimeout(() => {
                    initializeMapWhenVisible(retryCount + 1);
                }, 200);
            } else {
                console.warn('Map element is not visible after multiple retries, trying anyway...');
                if (!mapInitialized || !clinicLocationMap) {
                    initClinicLocationMap();
                }
            }
            return;
        }

        if (!mapInitialized || !clinicLocationMap) {
            initClinicLocationMap();
        } else {
            clinicLocationMap.invalidateSize();
        }
    }

    window.initClinicLocationMap = function() {
        const mapElement = document.getElementById('clinicLocationMap');
        if (!mapElement) {
            console.error('Map element not found');
            return;
        }

        if (clinicLocationMap) {
            try {
                clinicLocationMap.remove();
            } catch (e) {
                console.warn('Error removing map:', e);
            }
            clinicLocationMap = null;
            clinicLocationMarker = null;
        }

        const existingLat = document.getElementById('clinicLatitude').value;
        const existingLng = document.getElementById('clinicLongitude').value;
        
        let initialLat = 35.6892;
        let initialLng = 51.3890;
        let initialZoom = 13;
        
        if (existingLat && existingLng) {
            initialLat = parseFloat(existingLat);
            initialLng = parseFloat(existingLng);
            initialZoom = 15;
        }
        
        try {
            clinicLocationMap = L.map('clinicLocationMap', {
                zoomControl: true
            }).setView([initialLat, initialLng], initialZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(clinicLocationMap);

            if (existingLat && existingLng) {
                const lat = parseFloat(existingLat);
                const lng = parseFloat(existingLng);
                
                const customIcon = L.divIcon({
                    className: 'custom-clinic-icon',
                    html: `<div style="background: #50cd89; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-hospital"></i></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });
                
                clinicLocationMarker = L.marker([lat, lng], { icon: customIcon }).addTo(clinicLocationMap);
                clinicLocationMarker.bindPopup('موقعیت مطب انتخاب شده').openPopup();
                const coordsDisplay = document.getElementById('coordinatesDisplay');
                if (coordsDisplay) {
                    coordsDisplay.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                }
            } else {
                const coordsDisplay = document.getElementById('coordinatesDisplay');
                if (coordsDisplay) {
                    coordsDisplay.textContent = '-';
                }
            }

            clinicLocationMap.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                document.getElementById('clinicLatitude').value = lat;
                document.getElementById('clinicLongitude').value = lng;
                const coordsDisplay = document.getElementById('coordinatesDisplay');
                if (coordsDisplay) {
                    coordsDisplay.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                }
                
                if (clinicLocationMarker) {
                    clinicLocationMap.removeLayer(clinicLocationMarker);
                }
                
                const customIcon = L.divIcon({
                    className: 'custom-clinic-icon',
                    html: `<div style="background: #50cd89; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-hospital"></i></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });
                
                clinicLocationMarker = L.marker([lat, lng], { icon: customIcon }).addTo(clinicLocationMap);
                clinicLocationMarker.bindPopup('موقعیت مطب انتخاب شده').openPopup();
            });

            mapInitialized = true;
            
            setTimeout(() => {
                if (clinicLocationMap) {
                    clinicLocationMap.invalidateSize();
                }
            }, 100);
            
            setTimeout(() => {
                if (clinicLocationMap) {
                    clinicLocationMap.invalidateSize();
                }
            }, 500);
            
        } catch (error) {
            console.error('Error initializing map:', error);
            mapInitialized = false;
        }
    }

    window.previewImage = function(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.removeImage = function() {
        document.getElementById('doctorImage').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('previewImg').src = '';
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

