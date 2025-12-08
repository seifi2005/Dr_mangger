<?php
/** @var array $specialties */
$pageTitle = 'لیست رشته‌های پزشکی - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">لیست رشته‌های پزشکی</h1>
                <p class="breadcrumb-custom">خانه / رشته‌های پزشکی / لیست رشته‌ها</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/add'">رشته جدید</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <div class="toolbar">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn-add" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/add'">
                        <i class="fas fa-plus"></i>
                        افزودن رشته
                    </button>
                </div>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th>نام فارسی</th>
                        <th>نام انگلیسی</th>
                        <th>تعداد پزشکان</th>
                        <th>توضیحات</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($specialties)): ?>
                        <?php foreach ($specialties as $specialty): ?>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="stat-icon bg-info bg-opacity-25 text-info" style="width: 50px; height: 50px;">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    <div>
                                        <p class="user-name"><?php echo htmlspecialchars($specialty['name_fa']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($specialty['name_en'] ?? '-'); ?></td>
                            <td>
                                <span class="badge-enabled"><?php echo $specialty['doctors_count'] ?? 0; ?> پزشک</span>
                            </td>
                            <td><?php echo htmlspecialchars(mb_substr($specialty['description'] ?? '', 0, 50)) . (mb_strlen($specialty['description'] ?? '') > 50 ? '...' : ''); ?></td>
                            <td>
                                <span class="badge-<?php echo $specialty['status'] === 'active' ? 'enabled' : 'disabled'; ?>">
                                    <?php echo $specialty['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/details/<?php echo $specialty['id']; ?>'" title="مشاهده">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/edit/<?php echo $specialty['id']; ?>'" title="ویرایش" style="background: #ffc700; color: white;">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-action" onclick="deleteSpecialty(<?php echo $specialty['id']; ?>)" title="حذف" style="background: #f1416c; color: white;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">هیچ رشته پزشکی یافت نشد</td>
                        </tr>
                    <?php endif; ?>
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
                            <a class="page-link" href="<?php echo $baseUrl; ?>/specialties/list?page=<?php echo $currentPage - 1; ?>">قبلی</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/specialties/list?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/specialties/list?page=<?php echo $currentPage + 1; ?>">بعدی</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteSpecialty(id) {
    if (confirm('آیا از حذف این رشته پزشکی اطمینان دارید؟')) {
        fetch('<?php echo $baseUrl; ?>/specialties/delete/' + id, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('رشته پزشکی با موفقیت حذف شد');
                location.reload();
            } else {
                alert('خطا در حذف رشته پزشکی');
            }
        });
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

