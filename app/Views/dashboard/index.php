<?php
/** @var array $stats */
/** @var array $recentDoctors */
$pageTitle = 'داشبورد - سیستم مدیریت پزشکان';
?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">داشبورد</h1>
                <p class="breadcrumb-custom">خانه / داشبورد</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-goal">گزارش جدید</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <!-- کارت‌های آمار -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-primary bg-opacity-25 text-primary me-3">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">کل پزشکان</h6>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <h2 class="mb-0"><?php echo $stats['doctors']; ?></h2>
                    </div>
                    <small class="text-muted">پزشکان ثبت شده</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-success bg-opacity-25 text-success me-3">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">مراکز درمانی</h6>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <h2 class="mb-0"><?php echo $stats['centers']; ?></h2>
                    </div>
                    <small class="text-muted">مراکز ثبت شده</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-warning bg-opacity-25 text-warning me-3">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">داروخانه‌ها</h6>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <h2 class="mb-0"><?php echo $stats['pharmacies']; ?></h2>
                    </div>
                    <small class="text-muted">داروخانه‌های ثبت شده</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-info bg-opacity-25 text-info me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">کاربران سیستم</h6>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <h2 class="mb-0"><?php echo $stats['users']; ?></h2>
                    </div>
                    <small class="text-muted">کاربران ثبت شده</small>
                </div>
            </div>
        </div>

        <!-- کارت‌های دسترسی سریع -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-custom">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="mb-0" style="font-weight: 600; color: #181c32;">دسترسی سریع</h5>
                        <i class="fas fa-bolt" style="color: #7239ea; font-size: 18px;"></i>
                    </div>
                    <div class="quick-access-grid">
                        <div class="quick-access-item" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/add'">
                            <div class="quick-access-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="quick-access-content">
                                <h6 class="quick-access-title">افزودن پزشک</h6>
                                <p class="quick-access-desc">ثبت پزشک جدید در سیستم</p>
                            </div>
                            <div class="quick-access-arrow">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        </div>
                        <div class="quick-access-item" onclick="window.location.href='<?php echo $baseUrl; ?>/users/add'">
                            <div class="quick-access-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="quick-access-content">
                                <h6 class="quick-access-title">افزودن کاربر</h6>
                                <p class="quick-access-desc">ایجاد حساب کاربری جدید</p>
                            </div>
                            <div class="quick-access-arrow">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        </div>
                        <div class="quick-access-item" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/add'">
                            <div class="quick-access-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <i class="fas fa-pills"></i>
                            </div>
                            <div class="quick-access-content">
                                <h6 class="quick-access-title">افزودن داروخانه</h6>
                                <p class="quick-access-desc">ثبت داروخانه جدید</p>
                            </div>
                            <div class="quick-access-arrow">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        </div>
                        <div class="quick-access-item" onclick="window.location.href='<?php echo $baseUrl; ?>/medical-centers/add'">
                            <div class="quick-access-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <i class="fas fa-hospital"></i>
                            </div>
                            <div class="quick-access-content">
                                <h6 class="quick-access-title">افزودن مرکز درمانی</h6>
                                <p class="quick-access-desc">ثبت مرکز درمانی جدید</p>
                            </div>
                            <div class="quick-access-arrow">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0" style="font-weight: 600; color: #181c32;">آخرین فعالیت‌ها</h5>
                        <a href="<?php echo $baseUrl; ?>/reports/activities" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                            <div class="d-flex align-items-center gap-3 p-2" style="border-bottom: 1px solid #eff2f5;">
                                <div class="stat-icon <?php 
                                    echo $activity['action'] === 'create' ? 'bg-success bg-opacity-25 text-success' : 
                                        ($activity['action'] === 'update' ? 'bg-warning bg-opacity-25 text-warning' : 
                                        ($activity['action'] === 'delete' ? 'bg-danger bg-opacity-25 text-danger' : 'bg-info bg-opacity-25 text-info')); 
                                ?>" style="width: 40px; height: 40px;">
                                    <i class="fas <?php 
                                        echo $activity['entity_type'] === 'doctor' ? 'fa-user-md' : 
                                            ($activity['entity_type'] === 'user' ? 'fa-user' : 
                                            ($activity['entity_type'] === 'pharmacy' ? 'fa-pills' : 
                                            ($activity['entity_type'] === 'medical_center' ? 'fa-hospital' : 
                                            ($activity['entity_type'] === 'specialty' ? 'fa-stethoscope' : 'fa-circle')))); 
                                    ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0" style="font-weight: 500; font-size: 14px;">
                                        <?php 
                                        $actionText = $activity['action'] === 'create' ? 'افزودن' : 
                                            ($activity['action'] === 'update' ? 'ویرایش' : 
                                            ($activity['action'] === 'delete' ? 'حذف' : 'مشاهده'));
                                        $entityText = $activity['entity_type'] === 'doctor' ? 'پزشک' : 
                                            ($activity['entity_type'] === 'user' ? 'کاربر' : 
                                            ($activity['entity_type'] === 'pharmacy' ? 'داروخانه' : 
                                            ($activity['entity_type'] === 'medical_center' ? 'مرکز درمانی' : 
                                            ($activity['entity_type'] === 'specialty' ? 'رشته' : ''))));
                                        echo htmlspecialchars($actionText . ' ' . $entityText . ': ' . ($activity['entity_name'] ?? ''));
                                        ?>
                                    </p>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars(($activity['first_name'] ?? '') . ' ' . ($activity['last_name'] ?? 'سیستم')); ?>
                                        - <?php echo toPersianDate($activity['created_at'], 'Y/m/d H:i'); ?>
                                    </small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">هیچ فعالیتی وجود ندارد</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

