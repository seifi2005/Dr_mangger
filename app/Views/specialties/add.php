<?php
/** @var array $errors */
/** @var array $old */
$pageTitle = 'افزودن رشته پزشکی - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">افزودن رشته پزشکی جدید</h1>
                <p class="breadcrumb-custom">خانه / رشته‌های پزشکی / افزودن رشته</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <form id="addSpecialtyForm" method="POST" action="<?php echo $baseUrl; ?>/specialties/store">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-modal">نام فارسی <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="specialtyNameFa" id="specialtyNameFa" placeholder="نام فارسی را وارد کنید" value="<?php echo htmlspecialchars($old['specialtyNameFa'] ?? ''); ?>" required>
                        <div class="error-message-modal"><?php echo $errors['name_fa'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">نام انگلیسی</label>
                        <input type="text" class="form-control-modal" name="specialtyNameEn" id="specialtyNameEn" placeholder="نام انگلیسی را وارد کنید" value="<?php echo htmlspecialchars($old['specialtyNameEn'] ?? ''); ?>">
                        <div class="error-message-modal"><?php echo $errors['name_en'] ?? ''; ?></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-modal">توضیحات</label>
                        <textarea class="form-control-modal" name="description" id="description" rows="4" placeholder="توضیحات را وارد کنید"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">وضعیت</label>
                        <select class="form-control-modal" name="status" id="status">
                            <option value="active" <?php echo ($old['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>فعال</option>
                            <option value="inactive" <?php echo ($old['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn-modal-cancel" onclick="window.location.href='<?php echo $baseUrl; ?>/specialties/list'">انصراف</button>
                    <button type="submit" class="btn-modal-submit">ثبت رشته</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

