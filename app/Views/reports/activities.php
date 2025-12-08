<?php
/** @var array $activities */
/** @var int $currentPage */
/** @var int $totalPages */
/** @var string $entityType */
/** @var string $action */
$pageTitle = 'گزارش فعالیت‌ها - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">گزارش فعالیت‌ها</h1>
                <p class="breadcrumb-custom">خانه / گزارش‌ها / گزارش فعالیت‌ها</p>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <!-- فیلترها -->
            <div class="toolbar mb-4">
                <form method="GET" action="<?php echo $baseUrl; ?>/reports/activities" class="d-flex gap-2 flex-wrap align-items-end">
                    <div style="flex: 1; min-width: 200px;">
                        <label class="form-label-modal">نوع موجودیت</label>
                        <select class="form-control-modal" name="entity_type">
                            <option value="">همه</option>
                            <option value="doctor" <?php echo $entityType === 'doctor' ? 'selected' : ''; ?>>پزشک</option>
                            <option value="user" <?php echo $entityType === 'user' ? 'selected' : ''; ?>>کاربر</option>
                            <option value="pharmacy" <?php echo $entityType === 'pharmacy' ? 'selected' : ''; ?>>داروخانه</option>
                            <option value="medical_center" <?php echo $entityType === 'medical_center' ? 'selected' : ''; ?>>مرکز درمانی</option>
                            <option value="specialty" <?php echo $entityType === 'specialty' ? 'selected' : ''; ?>>رشته</option>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label class="form-label-modal">نوع فعالیت</label>
                        <select class="form-control-modal" name="action">
                            <option value="">همه</option>
                            <option value="create" <?php echo $action === 'create' ? 'selected' : ''; ?>>افزودن</option>
                            <option value="update" <?php echo $action === 'update' ? 'selected' : ''; ?>>ویرایش</option>
                            <option value="delete" <?php echo $action === 'delete' ? 'selected' : ''; ?>>حذف</option>
                            <option value="view" <?php echo $action === 'view' ? 'selected' : ''; ?>>مشاهده</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-modal-submit">
                            <i class="fas fa-filter"></i>
                            فیلتر
                        </button>
                        <a href="<?php echo $baseUrl; ?>/reports/activities" class="btn-modal-cancel">
                            <i class="fas fa-redo"></i>
                            بازنشانی
                        </a>
                    </div>
                </form>
            </div>

            <!-- جدول فعالیت‌ها -->
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>کاربر</th>
                        <th>نوع فعالیت</th>
                        <th>موجودیت</th>
                        <th>نام موجودیت</th>
                        <th>توضیحات</th>
                        <th>تاریخ و زمان</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-icon bg-primary bg-opacity-25 text-primary" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0" style="font-weight: 500; font-size: 14px;">
                                            <?php echo htmlspecialchars(($activity['first_name'] ?? '') . ' ' . ($activity['last_name'] ?? 'سیستم')); ?>
                                        </p>
                                        <?php if (!empty($activity['user_id'])): ?>
                                        <small class="text-muted">ID: <?php echo $activity['user_id']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?php 
                                    echo $activity['action'] === 'create' ? 'badge-enabled' : 
                                        ($activity['action'] === 'update' ? 'badge-warning' : 
                                        ($activity['action'] === 'delete' ? 'badge-disabled' : 'badge-info')); 
                                ?>">
                                    <?php 
                                    echo $activity['action'] === 'create' ? 'افزودن' : 
                                        ($activity['action'] === 'update' ? 'ویرایش' : 
                                        ($activity['action'] === 'delete' ? 'حذف' : 'مشاهده')); 
                                    ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-enabled">
                                    <?php 
                                    echo $activity['entity_type'] === 'doctor' ? 'پزشک' : 
                                        ($activity['entity_type'] === 'user' ? 'کاربر' : 
                                        ($activity['entity_type'] === 'pharmacy' ? 'داروخانه' : 
                                        ($activity['entity_type'] === 'medical_center' ? 'مرکز درمانی' : 
                                        ($activity['entity_type'] === 'specialty' ? 'رشته' : $activity['entity_type'])))); 
                                    ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($activity['entity_id']) && !empty($activity['entity_name'])): ?>
                                    <strong><?php echo htmlspecialchars($activity['entity_name']); ?></strong>
                                    <br>
                                    <small class="text-muted">ID: <?php echo $activity['entity_id']; ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($activity['description'])): ?>
                                    <span style="font-size: 13px;"><?php echo htmlspecialchars($activity['description']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div>
                                    <div style="font-weight: 500;"><?php echo toPersianDate($activity['created_at'], 'Y/m/d'); ?></div>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($activity['created_at'])); ?></small>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted"><?php echo htmlspecialchars($activity['ip_address'] ?? '-'); ?></small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">هیچ فعالیتی یافت نشد</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/reports/activities?page=<?php echo $currentPage - 1; ?><?php echo !empty($entityType) ? '&entity_type=' . $entityType : ''; ?><?php echo !empty($action) ? '&action=' . $action : ''; ?>">قبلی</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/reports/activities?page=<?php echo $i; ?><?php echo !empty($entityType) ? '&entity_type=' . $entityType : ''; ?><?php echo !empty($action) ? '&action=' . $action : ''; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/reports/activities?page=<?php echo $currentPage + 1; ?><?php echo !empty($entityType) ? '&entity_type=' . $entityType : ''; ?><?php echo !empty($action) ? '&action=' . $action : ''; ?>">بعدی</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

