<?php
/** @var string $baseUrl */
/** @var string $pageTitle */
/** @var string $error */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo rtrim($baseUrl, '/'); ?>/assets/css/login.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-pattern"></div>
    
    <div class="login-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="brand-content">
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-hospital"></i>
                    </div>
                </div>
                <h1 class="brand-name">سیستم مدیریت پزشکان</h1>
                <p class="brand-description">
                    سیستم جامع مدیریت اطلاعات پزشکان، مراکز درمانی و داروخانه‌ها
                </p>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="login-box">
                <h2 class="login-title">خوش آمدید</h2>
                <p class="login-subtitle">لطفاً برای ورود به سیستم، اطلاعات خود را وارد کنید</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert" style="margin-bottom: 20px; text-align: right;">
                        <?php
                        $errorMessages = [
                            'empty_fields' => 'لطفاً تمام فیلدها را پر کنید',
                            'invalid_credentials' => 'نام کاربری یا رمز عبور اشتباه است',
                            'inactive_user' => 'حساب کاربری شما غیرفعال است',
                            'invalid_request' => 'درخواست نامعتبر'
                        ];
                        echo $errorMessages[$error] ?? 'خطا در ورود به سیستم';
                        ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo $baseUrl; ?>/auth/authenticate" id="loginForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            نام کاربری یا کد ملی <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="نام کاربری یا کد ملی خود را وارد کنید"
                               required
                               autocomplete="username">
                        <div class="error-message" id="usernameError"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            رمز عبور <span class="required">*</span>
                        </label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="رمز عبور خود را وارد کنید"
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                        <div class="error-message" id="passwordError"></div>
                    </div>

                    <div class="form-options">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                مرا به خاطر بسپار
                            </label>
                        </div>
                        <a href="#" class="forgot-link">رمز عبور را فراموش کرده‌ام</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        ورود به سیستم
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            let hasError = false;

            // Clear previous errors
            document.getElementById('usernameError').classList.remove('show');
            document.getElementById('passwordError').classList.remove('show');

            if (username === '') {
                document.getElementById('usernameError').textContent = 'لطفاً نام کاربری را وارد کنید';
                document.getElementById('usernameError').classList.add('show');
                hasError = true;
            }

            if (password === '') {
                document.getElementById('passwordError').textContent = 'لطفاً رمز عبور را وارد کنید';
                document.getElementById('passwordError').classList.add('show');
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>

