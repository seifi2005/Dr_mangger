<?php
/** @var array $user */
/** @var array $errors */
$pageTitle = 'ویرایش کاربر - سیستم مدیریت پزشکان';
?>
<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-start">
                <h1 class="page-title">ویرایش کاربر</h1>
                <p class="breadcrumb-custom">خانه / مدیریت کاربران / ویرایش کاربر</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-new-user" onclick="window.location.href='<?php echo $baseUrl; ?>/users/list'">بازگشت به لیست</button>
            </div>
        </div>
    </div>

    <div class="content-area">
        <div class="card-custom">
            <form id="editUserForm" method="POST" action="<?php echo $baseUrl; ?>/users/update/<?php echo $user['id']; ?>" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-modal">نام <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="firstName" id="firstName" placeholder="نام را وارد کنید" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                        <div class="error-message-modal" id="firstNameError"><?php echo $errors['first_name'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">نام خانوادگی <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="lastName" id="lastName" placeholder="نام خانوادگی را وارد کنید" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                        <div class="error-message-modal" id="lastNameError"><?php echo $errors['last_name'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">کد ملی <span class="required">*</span></label>
                        <input type="text" class="form-control-modal" name="nationalCode" id="nationalCode" placeholder="کد ملی را وارد کنید" maxlength="10" value="<?php echo htmlspecialchars($user['national_code'] ?? ''); ?>" required>
                        <div class="error-message-modal" id="nationalCodeError"><?php echo $errors['national_code'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">موبایل</label>
                        <input type="tel" class="form-control-modal" name="mobile" id="mobile" placeholder="09123456789" maxlength="11" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>">
                        <div class="error-message-modal" id="mobileError"><?php echo $errors['mobile'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">ایمیل</label>
                        <input type="email" class="form-control-modal" name="email" id="email" placeholder="email@example.com" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        <div class="error-message-modal" id="emailError"><?php echo $errors['email'] ?? ''; ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">نقش <span class="required">*</span></label>
                        <select class="form-control-modal" name="userRole" id="userRole" required>
                            <option value="">انتخاب کنید</option>
                            <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>مدیر</option>
                            <option value="user" <?php echo ($user['role'] ?? '') === 'user' ? 'selected' : ''; ?>>کاربر</option>
                        </select>
                        <div class="error-message-modal" id="userRoleError"><?php echo $errors['role'] ?? ''; ?></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-modal">آدرس</label>
                        <textarea class="form-control-modal" name="address" id="address" rows="3" placeholder="آدرس را وارد کنید"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">وضعیت</label>
                        <select class="form-control-modal" name="status" id="status">
                            <option value="active" <?php echo ($user['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>فعال</option>
                            <option value="inactive" <?php echo ($user['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modal">تصویر کاربر</label>
                        <div class="image-upload-wrapper">
                            <input type="file" class="image-input" name="userImage" id="userImage" accept="image/*" onchange="previewImage(this)">
                            <label for="userImage" class="image-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>برای آپلود تصویر کلیک کنید</span>
                            </label>
                            <div class="image-preview" id="imagePreview" style="<?php echo !empty($user['image']) ? 'display: block;' : 'display: none;'; ?>">
                                <img id="previewImg" src="<?php echo !empty($user['image']) ? $baseUrl . '/uploads/' . htmlspecialchars($user['image']) : ''; ?>" alt="Preview">
                                <button type="button" class="remove-image" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn-modal-cancel" onclick="window.location.href='<?php echo $baseUrl; ?>/users/list'">انصراف</button>
                    <button type="submit" class="btn-modal-submit">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('userImage').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

