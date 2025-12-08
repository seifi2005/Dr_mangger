<?php
/** @var array $center */
/** @var array $errors */
$pageTitle = 'ویرایش مرکز درمانی - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">ویرایش مرکز درمانی</h1>
                <p class="breadcrumb-custom">خانه / مراکز درمانی / ویرایش مرکز</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/medical-centers/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <form id="editCenterForm" method="POST" action="<?php echo $baseUrl; ?>/medical-centers/update/<?php echo $center['id']; ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-modal">نام مرکز <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="centerName" id="centerName" placeholder="نام مرکز را وارد کنید" value="<?php echo htmlspecialchars($center['name'] ?? ''); ?>" required>
                        <div class="error-message-modal"><?php echo $errors['name'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">نوع مرکز <span class="required">*</span></label>
                        <select class="form-control-modal" name="centerType" id="centerType" required>
                            <option value="">انتخاب کنید</option>
                            <option value="hospital" <?php echo ($center['type'] ?? '') === 'hospital' ? 'selected' : ''; ?>>بیمارستان</option>
                            <option value="clinic" <?php echo ($center['type'] ?? '') === 'clinic' ? 'selected' : ''; ?>>درمانگاه</option>
                            <option value="medical_center" <?php echo ($center['type'] ?? '') === 'medical_center' ? 'selected' : ''; ?>>مرکز درمانی</option>
                        </select>
                        <div class="error-message-modal"><?php echo $errors['type'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">شماره پروانه <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="licenseNumber" id="licenseNumber" placeholder="شماره پروانه را وارد کنید" value="<?php echo htmlspecialchars($center['license_number'] ?? ''); ?>" required>
                        <div class="error-message-modal"><?php echo $errors['license_number'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">نام مدیر</label>
                        <input type="text" class="form-control-modal" name="managerName" id="managerName" placeholder="نام مدیر را وارد کنید" value="<?php echo htmlspecialchars($center['manager_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">تلفن</label>
                        <input type="tel" class="form-control-modal" name="phone" id="phone" placeholder="021-12345678" value="<?php echo htmlspecialchars($center['phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">وضعیت</label>
                        <select class="form-control-modal" name="status" id="status">
                            <option value="active" <?php echo ($center['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>فعال</option>
                            <option value="inactive" <?php echo ($center['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-modal">آدرس</label>
                        <textarea class="form-control-modal" name="address" id="address" rows="3" placeholder="آدرس مرکز را وارد کنید"><?php echo htmlspecialchars($center['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn-modal-cancel" onclick="window.location.href='<?php echo $baseUrl; ?>/medical-centers/list'">انصراف</button>
                    <button type="submit" class="btn-modal-submit">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

