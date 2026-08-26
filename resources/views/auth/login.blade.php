<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول - النظام الموحد</title>
    <!-- Local Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', 'Cairo', system-ui, sans-serif;
            background: #f1f5f9;
            height: 100vh;
            overflow: hidden;
        }

        /* الحاوية الرئيسية بدون أي header */
        .split-screen-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* القسم الأيمن (الشعار والخلفية) */
        .right-side {
            flex: 1.2;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            text-align: center;
        }

        .right-side .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.85) 100%);
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .main-logo img {
    width: 300px;
    height: 300px;
    object-fit: contain;
    filter: drop-shadow(0 0 12px rgba(0,0,0,0.3));
}
        .brand-name {
            font-size: 2.2rem;
            font-weight: 700;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .brand-tagline {
            font-size: 1rem;
            opacity: 0.85;
            font-weight: 300;
        }

        .footer-note {
            position: absolute;
            bottom: 25px;
            font-size: 0.75rem;
            opacity: 0.5;
            z-index: 2;
        }

        /* القسم الأيسر (النموذج) */
        .left-side {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .form-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-group-custom {
            margin-bottom: 22px;
        }

        .form-group-custom label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .form-group-custom input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            background: #f8fafc;
            transition: 0.2s;
            font-family: inherit;
        }

        .form-group-custom input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .form-utils {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 30px;
            font-size: 0.85rem;
        }

        .remember-me-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #475569;
        }

        .forgot-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-login-exec {
            width: 100%;
            padding: 14px;
            background: #1e293b;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-login-exec:hover {
            background: #0f172a;
            transform: translateY(-1px);
        }

        .alert-modern {
            background: #fff1f2;
            border: 1px solid #ffe4e6;
            color: #be123c;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
        }

        .login-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
        }

        .login-footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .right-side {
                display: none;
            }
            .left-side {
                flex: 1;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="split-screen-container">
        <!-- القسم الأيمن: الشعار والخلفية -->
        <div class="right-side">
            <div class="overlay"></div>
            <div class="brand-content">
                <div class="main-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="الشعار الرئيسي">
                </div>
                <h1 class="brand-name">نظام المعلومات</h1>
                <p class="brand-tagline">نظام المعلومات بالأمن الوقائي</p>
            </div>
            <div class="footer-note">إصدار النظام 2026.1.0</div>
        </div>

        <!-- القسم الأيسر: نموذج الدخول فقط (بدون أي شريط علوي) -->
        <div class="left-side">
            <div class="login-wrapper">
                <div class="form-header">
                    <h2>تسجيل الدخول</h2>
                </div>

                @if ($errors->any())
                    <div class="alert-modern">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group-custom">
                        <label>اسم المستخدم</label>
                        <input type="text" name="username" placeholder="أدخل اسم المستخدم" value="{{ old('username') }}" required autofocus>
                    </div>

                    <div class="form-group-custom">
                        <label>كلمة المرور</label>
                        <div class="password-input-wrapper">
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <i class="far fa-eye" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                        </div>
                    </div>

                    <div class="form-utils">
                        <label class="remember-me-label">
                            <input type="checkbox" name="remember" checked>
                            <span class="checkmark"></span> تذكرني (البقاء مسجلاً لمدة طويلة)
                        </label>
                        <a href="#" class="forgot-link">هل نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit" class="btn-login-exec">
                        <span>دخول المنصة</span>
                    </button>
                </form>

                <div class="login-footer">
                    <p>تواجه مشكلة في الدخول؟ <a href="#">اتصل بالدعم الفني</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePassword');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>