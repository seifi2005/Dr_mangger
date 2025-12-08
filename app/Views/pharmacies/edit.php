<?php
/** @var array $pharmacy */
/** @var array $errors */
$pageTitle = 'ویرایش داروخانه - سیستم مدیریت پزشکان';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #locationMap {
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
                <h1 class="page-title">ویرایش داروخانه</h1>
                <p class="breadcrumb-custom">خانه / مدیریت داروخانه / ویرایش داروخانه</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <form id="editPharmacyForm" method="POST" action="<?php echo $baseUrl; ?>/pharmacies/update/<?php echo $pharmacy['id']; ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-modal">نام داروخانه <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="pharmacyName" id="pharmacyName" placeholder="نام داروخانه را وارد کنید" value="<?php echo htmlspecialchars($pharmacy['name'] ?? ''); ?>" required>
                        <div class="error-message-modal" id="pharmacyNameError"><?php echo $errors['name'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">شماره پروانه <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="licenseNumber" id="licenseNumber" placeholder="شماره پروانه را وارد کنید" value="<?php echo htmlspecialchars($pharmacy['license_number'] ?? ''); ?>" required>
                        <div class="error-message-modal" id="licenseNumberError"><?php echo $errors['licenseNumber'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">نام صاحب داروخانه <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="ownerName" id="ownerName" placeholder="نام صاحب را وارد کنید" value="<?php echo htmlspecialchars($pharmacy['owner_name'] ?? ''); ?>" required>
                        <div class="error-message-modal" id="ownerNameError"><?php echo $errors['ownerName'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">کد ملی صاحب <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="ownerNationalCode" id="ownerNationalCode" placeholder="کد ملی را وارد کنید" maxlength="10" value="<?php echo htmlspecialchars($pharmacy['owner_national_code'] ?? ''); ?>" required>
                        <div class="error-message-modal" id="ownerNationalCodeError"><?php echo $errors['ownerNationalCode'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">شماره تلفن</label>
                        <input type="tel" class="form-control-modal" name="phone" id="phone" placeholder="021-12345678" value="<?php echo htmlspecialchars($pharmacy['phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">شماره موبایل</label>
                        <input type="tel" class="form-control-modal" name="mobile" id="mobile" placeholder="09123456789" maxlength="11" value="<?php echo htmlspecialchars($pharmacy['mobile'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">وضعیت</label>
                        <select class="form-control-modal" name="status" id="status">
                            <option value="active" <?php echo ($pharmacy['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>فعال</option>
                            <option value="inactive" <?php echo ($pharmacy['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-modal">آدرس <span class="required">*</span></label>
                        <textarea class="form-control-modal" name="address" id="address" rows="3" placeholder="آدرس کامل داروخانه را وارد کنید" required><?php echo htmlspecialchars($pharmacy['address'] ?? ''); ?></textarea>
                        <div class="error-message-modal" id="addressError"><?php echo $errors['address'] ?? ''; ?></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-modal">انتخاب موقعیت روی نقشه</label>
                        <p style="font-size: 13px; color: #a1a5b7; margin-bottom: 10px;">برای انتخاب موقعیت، روی نقشه کلیک کنید</p>
                        <div id="locationMap"></div>
                        <input type="hidden" name="latitude" id="latitude" value="<?php echo htmlspecialchars($pharmacy['latitude'] ?? ''); ?>">
                        <input type="hidden" name="longitude" id="longitude" value="<?php echo htmlspecialchars($pharmacy['longitude'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-modal">استان <span class="required">*</span></label>
                        <select class="form-control-modal" name="province" id="province" required onchange="updateCities()">
                            <option value="">انتخاب استان</option>
                            <option value="qom" <?php echo ($pharmacy['province'] ?? '') === 'qom' ? 'selected' : ''; ?>>قم</option>
                            <option value="tehran" <?php echo ($pharmacy['province'] ?? '') === 'tehran' ? 'selected' : ''; ?>>تهران</option>
                            <option value="isfahan" <?php echo ($pharmacy['province'] ?? '') === 'isfahan' ? 'selected' : ''; ?>>اصفهان</option>
                            <option value="shiraz" <?php echo ($pharmacy['province'] ?? '') === 'shiraz' ? 'selected' : ''; ?>>شیراز</option>
                        </select>
                        <div class="error-message-modal" id="provinceError"><?php echo $errors['province'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-modal">شهر <span class="required">*</span></label>
                        <select class="form-control-modal" name="city" id="city" required onchange="updateDistricts()">
                            <option value="">ابتدا استان را انتخاب کنید</option>
                        </select>
                        <div class="error-message-modal" id="cityError"><?php echo $errors['city'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-modal">منطقه <span class="required">*</span></label>
                        <select class="form-control-modal" name="district" id="district" required>
                            <option value="">ابتدا شهر را انتخاب کنید</option>
                        </select>
                        <div class="error-message-modal" id="districtError"><?php echo $errors['district'] ?? ''; ?></div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn-modal-cancel" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/list'">انصراف</button>
                    <button type="submit" class="btn-modal-submit">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let locationMap;
    let locationMarker;

    function initLocationMap() {
        const existingLat = document.getElementById('latitude').value;
        const existingLng = document.getElementById('longitude').value;
        const defaultLat = existingLat ? parseFloat(existingLat) : 35.6892;
        const defaultLng = existingLng ? parseFloat(existingLng) : 51.3890;
        const zoom = existingLat && existingLng ? 15 : 13;
        
        locationMap = L.map('locationMap').setView([defaultLat, defaultLng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(locationMap);

        // Add existing marker if coordinates exist
        if (existingLat && existingLng) {
            const customIcon = L.divIcon({
                className: 'custom-pharmacy-icon',
                html: `<div style="background: #7239ea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-pills"></i></div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
            
            locationMarker = L.marker([defaultLat, defaultLng], { icon: customIcon }).addTo(locationMap);
            locationMarker.bindPopup('موقعیت فعلی داروخانه').openPopup();
        }

        locationMap.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            
            if (locationMarker) {
                locationMap.removeLayer(locationMarker);
            }
            
            const customIcon = L.divIcon({
                className: 'custom-pharmacy-icon',
                html: `<div style="background: #7239ea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-pills"></i></div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
            
            locationMarker = L.marker([lat, lng], { icon: customIcon }).addTo(locationMap);
            locationMarker.bindPopup('موقعیت داروخانه انتخاب شده').openPopup();
        });
    }

    const locationData = {
        qom: {
            name: 'قم',
            cities: {
                qom: {
                    name: 'قم',
                    districts: [
                        'للوار امین',
                        'زنبیل آباد',
                        'دور شهر',
                        'صفایی',
                        'شهرک پردیسان',
                        'شهرک امام خمینی',
                        'شهرک ولیعصر',
                        'شهرک شهید بهشتی',
                        'شهرک صفاییه',
                        'شهرک صنعتی',
                        'مرکز شهر',
                        'بلوار معلم',
                        'بلوار امین',
                        'بلوار 15 خرداد',
                        'بلوار قدس'
                    ]
                }
            }
        },
        tehran: {
            name: 'تهران',
            cities: {
                tehran: {
                    name: 'تهران',
                    districts: ['منطقه 1', 'منطقه 2', 'منطقه 3', 'منطقه 4', 'منطقه 5']
                }
            }
        },
        isfahan: {
            name: 'اصفهان',
            cities: {
                isfahan: {
                    name: 'اصفهان',
                    districts: ['منطقه 1', 'منطقه 2', 'منطقه 3']
                }
            }
        },
        shiraz: {
            name: 'شیراز',
            cities: {
                shiraz: {
                    name: 'شیراز',
                    districts: ['منطقه 1', 'منطقه 2', 'منطقه 3']
                }
            }
        }
    };

    function updateCities() {
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const districtSelect = document.getElementById('district');
        
        const selectedProvince = provinceSelect.value;
        
        citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
        districtSelect.innerHTML = '<option value="">ابتدا شهر را انتخاب کنید</option>';
        
        if (selectedProvince && locationData[selectedProvince]) {
            const cities = locationData[selectedProvince].cities;
            for (const cityKey in cities) {
                const city = cities[cityKey];
                const option = document.createElement('option');
                option.value = cityKey;
                option.textContent = city.name;
                citySelect.appendChild(option);
            }
        }
    }

    function updateDistricts() {
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const districtSelect = document.getElementById('district');
        
        const selectedProvince = provinceSelect.value;
        const selectedCity = citySelect.value;
        
        districtSelect.innerHTML = '<option value="">ابتدا شهر را انتخاب کنید</option>';
        
        if (selectedProvince && selectedCity && locationData[selectedProvince] && locationData[selectedProvince].cities[selectedCity]) {
            const districts = locationData[selectedProvince].cities[selectedCity].districts;
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        initLocationMap();
        <?php if (!empty($pharmacy['province'])): ?>
        updateCities();
        <?php if (!empty($pharmacy['city'])): ?>
        setTimeout(() => {
            document.getElementById('city').value = '<?php echo $pharmacy['city']; ?>';
            updateDistricts();
            <?php if (!empty($pharmacy['district'])): ?>
            setTimeout(() => {
                document.getElementById('district').value = '<?php echo $pharmacy['district']; ?>';
            }, 100);
            <?php endif; ?>
        }, 100);
        <?php endif; ?>
        <?php endif; ?>
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

