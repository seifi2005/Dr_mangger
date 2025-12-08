<?php
/** @var array $backups */
/** @var int $oldLogsCount */
$pageTitle = 'بک‌آپ دیتابیس - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">بک‌آپ دیتابیس</h1>
                <p class="breadcrumb-custom">خانه / تنظیمات / بک‌آپ دیتابیس</p>
            </div>
        </div>
    </div>

    <div class="content-area">
        <!-- پیام‌های موفقیت/خطا -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- کارت ایجاد بک‌آپ -->
            <div class="col-md-12">
                <div class="card-custom">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="mb-0" style="font-weight: 600; color: #181c32;">ایجاد بک‌آپ جدید</h5>
                        <i class="fas fa-database" style="color: #7239ea; font-size: 24px;"></i>
                    </div>
                    <p class="text-muted mb-4">با کلیک روی دکمه زیر، یک بک‌آپ کامل از دیتابیس ایجاد می‌شود. سیستم به صورت خودکار فقط 2 بک‌آپ آخر را نگه می‌دارد.</p>
                    <form method="POST" action="<?php echo $baseUrl; ?>/settings/backup/create">
                        <button type="submit" class="btn-modal-submit">
                            <i class="fas fa-download"></i>
                            ایجاد بک‌آپ جدید
                        </button>
                    </form>
                </div>
            </div>

            <!-- کارت پاک‌سازی لاگ‌ها -->
            <div class="col-md-12">
                <div class="card-custom">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="mb-0" style="font-weight: 600; color: #181c32;">پاک‌سازی لاگ‌های قدیمی</h5>
                        <i class="fas fa-trash-alt" style="color: #f1416c; font-size: 24px;"></i>
                    </div>
                    <p class="text-muted mb-4">
                        لاگ‌های قدیمی‌تر از 20 روز به صورت خودکار حذف می‌شوند. 
                        <?php if ($oldLogsCount > 0): ?>
                            <strong><?php echo $oldLogsCount; ?> لاگ قدیمی</strong> برای حذف وجود دارد.
                        <?php else: ?>
                            در حال حاضر لاگ قدیمی برای حذف وجود ندارد.
                        <?php endif; ?>
                    </p>
                    <button type="button" class="btn-modal-cancel" onclick="cleanLogs(event)" <?php echo $oldLogsCount == 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-broom"></i>
                        پاک‌سازی لاگ‌های قدیمی
                    </button>
                </div>
            </div>

            <!-- لیست بک‌آپ‌ها -->
            <div class="col-md-12">
                <div class="card-custom">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="mb-0" style="font-weight: 600; color: #181c32;">لیست بک‌آپ‌ها</h5>
                        <span class="badge-enabled"><?php echo count($backups); ?> بک‌آپ</span>
                    </div>

                    <?php if (!empty($backups)): ?>
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>نام فایل</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>حجم</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backups as $backup): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-file-archive text-primary"></i>
                                            <span style="font-weight: 500;"><?php echo htmlspecialchars($backup['filename']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div style="font-weight: 500;"><?php echo toPersianDate($backup['created_at'], 'Y/m/d'); ?></div>
                                            <small class="text-muted"><?php echo date('H:i', strtotime($backup['created_at'])); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-enabled"><?php echo htmlspecialchars($backup['size_formatted']); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo $baseUrl; ?>/settings/backup/download/<?php echo urlencode($backup['filename']); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                                دانلود
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBackup('<?php echo htmlspecialchars($backup['filename']); ?>')">
                                                <i class="fas fa-trash"></i>
                                                حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                            <p>هیچ بک‌آپی وجود ندارد</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteBackup(filename) {
    if (!confirm('آیا از حذف این بک‌آپ اطمینان دارید؟')) {
        return;
    }

    fetch('<?php echo $baseUrl; ?>/settings/backup/delete/' + encodeURIComponent(filename), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('خطا: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطا در حذف بک‌آپ');
    });
}

function cleanLogs(event) {
    if (!confirm('آیا از پاک‌سازی لاگ‌های قدیمی اطمینان دارید؟ این عمل قابل بازگشت نیست.')) {
        return;
    }

    const button = event.target.closest('button') || event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال پردازش...';

    fetch('<?php echo $baseUrl; ?>/settings/backup/clean-logs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('خطا: ' + (data.message || 'خطای نامشخص'));
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطا در پاک‌سازی لاگ‌ها: ' + error.message);
        button.disabled = false;
        button.innerHTML = originalText;
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

