@extends('layouts.app_plain')

@section('title', 'تسجيل الدخول - النظام الموحد')

@section('content')
    <div class="split-screen-container">

        <div class="right-side">
            <div class="overlay"></div>
            <div class="brand-content">
                <div class="main-logo">
                    <!-- تم استبدال الأيقونة بالصورة الخاصة بك -->
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 350px; height:300px;">
                </div>
                <h1 class="brand-name">نظام المعلومات </h1>
                <p class="brand-tagline"> نظام المعلومات بالامن الوقائي </p>
            </div>
            <div class="footer-note">إصدار النظام 2026.1.0</div>
        </div>

        <div class="left-side">
            <div class="login-wrapper">
                <div class="form-header">
                    <h2>تسجيل الدخول</h2>
                    <!-- <p>مرحباً بك مجدداً، يرجى إدخال بيانات الاعتماد الخاصة بك.</p> -->
                </div>

                @if ($errors->any())
                    <div class="alert-modern">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="standard-form">
                    @csrf

                    <div class="form-group-custom">
                        <label>اسم المستخدم</label>
                        <input type="text" name="username" placeholder="أدخل اسم المستخدم" value="{{ old('username') }}"
                            required autofocus>
                    </div>

                    <div class="form-group-custom">
                        <label>كلمة المرور</label>
                        <div class="password-input-wrapper">
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <i class="far fa-eye" id="togglePassword" onclick="togglePass()"></i>
                        </div>
                    </div>

                    <div class="form-utils">
                        <label class="remember-me-label">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            تذكرني
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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #1e293b;
            --accent-blue: #2563eb;
            --bg-light: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            direction: rtl;
            overflow: hidden;
        }

        .split-screen-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* القسم الأيمن (الشعار) */
        .right-side {
            flex: 1.2;
            background: #1e293b url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
            background-size: cover;
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
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.8) 100%);
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .main-logo {
            margin-bottom: 20px;
            /* إزالة ستايلات الخط وتعديل الظل للصورة */
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.2));
        }

        .brand-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .brand-tagline {
            font-size: 1.1rem;
            opacity: 0.8;
            font-weight: 300;
            max-width: 400px;
            line-height: 1.6;
        }

        .footer-note {
            position: absolute;
            bottom: 30px;
            font-size: 0.8rem;
            opacity: 0.5;
            z-index: 2;
        }

        /* القسم الأيسر (النموذج) */
        .left-side {
            flex: 1;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .form-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 35px;
        }

        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-group-custom label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .form-group-custom input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f8fafc;
        }

        .form-group-custom input:focus {
            outline: none;
            border-color: var(--accent-blue);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
        }

        .form-utils {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.85rem;
        }

        .forgot-link {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .remember-me-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
        }

        .btn-login-exec {
            width: 100%;
            padding: 16px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login-exec:hover {
            background: #000;
            transform: translateY(-2px);
        }

        .alert-modern {
            background: #fff1f2;
            color: #be123c;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ffe4e6;
        }

        .login-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .login-footer a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .right-side {
                display: none;
            }

            .left-side {
                flex: 1;
                padding: 30px;
            }
        }
    </style>

    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePassword');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('far', 'fas');
            } else {
                input.type = 'password';
                icon.classList.replace('fas', 'far');
            }
        }
    </script>
@endsection