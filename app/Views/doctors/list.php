<?php
/** @var array $doctors */
/** @var array $groupedDoctors */
/** @var string $search */
/** @var array $filters */
/** @var array $specialties */
/** @var array $centers */
/** @var bool $hasFilters */

// Include permission helper
require_once __DIR__ . '/../layouts/permission_check.php';

$pageTitle = 'لیست پزشکان - سیستم مدیریت پزشکان';
?>
<style>
.filter-panel {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}
.filter-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}
.clinic-group {
    margin-bottom: 30px;
}
.clinic-header {
    background: #7239ea;
    color: white;
    padding: 12px 20px;
    border-radius: 8px 8px 0 0;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.clinic-header .count {
    background: rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 14px;
}
.clinic-doctors {
    border: 1px solid #e4e6ef;
    border-top: none;
    border-radius: 0 0 8px 8px;
    overflow: hidden;
}
</style>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">لیست پزشکان</h1>
                <p class="breadcrumb-custom">خانه / مدیریت پزشکان / لیست پزشکان</p>
            </div>
            <div class="d-flex gap-2">
                <?php if (canView('doctors.create')): ?>
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/add'">پزشک جدید</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="content-area">
        <!-- Filter Panel -->
        <div class="card-custom">
            <div class="filter-panel">
                <h5 style="margin-bottom: 20px; color: #181c32;">
                    <i class="fas fa-filter"></i> فیلترهای پیشرفته
                </h5>
                <form method="GET" action="<?php echo $baseUrl; ?>/doctors/list" id="filterForm">
                    <div class="filter-row">
                        <div>
                            <label class="form-label-modal">نام</label>
                            <input type="text" name="first_name" class="form-control-modal" value="<?php echo htmlspecialchars($filters['first_name'] ?? ''); ?>" placeholder="نام">
                        </div>
                        <div>
                            <label class="form-label-modal">نام خانوادگی</label>
                            <input type="text" name="last_name" class="form-control-modal" value="<?php echo htmlspecialchars($filters['last_name'] ?? ''); ?>" placeholder="نام خانوادگی">
                        </div>
                        <div>
                            <label class="form-label-modal">کد ملی</label>
                            <input type="text" name="national_code" class="form-control-modal" value="<?php echo htmlspecialchars($filters['national_code'] ?? ''); ?>" placeholder="کد ملی" maxlength="10">
                        </div>
                        <div>
                            <label class="form-label-modal">شماره شناسنامه</label>
                            <input type="text" name="id_number" class="form-control-modal" value="<?php echo htmlspecialchars($filters['id_number'] ?? ''); ?>" placeholder="شماره شناسنامه">
                        </div>
                        <div>
                            <label class="form-label-modal">شماره نظام پزشکی</label>
                            <input type="text" name="medical_license" class="form-control-modal" value="<?php echo htmlspecialchars($filters['medical_license'] ?? ''); ?>" placeholder="شماره نظام پزشکی">
                        </div>
                        <div>
                            <label class="form-label-modal">موبایل</label>
                            <input type="text" name="mobile" class="form-control-modal" value="<?php echo htmlspecialchars($filters['mobile'] ?? ''); ?>" placeholder="موبایل" maxlength="11">
                        </div>
                        <div>
                            <label class="form-label-modal">از قم رفته</label>
                            <select name="from_qom" class="form-control-modal">
                                <option value="">همه</option>
                                <option value="1" <?php echo ($filters['from_qom'] ?? '') === '1' ? 'selected' : ''; ?>>بله</option>
                                <option value="0" <?php echo ($filters['from_qom'] ?? '') === '0' ? 'selected' : ''; ?>>خیر</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label-modal">شماره پرونده</label>
                            <input type="text" name="file_number" class="form-control-modal" value="<?php echo htmlspecialchars($filters['file_number'] ?? ''); ?>" placeholder="شماره پرونده">
                        </div>
                        <div>
                            <label class="form-label-modal">فوت شده</label>
                            <select name="is_deceased" class="form-control-modal">
                                <option value="">همه</option>
                                <option value="1" <?php echo ($filters['is_deceased'] ?? '') === '1' ? 'selected' : ''; ?>>بله</option>
                                <option value="0" <?php echo ($filters['is_deceased'] ?? '') === '0' ? 'selected' : ''; ?>>خیر</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label-modal">رشته</label>
                            <select name="specialty_id" class="form-control-modal">
                                <option value="">همه رشته‌ها</option>
                                <?php foreach ($specialties as $specialty): ?>
                                    <option value="<?php echo $specialty['id']; ?>" <?php echo ($filters['specialty_id'] ?? '') == $specialty['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($specialty['name_fa']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label-modal">مرکز درمانی</label>
                            <select name="clinic_name" class="form-control-modal">
                                <option value="">همه مراکز</option>
                                <?php foreach ($clinicNames as $clinicName): ?>
                                    <option value="<?php echo htmlspecialchars($clinicName); ?>" <?php echo ($filters['clinic_name'] ?? '') === $clinicName ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($clinicName); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label-modal">وضعیت</label>
                            <select name="status" class="form-control-modal">
                                <option value="">همه</option>
                                <option value="active" <?php echo ($filters['status'] ?? '') === 'active' ? 'selected' : ''; ?>>فعال</option>
                                <option value="inactive" <?php echo ($filters['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="button" class="btn-modal-cancel" onclick="resetFilters()">
                            <i class="fas fa-redo"></i> بازنشانی
                        </button>
                        <button type="submit" class="btn-modal-submit">
                            <i class="fas fa-search"></i> اعمال فیلتر
                        </button>
                        <?php if ($hasFilters): ?>
                        <button type="button" class="btn-modal-submit" onclick="exportToExcel()" style="background: #50cd89;">
                            <i class="fas fa-file-excel"></i> خروجی Excel
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-custom">
            <div class="toolbar">
                <div class="d-flex gap-2 flex-wrap">
                    <?php if (canView('doctors.create')): ?>
                    <button class="btn-add" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/add'">
                        <i class="fas fa-plus"></i>
                        افزودن پزشک
                    </button>
                    <?php endif; ?>
                    <?php if ($hasFilters && canView('doctors.export')): ?>
                    <button class="btn-add" onclick="exportToExcel()" style="background: #50cd89;">
                        <i class="fas fa-file-excel"></i>
                        خروجی Excel
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <table class="table-custom">
                    <thead>
                        <tr>
                            <th>پزشک</th>
                            <th>شماره نظام پزشکی</th>
                            <th>رشته پزشکی</th>
                            <th>مرکز درمانی</th>
                            <th>موبایل</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($doctors)): ?>
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
                                <td><?php echo htmlspecialchars($doctor['clinic_name'] ?? '-'); ?></td>
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
                                        <?php if (canView('doctors.edit')): ?>
                                        <button class="btn btn-action" onclick="window.location.href='<?php echo $baseUrl; ?>/doctors/edit/<?php echo $doctor['id']; ?>'" title="ویرایش" style="background: #ffc700; color: white;">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php if (canView('doctors.delete')): ?>
                                        <button class="btn btn-action" onclick="deleteDoctor(<?php echo $doctor['id']; ?>)" title="حذف" style="background: #f1416c; color: white;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">هیچ پزشکی یافت نشد</td>
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
                            <a class="page-link" href="<?php echo $baseUrl; ?>/doctors/list?page=<?php echo $currentPage - 1; ?><?php echo buildQueryString($filters); ?>">قبلی</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/doctors/list?page=<?php echo $i; ?><?php echo buildQueryString($filters); ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $baseUrl; ?>/doctors/list?page=<?php echo $currentPage + 1; ?><?php echo buildQueryString($filters); ?>">بعدی</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
function buildQueryString($filters) {
    $query = [];
    foreach ($filters as $key => $value) {
        if ($value !== '') {
            $query[] = $key . '=' . urlencode($value);
        }
    }
    return !empty($query) ? '&' . implode('&', $query) : '';
}
?>

<script>
function deleteDoctor(id) {
    if (confirm('آیا از حذف این پزشک اطمینان دارید؟')) {
        fetch('<?php echo $baseUrl; ?>/doctors/delete/' + id, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('پزشک با موفقیت حذف شد');
                location.reload();
            } else {
                alert('خطا در حذف پزشک');
            }
        });
    }
}

function resetFilters() {
    window.location.href = '<?php echo $baseUrl; ?>/doctors/list';
}

function exportToExcel() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    window.location.href = '<?php echo $baseUrl; ?>/doctors/export-excel?' + params.toString();
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
