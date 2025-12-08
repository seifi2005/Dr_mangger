<?php
/** @var array $specialty */
/** @var array $doctors */
$pageTitle = 'جزئیات رشته پزشکی - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">جزئیات رشته پزشکی</h1>
                <p class="breadcrumb-custom">خانه / رشته‌های پزشکی / جزئیات رشته</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom mb-4">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="stat-icon bg-info bg-opacity-25 text-info" style="width: 150px; height: 150px; margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <i class="fas fa-stethoscope" style="font-size: 60px;"></i>
                    </div>
                    <h3 class="mt-3"><?php echo htmlspecialchars($specialty['name_fa']); ?></h3>
                    <span class="badge-<?php echo $specialty['status'] === 'active' ? 'enabled' : 'disabled'; ?>">
                        <?php echo $specialty['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                    </span>
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px; color: #a1a5b7;">نام انگلیسی:</td>
                            <td><strong><?php echo htmlspecialchars($specialty['name_en'] ?? '-'); ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #a1a5b7;">تعداد پزشکان:</td>
                            <td><strong><span class="badge-enabled"><?php echo $specialty['doctors_count'] ?? 0; ?> پزشک</span></strong></td>
                        </tr>
                        <?php if (!empty($specialty['description'])): ?>
                        <tr>
                            <td style="color: #a1a5b7;">توضیحات:</td>
                            <td><strong><?php echo htmlspecialchars($specialty['description']); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($specialty['created_at'])): ?>
                        <tr>
                            <td style="color: #a1a5b7;">تاریخ ثبت:</td>
                            <td><strong><?php echo toPersianDate($specialty['created_at']); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <?php if (!empty($doctors)): ?>
        <div class="card-custom">
            <h4 class="mb-3">پزشکان این رشته</h4>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>پزشک</th>
                        <th>شماره نظام پزشکی</th>
                        <th>موبایل</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor): ?>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <?php if (!empty($doctor['image'])): ?>
                                    <img src="<?php echo $baseUrl; ?>/uploads/<?php echo htmlspecialchars($doctor['image']); ?>"
                                         class="user-avatar-table"
                                         alt="">
                                <?php else: ?>
                                    <img src="https://i.pravatar.cc/150?img=<?php echo $doctor['id']; ?>"
                                         class="user-avatar-table"
                                         alt="">
                                <?php endif; ?>
                                <div>
                                    <p class="user-name"><?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($doctor['medical_license']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['mobile'] ?? '-'); ?></td>
                        <td>
                            <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/details/<?php echo $doctor['id']; ?>'">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

