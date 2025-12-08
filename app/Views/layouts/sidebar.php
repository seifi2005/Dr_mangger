<?php
// baseUrl should be passed from controller, if not, detect it
if (!isset($baseUrl)) {
    $config = require __DIR__ . '/../../../config/config.php';
    $baseUrl = $config['app']['url'];
    
    // Auto-detect base URL if not set correctly
    if (empty($baseUrl) || $baseUrl === 'http://localhost/medic/public') {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $baseUrl = $protocol . '://' . $host . rtrim($scriptName, '/');
    }
    $baseUrl = rtrim($baseUrl, '/');
}

$currentController = $currentController ?? 'dashboard';
?>
<!-- Secondary Sidebar (Icons) -->
<div class="sidebar-secondary">
    <div class="logo-small">🏥</div>

    <div class="sidebar-icon-item <?php echo $currentController === 'dashboard' ? 'active' : ''; ?>" data-menu="home" onclick="window.location.href='<?php echo $baseUrl; ?>/dashboard'">
        <i class="fas fa-home"></i>
        <span class="sidebar-icon-label">داشبورد</span>
    </div>
    <div class="sidebar-icon-item <?php echo $currentController === 'doctors' ? 'active' : ''; ?>" data-menu="doctors" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/list'">
        <i class="fas fa-user-md"></i>
        <span class="sidebar-icon-label">پزشکان</span>
    </div>
    <div class="sidebar-icon-item <?php echo $currentController === 'users' ? 'active' : ''; ?>" data-menu="users" onclick="window.location.href='<?php echo $baseUrl; ?>/users/list'">
        <i class="fas fa-users"></i>
        <span class="sidebar-icon-label">کاربران</span>
    </div>
    <div class="sidebar-icon-item <?php echo $currentController === 'pharmacies' ? 'active' : ''; ?>" data-menu="pharmacies" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/list'">
        <i class="fas fa-pills"></i>
        <span class="sidebar-icon-label">داروخانه</span>
    </div>

    <div class="sidebar-divider"></div>

    <div class="sidebar-icon-item <?php echo $currentController === 'medical-centers' ? 'active' : ''; ?>" data-menu="centers" onclick="window.location.href='<?php echo $baseUrl; ?>/medical-centers/list'">
        <i class="fas fa-hospital"></i>
        <span class="sidebar-icon-label">مراکز</span>
    </div>
    <div class="sidebar-icon-item <?php echo $currentController === 'specialties' ? 'active' : ''; ?>" data-menu="specialties" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/list'">
        <i class="fas fa-stethoscope"></i>
        <span class="sidebar-icon-label">رشته‌ها</span>
    </div>
    <div class="sidebar-icon-item" data-menu="reports">
        <i class="fas fa-chart-bar"></i>
        <span class="sidebar-icon-label">گزارش‌ها</span>
    </div>

    <img src="https://i.pravatar.cc/150?img=68"
         class="sidebar-user-icon"
         alt="User"
         onclick="openUserProfileModal()"
         style="cursor: pointer;">
</div>

<!-- Primary Sidebar (Full Menu) -->
<div class="sidebar-primary" id="sidebarPrimary">
    <div class="sidebar-header">
        <p class="sidebar-title">منوی اصلی</p>
    </div>

    <div class="sidebar-menu">
        <!-- محتوای تب خانه -->
        <div class="menu-content <?php echo $currentController === 'dashboard' ? 'active' : ''; ?>" id="homeContent">
            <button class="menu-item <?php echo $currentController === 'dashboard' ? 'active' : ''; ?>" data-submenu="homeSubmenu">
                <div class="d-flex align-items-center gap-3">
                    <span class="menu-icon"><i class="fas fa-home"></i></span>
                    <span>خانه</span>
                </div>
                <i class="fas fa-chevron-up menu-arrow"></i>
            </button>

            <div class="submenu <?php echo ($currentController === 'dashboard' || $currentController === 'settings') ? 'expanded' : ''; ?>" id="homeSubmenu">
                <div class="submenu-item <?php echo $currentController === 'dashboard' ? 'active' : ''; ?>" onclick="window.location.href='<?php echo $baseUrl; ?>/dashboard'">داشبورد</div>
                <div class="submenu-item <?php echo $currentController === 'settings' ? 'active' : ''; ?>" onclick="window.location.href='<?php echo $baseUrl; ?>/settings/backup'">بک‌آپ دیتابیس</div>
            </div>
        </div>

        <!-- محتوای تب مدیریت پزشکان -->
        <div class="menu-content <?php echo $currentController === 'doctors' ? 'active' : ''; ?>" id="doctorsContent">
            <button class="menu-item <?php echo $currentController === 'doctors' ? 'active' : ''; ?>" data-submenu="doctorsSubmenu">
                <div class="d-flex align-items-center gap-3">
                    <span class="menu-icon"><i class="fas fa-user-md"></i></span>
                    <span>مدیریت پزشکان</span>
                </div>
                <i class="fas fa-chevron-up menu-arrow"></i>
            </button>

            <div class="submenu <?php echo $currentController === 'doctors' ? 'expanded' : ''; ?>" id="doctorsSubmenu">
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/list'">لیست پزشکان</div>
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/add'">افزودن پزشک</div>
            </div>
        </div>

        <!-- محتوای تب مدیریت کاربران -->
        <div class="menu-content <?php echo $currentController === 'users' ? 'active' : ''; ?>" id="usersContent">
            <button class="menu-item <?php echo $currentController === 'users' ? 'active' : ''; ?>" data-submenu="usersSubmenu">
                <div class="d-flex align-items-center gap-3">
                    <span class="menu-icon"><i class="fas fa-users"></i></span>
                    <span>مدیریت کاربران</span>
                </div>
                <i class="fas fa-chevron-up menu-arrow"></i>
            </button>

            <div class="submenu <?php echo $currentController === 'users' ? 'expanded' : ''; ?>" id="usersSubmenu">
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/users/list'">لیست کاربران</div>
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/users/add'">افزودن کاربر</div>
            </div>
        </div>

        <!-- محتوای تب مدیریت داروخانه -->
        <div class="menu-content <?php echo $currentController === 'pharmacies' ? 'active' : ''; ?>" id="pharmaciesContent">
            <button class="menu-item <?php echo $currentController === 'pharmacies' ? 'active' : ''; ?>" data-submenu="pharmaciesSubmenu">
                <div class="d-flex align-items-center gap-3">
                    <span class="menu-icon"><i class="fas fa-pills"></i></span>
                    <span>مدیریت داروخانه</span>
                </div>
                <i class="fas fa-chevron-up menu-arrow"></i>
            </button>

            <div class="submenu <?php echo $currentController === 'pharmacies' ? 'expanded' : ''; ?>" id="pharmaciesSubmenu">
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/list'">لیست داروخانه‌ها</div>
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/add'">افزودن داروخانه</div>
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/map-search'">جستجو روی نقشه</div>
            </div>
        </div>

        <!-- محتوای تب مدیریت مراکز درمانی -->
        <div class="menu-content <?php echo $currentController === 'medical-centers' ? 'active' : ''; ?>" id="centersContent">
            <button class="menu-item <?php echo $currentController === 'medical-centers' ? 'active' : ''; ?>" data-submenu="centersSubmenu">
                <div class="d-flex align-items-center gap-3">
                    <span class="menu-icon"><i class="fas fa-hospital"></i></span>
                    <span>مراکز درمانی</span>
                </div>
                <i class="fas fa-chevron-up menu-arrow"></i>
            </button>

            <div class="submenu <?php echo $currentController === 'medical-centers' ? 'expanded' : ''; ?>" id="centersSubmenu">
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/medical-centers/list'">لیست مراکز</div>
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/medical-centers/add'">افزودن مرکز</div>
            </div>
        </div>

        <!-- محتوای تب رشته‌های پزشکی -->
        <div class="menu-content <?php echo $currentController === 'specialties' ? 'active' : ''; ?>" id="specialtiesContent">
            <button class="menu-item <?php echo $currentController === 'specialties' ? 'active' : ''; ?>" data-submenu="specialtiesSubmenu">
                <div class="d-flex align-items-center gap-3">
                    <span class="menu-icon"><i class="fas fa-stethoscope"></i></span>
                    <span>رشته‌های پزشکی</span>
                </div>
                <i class="fas fa-chevron-up menu-arrow"></i>
            </button>

            <div class="submenu <?php echo $currentController === 'specialties' ? 'expanded' : ''; ?>" id="specialtiesSubmenu">
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/list'">لیست رشته‌ها</div>
                <div class="submenu-item" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/add'">افزودن رشته</div>
            </div>
        </div>

        <!-- محتوای تب گزارش‌ها -->
        <div class="menu-content" id="reportsContent">
            <button class="menu-item" data-submenu="reportsSubmenu">
                <div class="d-flex align-items-center gap-3">
                    <span class="menu-icon"><i class="fas fa-chart-bar"></i></span>
                    <span>گزارش‌ها</span>
                </div>
                <i class="fas fa-chevron-up menu-arrow"></i>
            </button>

            <div class="submenu expanded" id="reportsSubmenu">
                <div class="submenu-item <?php echo $currentController === 'reports' ? 'active' : ''; ?>" onclick="window.location.href='<?php echo $baseUrl; ?>/reports/activities'">گزارش فعالیت‌ها</div>
                <div class="submenu-item">گزارش پزشکان</div>
                <div class="submenu-item">گزارش کاربران</div>
                <div class="submenu-item">گزارش مراکز</div>
                <div class="submenu-item">گزارش‌های مالی</div>
            </div>
        </div>

    </div>
</div>

<!-- دکمه toggle -->
<div class="toggle-sidebar-btn" onclick="toggleSidebar()">
    <i class="fas fa-chevron-left" id="toggleIcon"></i>
</div>

