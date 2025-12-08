<?php
/** @var array $specialties */
/** @var array $errors */
/** @var array $old */
$pageTitle = 'افزودن پزشک - سیستم مدیریت پزشکان';
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
        font-family: Yekan, Tahoma, Arial, sans-serif;
    }
</style>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">افزودن پزشک جدید</h1>
                <p class="breadcrumb-custom">خانه / مدیریت پزشکان / افزودن پزشک</p>
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

            <form id="addDoctorForm" method="POST" action="<?php echo $baseUrl; ?>/doctors/store" enctype="multipart/form-data" style="padding: 30px;">
                <!-- Tab 1: اطلاعات فردی -->
                <div class="form-tab-content active" id="identityTab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modal">نام <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="firstName" id="firstName" placeholder="نام را وارد کنید" value="<?php echo htmlspecialchars($old['firstName'] ?? ''); ?>" required>
                            <div class="error-message-modal" id="firstNameError"><?php echo $errors['firstName'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">نام خانوادگی <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="lastName" id="lastName" placeholder="نام خانوادگی را وارد کنید" value="<?php echo htmlspecialchars($old['lastName'] ?? ''); ?>" required>
                            <div class="error-message-modal" id="lastNameError"><?php echo $errors['lastName'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">کد ملی <span class="required">*</span></label>
                            <div style="display: flex; gap: 10px;">
                                <input type="text" class="form-control-modal" name="nationalCode" id="nationalCode" placeholder="کد ملی را وارد کنید" maxlength="10" value="<?php echo htmlspecialchars($old['nationalCode'] ?? ''); ?>" required style="flex: 1;" onblur="autoCheckNationalCode()">
                                <button type="button" class="btn-modal-submit" onclick="checkNationalCode()" id="checkNationalCodeBtn" style="white-space: nowrap; padding: 10px 20px;">
                                    <i class="fas fa-search"></i> بررسی
                                </button>
                            </div>
                            <div class="error-message-modal" id="nationalCodeError"><?php echo $errors['nationalCode'] ?? ''; ?></div>
                            <div id="nationalCodeCheckResult" style="margin-top: 5px;"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره شناسنامه</label>
                            <input type="text" class="form-control-modal" name="idNumber" id="idNumber" placeholder="شماره شناسنامه را وارد کنید" value="<?php echo htmlspecialchars($old['idNumber'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">تاریخ تولد</label>
                            <input type="date" class="form-control-modal" name="birthDate" id="birthDate" value="<?php echo htmlspecialchars($old['birthDate'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">جنسیت <span class="required">*</span></label>
                            <select class="form-control-modal" name="gender" id="gender" required>
                                <option value="">انتخاب کنید</option>
                                <option value="male" <?php echo ($old['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>مرد</option>
                                <option value="female" <?php echo ($old['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>زن</option>
                            </select>
                            <div class="error-message-modal" id="genderError"><?php echo $errors['gender'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">نام پدر</label>
                            <input type="text" class="form-control-modal" name="fatherName" id="fatherName" placeholder="نام پدر را وارد کنید" value="<?php echo htmlspecialchars($old['fatherName'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">وضعیت حیات</label>
                            <select class="form-control-modal" name="isDeceased" id="lifeStatus">
                                <option value="0" <?php echo ($old['isDeceased'] ?? '0') === '0' ? 'selected' : ''; ?>>زنده</option>
                                <option value="1" <?php echo ($old['isDeceased'] ?? '0') === '1' ? 'selected' : ''; ?>>فوت شده</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: اطلاعات پزشکی -->
                <div class="form-tab-content" id="professionalTab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره نظام پزشکی <span class="required">*</span></label>
                            <input type="text" class="form-control-modal" name="medicalLicense" id="medicalLicense" placeholder="شماره نظام پزشکی را وارد کنید" value="<?php echo htmlspecialchars($old['medicalLicense'] ?? ''); ?>" required>
                            <div class="error-message-modal" id="medicalLicenseError"><?php echo $errors['medicalLicense'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">تخصص <span class="required">*</span></label>
                            <select class="form-control-modal" name="specialtyId" id="specialty" required>
                                <option value="">انتخاب تخصص</option>
                                <?php foreach ($specialties as $specialty): ?>
                                    <option value="<?php echo $specialty['id']; ?>" <?php echo ($old['specialtyId'] ?? '') == $specialty['id'] ? 'selected' : ''; ?>>
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
                                <option value="active" <?php echo ($old['employmentStatus'] ?? '') === 'active' ? 'selected' : ''; ?>>فعال</option>
                                <option value="inactive" <?php echo ($old['employmentStatus'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                                <option value="retired" <?php echo ($old['employmentStatus'] ?? '') === 'retired' ? 'selected' : ''; ?>>بازنشسته</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">عضویت (سازمان نظام پزشکی)</label>
                            <input type="text" class="form-control-modal" name="medicalOrgMembership" id="membership" placeholder="عضویت سازمان نظام پزشکی" value="<?php echo htmlspecialchars($old['medicalOrgMembership'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Tab 3: اطلاعات تماس -->
                <div class="form-tab-content" id="contactTab">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modal">موبایل</label>
                            <input type="tel" class="form-control-modal" name="mobile" id="mobile" placeholder="09123456789" maxlength="11" value="<?php echo htmlspecialchars($old['mobile'] ?? ''); ?>">
                            <div class="error-message-modal" id="mobileError"><?php echo $errors['mobile'] ?? ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره مطب</label>
                            <input type="tel" class="form-control-modal" name="clinicPhone" id="clinicPhone" placeholder="021-12345678" value="<?php echo htmlspecialchars($old['clinicPhone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره منزل</label>
                            <input type="tel" class="form-control-modal" name="homePhone" id="homePhone" placeholder="021-87654321" value="<?php echo htmlspecialchars($old['homePhone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">ایمیل</label>
                            <input type="email" class="form-control-modal" name="email" id="email" placeholder="email@example.com" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Tab 4: اطلاعات آدرس -->
                <div class="form-tab-content" id="locationTab">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="fromQom" id="fromQom" value="1" <?php echo ($old['fromQom'] ?? '') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="fromQom">
                                    از قم رفته
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">آدرس پستی مطب</label>
                            <textarea class="form-control-modal" name="clinicPostalAddress" id="clinicPostalAddress" rows="3" placeholder="آدرس پستی مطب را وارد کنید"><?php echo htmlspecialchars($old['clinicPostalAddress'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">آدرس محل کار</label>
                            <textarea class="form-control-modal" name="workAddress" id="workAddress" rows="3" placeholder="آدرس محل کار را وارد کنید"><?php echo htmlspecialchars($old['workAddress'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">آدرس پستی منزل</label>
                            <textarea class="form-control-modal" name="homePostalAddress" id="homePostalAddress" rows="3" placeholder="آدرس پستی منزل را وارد کنید"><?php echo htmlspecialchars($old['homePostalAddress'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">مکان نقشه مطب</label>
                            <p style="font-size: 13px; color: #a1a5b7; margin-bottom: 10px;">برای انتخاب موقعیت مطب، روی نقشه کلیک کنید</p>
                            <div id="clinicLocationMap"></div>
                            <input type="hidden" name="clinicLatitude" id="clinicLatitude" value="<?php echo htmlspecialchars($old['clinicLatitude'] ?? ''); ?>">
                            <input type="hidden" name="clinicLongitude" id="clinicLongitude" value="<?php echo htmlspecialchars($old['clinicLongitude'] ?? ''); ?>">
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
                                            <?php echo (isset($old['clinicName']) && $old['clinicName'] === $center['name']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($center['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">توضیحات</label>
                            <textarea class="form-control-modal" name="description" id="description" rows="4" placeholder="توضیحات را وارد کنید"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">تاریخ ثبت</label>
                            <input type="date" class="form-control-modal" name="registrationDate" id="registrationDate" value="<?php echo htmlspecialchars($old['registrationDate'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modal">شماره پرونده</label>
                            <input type="text" class="form-control-modal" name="fileNumber" id="fileNumber" placeholder="شماره پرونده را وارد کنید" value="<?php echo htmlspecialchars($old['fileNumber'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label-modal">تصویر پزشک</label>
                            <div class="image-upload-wrapper">
                                <input type="file" class="image-input" name="image" id="doctorImage" accept="image/*" onchange="previewImage(this)">
                                <label for="doctorImage" class="image-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>برای آپلود تصویر کلیک کنید</span>
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
                    <button type="submit" class="btn-modal-submit" id="submitDoctorBtn">ثبت پزشک</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    console.log('=== Doctor Add Page - Script Loading ===');
    console.log('Leaflet loaded:', typeof L !== 'undefined');
    console.log('Document ready state:', document.readyState);
    
    let clinicLocationMap;
    let clinicLocationMarker;
    let mapInitialized = false;
    
    // Test if function is accessible
    window.testFunction = function() {
        console.log('Test function called!');
        alert('Test function works!');
    };
    
    console.log('About to define switchFormTab...');

    window.switchFormTab = function(tabName, event) {
        console.log('===== switchFormTab called =====');
        console.log('Tab name:', tabName);
        
        document.querySelectorAll('.form-tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.form-tab-btn').forEach(btn => btn.classList.remove('active'));
        
        const targetTab = document.getElementById(tabName + 'Tab');
        console.log('Target tab element:', targetTab);
        
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
            console.log('Location tab activated, will initialize map...');
            // Wait for tab to be fully visible before initializing map
            setTimeout(() => {
                initializeMapWhenVisible();
            }, 100);
        }
    }

    window.initializeMapWhenVisible = function(retryCount = 0) {
        console.log('===== initializeMapWhenVisible called =====');
        console.log('Retry count:', retryCount);
        
        const maxRetries = 10;
        const mapElement = document.getElementById('clinicLocationMap');
        
        console.log('Map element:', mapElement);
        
        if (!mapElement) {
            console.error('Map element not found!');
            return;
        }

        // Check if Leaflet is loaded
        console.log('Leaflet loaded:', typeof L !== 'undefined');
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded');
            if (retryCount < maxRetries) {
                console.log('Retrying after 200ms...');
                setTimeout(() => {
                    initializeMapWhenVisible(retryCount + 1);
                }, 200);
            }
            return;
        }

        // Check if element is visible
        const isVisible = mapElement.offsetParent !== null && 
                         mapElement.offsetWidth > 0 && 
                         mapElement.offsetHeight > 0;
        
        console.log('Is visible:', isVisible);
        console.log('offsetParent:', mapElement.offsetParent);
        console.log('offsetWidth:', mapElement.offsetWidth);
        console.log('offsetHeight:', mapElement.offsetHeight);
        console.log('Computed display:', window.getComputedStyle(mapElement).display);
        
        if (!isVisible) {
            if (retryCount < maxRetries) {
                console.log('Element not visible, retrying after 200ms...');
                setTimeout(() => {
                    initializeMapWhenVisible(retryCount + 1);
                }, 200);
            } else {
                console.warn('Map element is not visible after ' + maxRetries + ' retries');
                console.log('Forcing initialization anyway...');
                // Try to initialize anyway
                if (!mapInitialized || !clinicLocationMap) {
                    initClinicLocationMap();
                }
            }
            return;
        }

        // Initialize map if not already initialized
        console.log('mapInitialized:', mapInitialized);
        console.log('clinicLocationMap exists:', !!clinicLocationMap);
        
        if (!mapInitialized || !clinicLocationMap) {
            console.log('Calling initClinicLocationMap...');
            initClinicLocationMap();
        } else {
            // Just invalidate size if map already exists
            console.log('Map already exists, invalidating size...');
            clinicLocationMap.invalidateSize();
        }
    }

    window.initClinicLocationMap = function() {
        console.log('===== initClinicLocationMap called =====');
        
        const mapElement = document.getElementById('clinicLocationMap');
        if (!mapElement) {
            console.error('Map element not found!');
            return;
        }

        console.log('Map element found');
        console.log('Element dimensions:', mapElement.offsetWidth, 'x', mapElement.offsetHeight);

        // Check if Leaflet is loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded!');
            return;
        }

        console.log('Leaflet is loaded');

        // Check if map already exists
        if (clinicLocationMap) {
            console.log('Removing existing map...');
            try {
                clinicLocationMap.remove();
            } catch (e) {
                console.warn('Error removing existing map:', e);
            }
            clinicLocationMap = null;
            clinicLocationMarker = null;
        }

        // Load existing coordinates if available
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
            console.log('Creating Leaflet map...');
            // Initialize map
            clinicLocationMap = L.map('clinicLocationMap', {
                zoomControl: true
            }).setView([initialLat, initialLng], initialZoom);

            console.log('Map created successfully');
            console.log('Adding tile layer...');

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(clinicLocationMap);

            console.log('Tile layer added');

            // Add existing marker if coordinates exist
            if (existingLat && existingLng) {
                console.log('Adding existing marker at:', existingLat, existingLng);
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

            console.log('Setting up map click handler...');

            // Handle map click
            clinicLocationMap.on('click', function(e) {
                console.log('Map clicked at:', e.latlng);
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
            console.log('Map initialized successfully! mapInitialized =', mapInitialized);
            
            // Force resize multiple times to ensure map renders correctly
            setTimeout(() => {
                if (clinicLocationMap) {
                    console.log('Invalidating size after 100ms');
                    clinicLocationMap.invalidateSize();
                }
            }, 100);
            
            setTimeout(() => {
                if (clinicLocationMap) {
                    console.log('Invalidating size after 500ms');
                    clinicLocationMap.invalidateSize();
                }
            }, 500);
            
            setTimeout(() => {
                if (clinicLocationMap) {
                    console.log('Invalidating size after 1000ms');
                    clinicLocationMap.invalidateSize();
                }
            }, 1000);
        } catch (error) {
            console.error('Error initializing map:', error);
            console.error('Error stack:', error.stack);
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

    window.autoCheckNationalCode = function() {
        const nationalCode = document.getElementById('nationalCode').value.trim();
        
        // Only check if national code is valid length
        if (nationalCode.length === 10 && /^\d+$/.test(nationalCode)) {
            checkNationalCode(true);
        }
    }

    window.checkNationalCode = function(isAutoCheck = false) {
        const nationalCode = document.getElementById('nationalCode').value.trim();
        const resultDiv = document.getElementById('nationalCodeCheckResult');
        const errorDiv = document.getElementById('nationalCodeError');
        const checkBtn = document.getElementById('checkNationalCodeBtn');
        const submitBtn = document.getElementById('submitDoctorBtn');
        
        if (!nationalCode) {
            if (!isAutoCheck) {
                resultDiv.innerHTML = '<span style="color: #f1416c;"><i class="fas fa-exclamation-circle"></i> لطفاً کد ملی را وارد کنید</span>';
            }
            return;
        }
        
        if (nationalCode.length !== 10 || !/^\d+$/.test(nationalCode)) {
            if (!isAutoCheck) {
                resultDiv.innerHTML = '<span style="color: #f1416c;"><i class="fas fa-exclamation-circle"></i> کد ملی باید 10 رقم باشد</span>';
            }
            return;
        }
        
        // Disable button and show loading
        if (!isAutoCheck) {
            checkBtn.disabled = true;
            checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال بررسی...';
        }
        resultDiv.innerHTML = '';
        errorDiv.textContent = '';
        
        fetch('<?php echo $baseUrl; ?>/doctors/check-national-code?national_code=' + encodeURIComponent(nationalCode), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!isAutoCheck) {
                checkBtn.disabled = false;
                checkBtn.innerHTML = '<i class="fas fa-search"></i> بررسی';
            }
            
            if (data.exists) {
                resultDiv.innerHTML = '<span style="color: #f1416c;"><i class="fas fa-times-circle"></i> این کد ملی قبلاً ثبت شده است</span>';
                errorDiv.textContent = 'این کد ملی قبلاً ثبت شده است';
                errorDiv.style.display = 'block';
                
                // Disable submit button
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                    submitBtn.title = 'کد ملی تکراری است. لطفاً کد ملی دیگری وارد کنید.';
                }
            } else {
                resultDiv.innerHTML = '<span style="color: #50cd89;"><i class="fas fa-check-circle"></i> کد ملی قابل استفاده است</span>';
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
                
                // Enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    submitBtn.title = '';
                }
            }
        })
        .catch(error => {
            if (!isAutoCheck) {
                checkBtn.disabled = false;
                checkBtn.innerHTML = '<i class="fas fa-search"></i> بررسی';
            }
            resultDiv.innerHTML = '<span style="color: #f1416c;"><i class="fas fa-exclamation-circle"></i> خطا در بررسی کد ملی</span>';
            console.error('Error:', error);
        });
    }
    
    console.log('=== All functions defined! ===');
    console.log('switchFormTab available:', typeof window.switchFormTab);
    console.log('initClinicLocationMap available:', typeof window.initClinicLocationMap);
</script>

