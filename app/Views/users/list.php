<?php
/** @var array $users */
/** @var string $search */
$pageTitle = 'لیست کاربران - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">لیست کاربران</h1>
                <p class="breadcrumb-custom">خانه / مدیریت کاربران / لیست کاربران</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/users/add'">کاربر جدید</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <div class="toolbar">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn-add" onclick="window.location.href='<?php echo $baseUrl; ?>/users/add'">
                        <i class="fas fa-plus"></i>
                        افزودن کاربر
                    </button>
                </div>
                <div class="search-wrapper">
                    <form method="GET" action="<?php echo $baseUrl; ?>/users/list" style="display: flex; width: 100%;">
                        <input type="text"
                               name="search"
                               class="search-input"
                               placeholder="جستجوی کاربر"
                               value="<?php echo htmlspecialchars($search); ?>">
                        <span class="search-icon"><i class="fas fa-search"></i></span>
                    </form>
                </div>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th>کاربر</th>
                        <th>کد ملی</th>
                        <th>موبایل</th>
                        <th>ایمیل</th>
                        <th>نقش</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <?php if (!empty($user['image'])): ?>
                                        <img src="<?php echo $baseUrl; ?>/uploads/<?php echo htmlspecialchars($user['image']); ?>"
                                             class="user-avatar-table"
                                             alt="">
                                    <?php else: ?>
                                        <img src="https://i.pravatar.cc/150?img=<?php echo $user['id']; ?>"
                                             class="user-avatar-table"
                                             alt="">
                                    <?php endif; ?>
                                    <div>
                                        <p class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                                        <p class="user-email"><?php echo htmlspecialchars($user['email'] ?? $user['national_code']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['national_code']); ?></td>
                            <td><?php echo htmlspecialchars($user['mobile'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                            <td>
                                <span class="badge-enabled"><?php echo htmlspecialchars($user['role'] ?? 'user'); ?></span>
                            </td>
                            <td>
                                <span class="badge-<?php echo $user['status'] === 'active' ? 'enabled' : 'disabled'; ?>">
                                    <?php echo $user['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/users/details/<?php echo $user['id']; ?>'" title="مشاهده">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/users/edit/<?php echo $user['id']; ?>'" title="ویرایش" style="background: #ffc700; color: white;">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-action" onclick="deleteUser(<?php echo $user['id']; ?>)" title="حذف" style="background: #f1416c; color: white;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">هیچ کاربری یافت نشد</td>
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
                            <a class="page-link" href="<?php echo $baseUrl; ?>/users/list?page=<?php echo $currentPage - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">قبلی</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/users/list?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/users/list?page=<?php echo $currentPage + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">بعدی</a>
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
function deleteUser(id) {
    if (confirm('آیا از حذف این کاربر اطمینان دارید؟')) {
        fetch('<?php echo $baseUrl; ?>/users/delete/' + id, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('کاربر با موفقیت حذف شد');
                location.reload();
            } else {
                alert('خطا در حذف کاربر');
            }
        });
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

