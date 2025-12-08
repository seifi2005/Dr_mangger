<?php
/** @var array $center */
$pageTitle = 'جزئیات مرکز درمانی - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">جزئیات مرکز درمانی</h1>
                <p class="breadcrumb-custom">خانه / مراکز درمانی / جزئیات مرکز</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/medical-centers/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="stat-icon bg-primary bg-opacity-25 text-primary" style="width: 150px; height: 150px; margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <i class="fas fa-hospital" style="font-size: 60px;"></i>
                    </div>
                    <h3 class="mt-3"><?php echo htmlspecialchars($center['name']); ?></h3>
                    <span class="badge-<?php echo $center['status'] === 'active' ? 'enabled' : 'disabled'; ?>">
                        <?php echo $center['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                    </span>
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px; color: #a1a5b7;">نوع مرکز:</td>
                            <td><strong><?php echo htmlspecialchars($center['type'] ?? '-'); ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #a1a5b7;">شماره پروانه:</td>
                            <td><strong><?php echo htmlspecialchars($center['license_number']); ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #a1a5b7;">نام مدیر:</td>
                            <td><strong><?php echo htmlspecialchars($center['manager_name'] ?? '-'); ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #a1a5b7;">تلفن:</td>
                            <td><strong><?php echo htmlspecialchars($center['phone'] ?? '-'); ?></strong></td>
                        </tr>
                        <?php if (!empty($center['address'])): ?>
                        <tr>
                            <td style="color: #a1a5b7;">آدرس:</td>
                            <td><strong><?php echo htmlspecialchars($center['address']); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($center['created_at'])): ?>
                        <tr>
                            <td style="color: #a1a5b7;">تاریخ ثبت:</td>
                            <td><strong><?php echo toPersianDate($center['created_at']); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- Doctors List -->
        <div class="card-custom mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 style="margin: 0; color: #181c32; font-weight: 600;">
                    <i class="fas fa-user-md"></i> پزشکان این مرکز درمانی
                </h5>
                <span class="badge-enabled"><?php echo $totalCount ?? 0; ?> پزشک</span>
            </div>

            <?php if (!empty($doctors)): ?>
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>پزشک</th>
                            <th>شماره نظام پزشکی</th>
                            <th>رشته پزشکی</th>
                            <th>موبایل</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <?php if (!empty($doctor['image'])): ?>
                                        <img src="<?php echo $baseUrl; ?>/uploads/<?php echo htmlspecialchars($doctor['image']); ?>" class="user-avatar-table" alt="">
                                    <?php else: ?>
                                        <img src="https://i.pravatar.cc/150?img=<?php echo $doctor['id']; ?>" class="user-avatar-table" alt="">
                                    <?php endif; ?>
                                    <div>
                                        <p class="user-name"><?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></p>
                                        <p class="user-email"><?php echo htmlspecialchars($doctor['email'] ?? $doctor['national_code']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($doctor['medical_license']); ?></td>
                            <td><?php echo htmlspecialchars($doctor['specialty_name'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($doctor['mobile'] ?? '-'); ?></td>
                            <td>
                                <span class="badge-<?php echo $doctor['status'] === 'active' ? 'enabled' : 'disabled'; ?>">
                                    <?php echo $doctor['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/details/<?php echo $doctor['id']; ?>'" title="مشاهده">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>'" title="ویرایش" style="background: #ffc700; color: white;">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        نمایش <?php echo (($currentPage - 1) * 50) + 1; ?> تا <?php echo min($currentPage * 50, $totalCount); ?> از <?php echo $totalCount; ?> نتیجه
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $baseUrl; ?>/medical-centers/details/<?php echo $center['id']; ?>?page=<?php echo $currentPage - 1; ?>">قبلی</a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo $baseUrl; ?>/medical-centers/details/<?php echo $center['id']; ?>?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $baseUrl; ?>/medical-centers/details/<?php echo $center['id']; ?>?page=<?php echo $currentPage + 1; ?>">بعدی</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-user-md" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                    <p>هیچ پزشکی برای این مرکز درمانی ثبت نشده است</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

