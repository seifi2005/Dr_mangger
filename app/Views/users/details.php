<?php
/** @var array $user */
$pageTitle = 'جزئیات کاربر - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">جزئیات کاربر</h1>
                <p class="breadcrumb-custom">خانه / مدیریت کاربران / جزئیات کاربر</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/users/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <?php if (!empty($user['image'])): ?>
                        <img src="<?php echo $baseUrl; ?>/uploads/<?php echo htmlspecialchars($user['image']); ?>" 
                             alt="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" 
                             style="width: 200px; height: 200px; border-radius: 12px; object-fit: cover; border: 4px solid #e4e6ef;">
                    <?php else: ?>
                        <img src="https://i.pravatar.cc/200?img=<?php echo $user['id']; ?>" 
                             alt="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" 
                             style="width: 200px; height: 200px; border-radius: 12px; object-fit: cover; border: 4px solid #e4e6ef;">
                    <?php endif; ?>
                    <h3 class="mt-3"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span class="badge-<?php echo $user['status'] === 'active' ? 'enabled' : 'disabled'; ?>">
                        <?php echo $user['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                    </span>
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px; color: #a1a5b7;">کد ملی:</td>
                            <td><strong><?php echo htmlspecialchars($user['national_code']); ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #a1a5b7;">موبایل:</td>
                            <td><strong><?php echo htmlspecialchars($user['mobile'] ?? '-'); ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #a1a5b7;">ایمیل:</td>
                            <td><strong><?php echo htmlspecialchars($user['email'] ?? '-'); ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #a1a5b7;">نقش:</td>
                            <td><span class="badge-enabled"><?php echo htmlspecialchars($user['role'] ?? 'user'); ?></span></td>
                        </tr>
                        <?php if (!empty($user['address'])): ?>
                        <tr>
                            <td style="color: #a1a5b7;">آدرس:</td>
                            <td><strong><?php echo htmlspecialchars($user['address']); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($user['created_at'])): ?>
                        <tr>
                            <td style="color: #a1a5b7;">تاریخ ثبت:</td>
                            <td><strong><?php echo toPersianDate($user['created_at']); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

