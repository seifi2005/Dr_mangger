<?php
/** @var array $pharmacies */
/** @var string $search */
$pageTitle = 'لیست داروخانه‌ها - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">لیست داروخانه‌ها</h1>
                <p class="breadcrumb-custom">خانه / مدیریت داروخانه / لیست داروخانه‌ها</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/add'">داروخانه جدید</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <div class="toolbar">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn-add" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/add'">
                        <i class="fas fa-plus"></i>
                        افزودن داروخانه
                    </button>
                </div>
                <div class="search-wrapper">
                    <form method="GET" action="<?php echo $baseUrl; ?>/pharmacies/list" style="display: flex; width: 100%;">
                        <input type="text"
                               name="search"
                               class="search-input"
                               placeholder="جستجوی داروخانه"
                               value="<?php echo htmlspecialchars($search); ?>">
                        <span class="search-icon"><i class="fas fa-search"></i></span>
                    </form>
                </div>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th>نام داروخانه</th>
                        <th>شماره پروانه</th>
                        <th>صاحب داروخانه</th>
                        <th>آدرس</th>
                        <th>تلفن</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pharmacies)): ?>
                        <?php foreach ($pharmacies as $pharmacy): ?>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="stat-icon bg-warning bg-opacity-25 text-warning" style="width: 50px; height: 50px;">
                                        <i class="fas fa-pills"></i>
                                    </div>
                                    <div>
                                        <p class="user-name"><?php echo htmlspecialchars($pharmacy['name']); ?></p>
                                        <p class="user-email"><?php echo htmlspecialchars($pharmacy['city'] ?? ''); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($pharmacy['license_number']); ?></td>
                            <td><?php echo htmlspecialchars($pharmacy['owner_name']); ?></td>
                            <td><?php echo htmlspecialchars($pharmacy['address']); ?></td>
                            <td><?php echo htmlspecialchars($pharmacy['phone'] ?? '-'); ?></td>
                            <td>
                                <span class="badge-<?php echo $pharmacy['status'] === 'active' ? 'enabled' : 'disabled'; ?>">
                                    <?php echo $pharmacy['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/details/<?php echo $pharmacy['id']; ?>'" title="مشاهده">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/pharmacies/edit/<?php echo $pharmacy['id']; ?>'" title="ویرایش" style="background: #ffc700; color: white;">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-action" onclick="deletePharmacy(<?php echo $pharmacy['id']; ?>)" title="حذف" style="background: #f1416c; color: white;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">هیچ داروخانه‌ای یافت نشد</td>
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
                            <a class="page-link" href="<?php echo $baseUrl; ?>/pharmacies/list?page=<?php echo $currentPage - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">قبلی</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/pharmacies/list?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/pharmacies/list?page=<?php echo $currentPage + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">بعدی</a>
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
function deletePharmacy(id) {
    if (confirm('آیا از حذف این داروخانه اطمینان دارید؟')) {
        fetch('<?php echo $baseUrl; ?>/pharmacies/delete/' + id, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('داروخانه با موفقیت حذف شد');
                location.reload();
            } else {
                alert('خطا در حذف داروخانه');
            }
        });
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

