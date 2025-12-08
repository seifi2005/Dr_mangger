<?php
/** @var array $pharmacies */
/** @var string $province */
/** @var string $city */
/** @var string $district */
/** @var array $provinces */
/** @var array $cities */
/** @var array $districts */
$pageTitle = 'جستجوی داروخانه روی نقشه - سیستم مدیریت پزشکان';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .map-container {
        position: relative;
        height: calc(100vh - 200px);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
    }

    #pharmacyMap {
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .filter-panel {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
    }

    .filter-title {
        font-size: 18px;
        font-weight: 600;
        color: #181c32;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-title i {
        color: #7239ea;
    }

    .filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .pharmacy-marker-popup {
        text-align: right;
        font-family: 'Segoe UI', Tahoma, Arial, 'Vazir', sans-serif;
    }

    .pharmacy-marker-popup h4 {
        font-size: 16px;
        font-weight: 600;
        color: #181c32;
        margin-bottom: 8px;
    }

    .pharmacy-marker-popup p {
        font-size: 13px;
        color: #5e6278;
        margin: 4px 0;
    }

    .pharmacy-marker-popup .badge {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 10px;
        background: #e8fff3;
        color: #50cd89;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .filter-row {
            grid-template-columns: 1fr;
        }

        .map-container {
            height: 500px;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">جستجوی داروخانه روی نقشه</h1>
                <p class="breadcrumb-custom">خانه / مدیریت داروخانه / جستجو روی نقشه</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <!-- Filter Panel -->
        <div class="filter-panel">
            <h3 class="filter-title">
                <i class="fas fa-filter"></i>
                فیلتر جستجو
            </h3>
            <form method="GET" action="<?php echo $baseUrl; ?>/pharmacies/map-search">
                <div class="filter-row">
                    <div>
                        <label class="form-label-modal">استان</label>
                        <select name="province" 
                                class="form-control-modal" 
                                id="provinceFilter" 
                                onchange="updateCitiesAndDistricts()">
                            <option value="">همه استان‌ها</option>
                            <?php foreach ($provinces as $p): ?>
                                <option value="<?php echo htmlspecialchars($p); ?>" <?php echo $province === $p ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-modal">شهر</label>
                        <select name="city" 
                                class="form-control-modal" 
                                id="cityFilter" 
                                onchange="updateDistricts()">
                            <option value="">همه شهرها</option>
                            <?php foreach ($cities as $c): ?>
                                <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $city === $c ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-modal">منطقه</label>
                        <select name="district" 
                                class="form-control-modal" 
                                id="districtFilter">
                            <option value="">همه مناطق</option>
                            <?php foreach ($districts as $d): ?>
                                <option value="<?php echo htmlspecialchars($d); ?>" <?php echo $district === $d ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="button" class="btn-modal-cancel" onclick="resetFilters()">
                        <i class="fas fa-redo"></i>
                        بازنشانی
                    </button>
                    <button type="submit" class="btn-modal-submit">
                        <i class="fas fa-search"></i>
                        اعمال فیلتر
                    </button>
                </div>
            </form>
        </div>

        <!-- Map Container -->
        <div class="card-custom" style="padding: 0;">
            <div class="map-container">
                <div id="pharmacyMap"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let pharmacyMap;
    let markers = [];
    const pharmacies = <?php echo json_encode($pharmacies, JSON_UNESCAPED_UNICODE); ?>;

    // Initialize Map
    function initPharmacyMap() {
        // Default to Tehran if no pharmacies
        const defaultLat = 35.6892;
        const defaultLng = 51.3890;
        const defaultZoom = pharmacies.length > 0 ? 12 : 6;
        
        pharmacyMap = L.map('pharmacyMap').setView([defaultLat, defaultLng], defaultZoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(pharmacyMap);

        displayPharmacies(pharmacies);
    }

    // Group pharmacies by location (cluster nearby pharmacies)
    function groupPharmaciesByLocation(pharmacies, radius = 0.01) {
        const groups = [];
        const processed = new Set();

        pharmacies.forEach((pharmacy, index) => {
            if (processed.has(index) || !pharmacy.latitude || !pharmacy.longitude) {
                return;
            }

            const lat = parseFloat(pharmacy.latitude);
            const lng = parseFloat(pharmacy.longitude);
            const group = {
                lat: lat,
                lng: lng,
                pharmacies: [pharmacy],
                indices: [index]
            };

            // Find nearby pharmacies
            pharmacies.forEach((other, otherIndex) => {
                if (otherIndex === index || processed.has(otherIndex) || !other.latitude || !other.longitude) {
                    return;
                }

                const otherLat = parseFloat(other.latitude);
                const otherLng = parseFloat(other.longitude);
                const distance = Math.sqrt(
                    Math.pow(lat - otherLat, 2) + Math.pow(lng - otherLng, 2)
                );

                if (distance <= radius) {
                    group.pharmacies.push(other);
                    group.indices.push(otherIndex);
                    processed.add(otherIndex);
                }
            });

            processed.add(index);
            groups.push(group);
        });

        return groups;
    }

    // Get color based on count - All blue
    function getColorForCount(count) {
        return '#009ef7'; // Blue for all clusters
    }

    // Display Pharmacies on Map
    function displayPharmacies(pharmacies) {
        // Clear existing markers
        markers.forEach(marker => pharmacyMap.removeLayer(marker));
        markers = [];

        if (pharmacies.length === 0) {
            return;
        }

        // Group pharmacies by location
        const groups = groupPharmaciesByLocation(pharmacies);

        // Add markers for each group
        groups.forEach(group => {
            const count = group.pharmacies.length;
            const color = getColorForCount(count);
            
            // Calculate average position for group
            let avgLat = 0;
            let avgLng = 0;
            group.pharmacies.forEach(p => {
                avgLat += parseFloat(p.latitude);
                avgLng += parseFloat(p.longitude);
            });
            avgLat /= group.pharmacies.length;
            avgLng /= group.pharmacies.length;

            // Create marker with count
            const size = count > 50 ? 50 : count > 20 ? 40 : count > 5 ? 35 : 30;
            const fontSize = count > 50 ? 18 : count > 20 ? 16 : count > 5 ? 14 : 12;
            
            const customIcon = L.divIcon({
                className: 'custom-pharmacy-cluster-icon',
                html: `<div style="background: ${color}; color: white; border-radius: 50%; width: ${size}px; height: ${size}px; display: flex; align-items: center; justify-content: center; font-size: ${fontSize}px; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">${count}</div>`,
                iconSize: [size, size],
                iconAnchor: [size / 2, size / 2]
            });

            const marker = L.marker([avgLat, avgLng], { icon: customIcon }).addTo(pharmacyMap);
            
            // Create popup with all pharmacies in group
            let popupContent = `<div class="pharmacy-marker-popup"><h4>${count} داروخانه</h4>`;
            group.pharmacies.forEach((pharmacy, idx) => {
                if (idx < 5) { // Show first 5 in popup
                    popupContent += `
                        <div style="border-bottom: 1px solid #eee; padding: 8px 0;">
                            <strong>${escapeHtml(pharmacy.name || '')}</strong><br>
                            ${pharmacy.address ? `<small><i class="fas fa-map-marker-alt"></i> ${escapeHtml(pharmacy.address)}</small><br>` : ''}
                            ${pharmacy.phone ? `<small><i class="fas fa-phone"></i> ${escapeHtml(pharmacy.phone)}</small>` : ''}
                            ${pharmacy.id ? `<br><a href="<?php echo $baseUrl; ?>/pharmacies/details/${pharmacy.id}" style="margin-top: 4px; display: inline-block; color: #7239ea; text-decoration: none; font-size: 11px;">مشاهده جزئیات</a>` : ''}
                        </div>
                    `;
                }
            });
            if (group.pharmacies.length > 5) {
                popupContent += `<p style="margin-top: 8px; color: #a1a5b7; font-size: 12px;">و ${group.pharmacies.length - 5} داروخانه دیگر...</p>`;
            }
            popupContent += `</div>`;
            
            marker.bindPopup(popupContent);
            markers.push(marker);
        });

        // Fit map to show all markers
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            pharmacyMap.fitBounds(group.getBounds().pad(0.1));
        }
    }

    // Reset Filters
    function resetFilters() {
        window.location.href = '<?php echo $baseUrl; ?>/pharmacies/map-search';
    }

    // Update cities and districts when province changes
    function updateCitiesAndDistricts() {
        const province = document.getElementById('provinceFilter').value;
        const url = new URL(window.location.href);
        url.searchParams.set('province', province);
        url.searchParams.delete('city');
        url.searchParams.delete('district');
        window.location.href = url.toString();
    }

    // Update districts when city changes
    function updateDistricts() {
        const province = document.getElementById('provinceFilter').value;
        const city = document.getElementById('cityFilter').value;
        const url = new URL(window.location.href);
        url.searchParams.set('province', province);
        url.searchParams.set('city', city);
        url.searchParams.delete('district');
        window.location.href = url.toString();
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initPharmacyMap();
    });
</script>

