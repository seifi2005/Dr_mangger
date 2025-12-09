function toggleSidebar() {
    const sidebar = document.getElementById('sidebarPrimary');
    const mainContent = document.getElementById('mainContent');
    const toggleIcon = document.getElementById('toggleIcon');
    const isMobile = window.innerWidth <= 767;

    if (isMobile) {
        // On mobile, toggle overlay mode
        sidebar.classList.toggle('mobile-open');
        document.body.classList.toggle('sidebar-open');
    } else {
        // On desktop/tablet, toggle closed/expanded
        sidebar.classList.toggle('closed');
        mainContent.classList.toggle('expanded');

        if (sidebar.classList.contains('closed')) {
            toggleIcon.classList.remove('fa-chevron-left');
            toggleIcon.classList.add('fa-chevron-right');
        } else {
            toggleIcon.classList.remove('fa-chevron-right');
            toggleIcon.classList.add('fa-chevron-left');
        }
    }
}

// Close mobile sidebar when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebarPrimary');
    
    document.addEventListener('click', function(e) {
        const isMobile = window.innerWidth <= 767;
        if (isMobile && sidebar.classList.contains('mobile-open')) {
            // If clicking outside sidebar
            if (!sidebar.contains(e.target) && !e.target.closest('.toggle-sidebar-btn') && !e.target.closest('.sidebar-secondary')) {
                sidebar.classList.remove('mobile-open');
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        const isMobileNow = window.innerWidth <= 767;
        if (!isMobileNow) {
            if (sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // Add data-label attributes to table cells for mobile view
    const tables = document.querySelectorAll('.table-custom');
    tables.forEach(table => {
        const headers = table.querySelectorAll('thead th');
        const rows = table.querySelectorAll('tbody tr');
        
        headers.forEach((header, index) => {
            const label = header.textContent.trim();
            rows.forEach(row => {
                const cell = row.querySelectorAll('td')[index];
                if (cell) {
                    cell.setAttribute('data-label', label);
                }
            });
        });
    });
});

// مدیریت منوها به صورت تب‌بندی
document.addEventListener('DOMContentLoaded', function() {
    // تمام آیکون‌های سایدبار ثانویه
    const sidebarIcons = document.querySelectorAll('.sidebar-icon-item');
    
    // تمام محتواهای تب‌ها
    const menuContents = document.querySelectorAll('.menu-content');
    
    // مدیریت کلیک روی آیکون‌های سایدبار ثانویه
    sidebarIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            const menuId = this.getAttribute('data-menu');
            
            // غیرفعال کردن تمام آیکون‌ها
            sidebarIcons.forEach(item => {
                item.classList.remove('active');
            });
            
            // فعال کردن آیکون جاری
            this.classList.add('active');
            
            // مخفی کردن تمام محتواهای تب‌ها
            menuContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // نمایش محتوای تب جاری
            const currentContent = document.getElementById(menuId + 'Content');
            if (currentContent) {
                currentContent.classList.add('active');
            }
        });
    });
    
    // مدیریت آکاردئونی برای زیرمنوها در هر تب
    const menuItems = document.querySelectorAll('.menu-item[data-submenu]');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const submenuId = this.getAttribute('data-submenu');
            const submenu = document.getElementById(submenuId);
            const arrow = this.querySelector('.menu-arrow');
            
            // بستن تمام زیرمنوها در تب جاری
            const currentTab = this.closest('.menu-content');
            currentTab.querySelectorAll('.submenu').forEach(sub => {
                if (sub !== submenu) {
                    sub.classList.remove('expanded');
                }
            });
            
            // تغییر وضعیت آیکون‌های تمام منوها در تب جاری
            currentTab.querySelectorAll('.menu-arrow').forEach(arr => {
                if (arr !== arrow) {
                    arr.style.transform = 'rotate(0deg)';
                }
            });
            
            // باز یا بسته کردن منوی جاری
            if (submenu.classList.contains('expanded')) {
                submenu.classList.remove('expanded');
                arrow.style.transform = 'rotate(0deg)';
            } else {
                submenu.classList.add('expanded');
                arrow.style.transform = 'rotate(180deg)';
            }
        });
    });
    
    // مدیریت زیرمنوی کاربران
    const usersSubSubmenuToggle = document.getElementById('usersSubSubmenuToggle');
    const usersSubSubmenu = document.getElementById('usersSubSubmenuItems');
    
    if (usersSubSubmenuToggle && usersSubSubmenu) {
        usersSubSubmenuToggle.addEventListener('click', function(e) {
            e.stopPropagation(); // جلوگیری از اجرای کلیک روی منوی والد
            
            const arrow = this.querySelector('i');
            
            if (usersSubSubmenu.classList.contains('expanded')) {
                usersSubSubmenu.classList.remove('expanded');
                arrow.style.transform = 'rotate(0deg)';
            } else {
                usersSubSubmenu.classList.add('expanded');
                arrow.style.transform = 'rotate(180deg)';
            }
        });
    }
    
    // مدیریت کلیک روی آیتم‌های زیرمنو
    document.querySelectorAll('.submenu-item').forEach(item => {
        item.addEventListener('click', function() {
            // حذف کلاس active از تمام آیتم‌ها در تب جاری
            const currentTab = this.closest('.menu-content');
            currentTab.querySelectorAll('.submenu-item').forEach(i => {
                i.classList.remove('active');
            });
            
            // اضافه کردن کلاس active به آیتم جاری
            this.classList.add('active');
        });
    });
});

// ============= Modal Management =============
function openAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // جلوگیری از اسکرول صفحه
        // تنظیم تب پیش‌فرض
        switchUserType('system-admin');
    }
}

// ============= User Type Tabs Management =============
function switchUserType(userType) {
    // حذف active از تمام تب‌ها
    const tabs = document.querySelectorAll('.user-type-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // اضافه کردن active به تب انتخاب شده
    const selectedTab = document.querySelector(`[data-type="${userType}"]`);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // تنظیم مقدار userRole
    const userRoleInput = document.getElementById('userRole');
    if (userRoleInput) {
        userRoleInput.value = userType;
    }
    
    // پاک کردن خطای نوع دسترسی در صورت وجود
    clearModalError('userRole');
}

function closeAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = ''; // بازگشت اسکرول
        // پاک کردن فرم
        document.getElementById('addUserForm').reset();
        removeImage();
        // پاک کردن تمام خطاها
        clearAllModalErrors();
    }
}

// بستن Modal با کلیک روی overlay
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAddUserModal();
            }
        });
    }

    // بستن Modal با کلید ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('addUserModal');
            if (modal && modal.classList.contains('active')) {
                closeAddUserModal();
            }
        }
    });
});

// ============= Image Preview =============
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const uploadLabel = document.querySelector('.image-upload-label');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            uploadLabel.style.display = 'none';
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const preview = document.getElementById('imagePreview');
    const uploadLabel = document.querySelector('.image-upload-label');
    const imageInput = document.getElementById('userImage');

    preview.style.display = 'none';
    uploadLabel.style.display = 'flex';
    if (imageInput) {
        imageInput.value = '';
    }
}

// ============= Error Messages =============
const AddUserErrorMessages = {
    firstName: {
        required: 'لطفا نام را وارد کنید',
        minLength: 'نام باید حداقل 2 کاراکتر باشد',
        maxLength: 'نام نباید بیشتر از 50 کاراکتر باشد'
    },
    lastName: {
        required: 'لطفا نام خانوادگی را وارد کنید',
        minLength: 'نام خانوادگی باید حداقل 2 کاراکتر باشد',
        maxLength: 'نام خانوادگی نباید بیشتر از 50 کاراکتر باشد'
    },
    nationalCode: {
        required: 'لطفا کد ملی را وارد کنید',
        invalid: 'کد ملی باید 10 رقم باشد',
        format: 'کد ملی باید فقط شامل اعداد باشد'
    },
    mobile: {
        required: 'لطفا شماره موبایل را وارد کنید',
        invalid: 'شماره موبایل باید 11 رقم و با 09 شروع شود',
        format: 'فقط اعداد مجاز هستند'
    },
    userRole: {
        required: 'لطفا نوع دسترسی را انتخاب کنید'
    }
};

// ============= Error Handling Functions =============
function showModalError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + 'Error');
    const inputElement = document.getElementById(fieldId);
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
    }
    
    if (inputElement) {
        inputElement.classList.add('error');
    }
}

function clearModalError(fieldId) {
    const errorElement = document.getElementById(fieldId + 'Error');
    const inputElement = document.getElementById(fieldId);
    
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.remove('show');
    }
    
    if (inputElement) {
        inputElement.classList.remove('error');
    }
}

function clearAllModalErrors() {
    const fields = ['firstName', 'lastName', 'nationalCode', 'mobile', 'userRole'];
    fields.forEach(field => clearModalError(field));
}

// ============= Validation Functions =============
function validateFirstName(firstName) {
    if (!firstName || firstName.trim() === '') {
        return { valid: false, message: AddUserErrorMessages.firstName.required };
    }
    
    if (firstName.length < 2) {
        return { valid: false, message: AddUserErrorMessages.firstName.minLength };
    }
    
    if (firstName.length > 50) {
        return { valid: false, message: AddUserErrorMessages.firstName.maxLength };
    }
    
    return { valid: true };
}

function validateLastName(lastName) {
    if (!lastName || lastName.trim() === '') {
        return { valid: false, message: AddUserErrorMessages.lastName.required };
    }
    
    if (lastName.length < 2) {
        return { valid: false, message: AddUserErrorMessages.lastName.minLength };
    }
    
    if (lastName.length > 50) {
        return { valid: false, message: AddUserErrorMessages.lastName.maxLength };
    }
    
    return { valid: true };
}

function validateNationalCode(code) {
    if (!code || code.trim() === '') {
        return { valid: false, message: AddUserErrorMessages.nationalCode.required };
    }
    
    if (!/^\d+$/.test(code)) {
        return { valid: false, message: AddUserErrorMessages.nationalCode.format };
    }
    
    if (code.length !== 10) {
        return { valid: false, message: AddUserErrorMessages.nationalCode.invalid };
    }
    
    return { valid: true };
}

function validateMobile(mobile) {
    if (!mobile || mobile.trim() === '') {
        return { valid: false, message: AddUserErrorMessages.mobile.required };
    }
    
    if (!/^\d+$/.test(mobile)) {
        return { valid: false, message: AddUserErrorMessages.mobile.format };
    }
    
    if (mobile.length !== 11 || !mobile.startsWith('09')) {
        return { valid: false, message: AddUserErrorMessages.mobile.invalid };
    }
    
    return { valid: true };
}

function validateUserRole(role) {
    if (!role || role.trim() === '') {
        return { valid: false, message: AddUserErrorMessages.userRole.required };
    }
    
    // بررسی اینکه نوع کاربر معتبر باشد
    const validRoles = ['system-admin', 'operator', 'acceptor', 'service-provider', 'support'];
    if (!validRoles.includes(role)) {
        return { valid: false, message: 'نوع دسترسی انتخاب شده معتبر نیست' };
    }
    
    return { valid: true };
}

// ============= Form Submit =============
function submitAddUser() {
    // پاک کردن خطاهای قبلی
    clearAllModalErrors();
    
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const nationalCode = document.getElementById('nationalCode').value.trim();
    const mobile = document.getElementById('mobile').value.trim();
    const userRole = document.getElementById('userRole').value;
    const address = document.getElementById('address').value.trim();
    const userImage = document.getElementById('userImage').files[0];

    let hasError = false;

    // اعتبارسنجی نام
    const firstNameValidation = validateFirstName(firstName);
    if (!firstNameValidation.valid) {
        showModalError('firstName', firstNameValidation.message);
        hasError = true;
    }

    // اعتبارسنجی نام خانوادگی
    const lastNameValidation = validateLastName(lastName);
    if (!lastNameValidation.valid) {
        showModalError('lastName', lastNameValidation.message);
        hasError = true;
    }

    // اعتبارسنجی کد ملی
    const nationalCodeValidation = validateNationalCode(nationalCode);
    if (!nationalCodeValidation.valid) {
        showModalError('nationalCode', nationalCodeValidation.message);
        hasError = true;
    }

    // اعتبارسنجی موبایل
    const mobileValidation = validateMobile(mobile);
    if (!mobileValidation.valid) {
        showModalError('mobile', mobileValidation.message);
        hasError = true;
    }

    // اعتبارسنجی نوع دسترسی
    const userRoleValidation = validateUserRole(userRole);
    if (!userRoleValidation.valid) {
        showModalError('userRole', userRoleValidation.message);
        hasError = true;
    }

    // اگر خطایی وجود داشت، متوقف می‌شود
    if (hasError) {
        return;
    }

    // آماده‌سازی داده‌ها
    const userData = {
        firstName: firstName,
        lastName: lastName,
        nationalCode: nationalCode,
        mobile: mobile,
        userRole: userRole,
        roleLabel: getUserRoleLabel(userRole),
        address: address,
        image: userImage ? userImage.name : null
    };

    // در اینجا می‌توانید داده‌ها را به سرور ارسال کنید
    console.log('اطلاعات کاربر:', userData);
    
    // نمایش پیام موفقیت
    alert('کاربر با موفقیت ثبت شد!');
    
    // بستن Modal و پاک کردن فرم
    closeAddUserModal();
    
    // در اینجا می‌توانید کاربر جدید را به جدول اضافه کنید
    // addUserToTable(userData);
}

// تبدیل مقدار role به برچسب فارسی
function getUserRoleLabel(role) {
    const roleLabels = {
        'system-admin': 'مدیر سامانه',
        'operator': 'اپراتور',
        'acceptor': 'پذیرنده',
        'service-provider': 'سرویس دهنده',
        'support': 'پشتیبانی',
        'org-admin': 'مدیر سازمان',
        'senior-admin': 'مدیر ارشد'
    };
    return roleLabels[role] || role;
}

// ============= Form Tab Management =============
// This function is overridden in specific pages like doctors/add.php
// Only use this as fallback if page-specific function doesn't exist
if (typeof window.switchFormTab === 'undefined') {
    window.switchFormTab = function(tabName, event) {
        console.log('Using default switchFormTab from script.js');
    // Hide all tab contents
    document.querySelectorAll('.form-tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.form-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab content
    const selectedTab = document.getElementById(tabName + 'Tab');
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Add active class to clicked button
        if (event && event.target) {
    event.target.closest('.form-tab-btn').classList.add('active');
        }
    };
}

// ============= Medical System Functions =============
function submitAddDoctor() {
    clearAllModalErrors();
    
    const firstName = document.getElementById('firstName')?.value.trim();
    const lastName = document.getElementById('lastName')?.value.trim();
    const nationalCode = document.getElementById('nationalCode')?.value.trim();
    const medicalLicense = document.getElementById('medicalLicense')?.value.trim();
    const mobile = document.getElementById('mobile')?.value.trim();
    const email = document.getElementById('email')?.value.trim();
    const birthDate = document.getElementById('birthDate')?.value;
    const gender = document.getElementById('gender')?.value;
    const specialty = document.getElementById('specialty')?.value;
    const medicalCenter = document.getElementById('medicalCenter')?.value;
    const address = document.getElementById('address')?.value.trim();

    let hasError = false;

    if (!firstName) {
        showModalError('firstName', 'لطفا نام را وارد کنید');
        hasError = true;
    }

    if (!lastName) {
        showModalError('lastName', 'لطفا نام خانوادگی را وارد کنید');
        hasError = true;
    }

    if (!nationalCode || nationalCode.length !== 10) {
        showModalError('nationalCode', 'کد ملی باید 10 رقم باشد');
        hasError = true;
    }

    if (!medicalLicense) {
        showModalError('medicalLicense', 'لطفا شماره نظام پزشکی را وارد کنید');
        hasError = true;
    }

    if (!mobile || mobile.length !== 11 || !mobile.startsWith('09')) {
        showModalError('mobile', 'شماره موبایل باید 11 رقم و با 09 شروع شود');
        hasError = true;
    }

    if (!gender) {
        showModalError('gender', 'لطفا جنسیت را انتخاب کنید');
        hasError = true;
    }

    if (!specialty) {
        showModalError('specialty', 'لطفا رشته پزشکی را انتخاب کنید');
        hasError = true;
    }

    if (hasError) {
        return;
    }

    const doctorData = {
        firstName,
        lastName,
        nationalCode,
        medicalLicense,
        mobile,
        email,
        birthDate,
        gender,
        specialty,
        medicalCenter,
        address
    };

    console.log('اطلاعات پزشک:', doctorData);
    alert('پزشک با موفقیت ثبت شد!');
    window.location.href = 'doctors-list.html';
}

function submitAddPharmacy() {
    const pharmacyName = document.getElementById('pharmacyName')?.value.trim();
    const licenseNumber = document.getElementById('licenseNumber')?.value.trim();
    const ownerName = document.getElementById('ownerName')?.value.trim();
    const ownerNationalCode = document.getElementById('ownerNationalCode')?.value.trim();
    const phone = document.getElementById('phone')?.value.trim();
    const address = document.getElementById('address')?.value.trim();

    if (!pharmacyName || !licenseNumber || !ownerName || !ownerNationalCode || !phone || !address) {
        alert('لطفا تمام فیلدهای اجباری را پر کنید');
        return;
    }

    console.log('اطلاعات داروخانه:', { pharmacyName, licenseNumber, ownerName, ownerNationalCode, phone, address });
    alert('داروخانه با موفقیت ثبت شد!');
    window.location.href = 'pharmacies-list.html';
}

function submitAddMedicalCenter() {
    const centerName = document.getElementById('centerName')?.value.trim();
    const centerType = document.getElementById('centerType')?.value;
    const licenseNumber = document.getElementById('licenseNumber')?.value.trim();
    const managerName = document.getElementById('managerName')?.value.trim();
    const phone = document.getElementById('phone')?.value.trim();
    const address = document.getElementById('address')?.value.trim();

    if (!centerName || !centerType || !licenseNumber || !managerName || !phone || !address) {
        alert('لطفا تمام فیلدهای اجباری را پر کنید');
        return;
    }

    console.log('اطلاعات مرکز درمانی:', { centerName, centerType, licenseNumber, managerName, phone, address });
    alert('مرکز درمانی با موفقیت ثبت شد!');
    window.location.href = 'medical-centers-list.html';
}

function submitAddSpecialty() {
    const specialtyNameFa = document.getElementById('specialtyNameFa')?.value.trim();
    const specialtyCode = document.getElementById('specialtyCode')?.value.trim();

    if (!specialtyNameFa || !specialtyCode) {
        alert('لطفا نام رشته و کد رشته را وارد کنید');
        return;
    }

    console.log('اطلاعات رشته پزشکی:', { specialtyNameFa, specialtyCode });
    alert('رشته پزشکی با موفقیت ثبت شد!');
    window.location.href = 'medical-specialties-list.html';
}

// اضافه کردن event listener برای پاک کردن خطاها هنگام تایپ
document.addEventListener('DOMContentLoaded', function() {
    const firstNameInput = document.getElementById('firstName');
    const lastNameInput = document.getElementById('lastName');
    const nationalCodeInput = document.getElementById('nationalCode');
    const mobileInput = document.getElementById('mobile');
    if (firstNameInput) {
        firstNameInput.addEventListener('input', function() {
            clearModalError('firstName');
        });
    }

    if (lastNameInput) {
        lastNameInput.addEventListener('input', function() {
            clearModalError('lastName');
        });
    }

    if (nationalCodeInput) {
        nationalCodeInput.addEventListener('input', function() {
            // فقط اعداد مجاز
            this.value = this.value.replace(/[^0-9]/g, '');
            clearModalError('nationalCode');
        });
    }

    if (mobileInput) {
        mobileInput.addEventListener('input', function() {
            // فقط اعداد مجاز
            this.value = this.value.replace(/[^0-9]/g, '');
            clearModalError('mobile');
        });
    }
});

// ============= User Profile Modal =============
function openUserProfileModal() {
    const modal = document.getElementById('userProfileModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeUserProfileModal() {
    const modal = document.getElementById('userProfileModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modal on overlay click
document.addEventListener('DOMContentLoaded', function() {
    const userProfileModal = document.getElementById('userProfileModal');
    if (userProfileModal) {
        userProfileModal.addEventListener('click', function(e) {
            if (e.target === userProfileModal) {
                closeUserProfileModal();
            }
        });
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('userProfileModal');
            if (modal && modal.classList.contains('active')) {
                closeUserProfileModal();
            }
        }
    });
});

