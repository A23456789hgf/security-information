<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css"  rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">   

    <!-- خط Google Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- التنسيقات المخصصة -->
    <style>
        :root {
            --primary-color: #4361ee;   
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 260px;
            --navbar-height: 60px;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            padding: 0;
            margin: 0;
        }

        /* الشريط العلوي */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1100;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-left: 10px;
            font-size: 1.8rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .nav-link i {
            margin-left: 8px;
        }

        /* الشريط الجانبي */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: var(--navbar-height);
            right: 0;
            bottom: 0;
            background: white;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            background-color: var(--light-color);
            border-bottom: 1px solid #eee;
        }

        .sidebar-menu {
            padding: 0;
            list-style: none;
        }

        .sidebar-menu li {
            position: relative;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            border-right: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            border-right-color: var(--primary-color);
        }

        .sidebar-menu a.active {
            font-weight: 600;
        }

        .sidebar-menu i {
            margin-left: 10px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* أنماط القائمة المنسدلة */
        .sidebar-menu .dropdown-toggle {
            position: relative;
            display: flex;
            align-items: center;
        }

        .sidebar-menu .dropdown-toggle i.float-end {
            transition: transform 0.3s ease;
        }

        .sidebar-menu .dropdown.show .dropdown-toggle i.float-end {
            transform: rotate(90deg);
        }

        .sidebar-menu .collapse {
            direction: rtl;
            padding-right: 20px;
        }

        .sidebar-menu .collapse li a {
            padding: 10px 20px;
            display: block;
            color: #555;
            font-size: 0.95rem;
        }

        /* المحتوى الرئيسي */
        .main-content {
            margin-right: var(--sidebar-width);
            padding: 20px;
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            transition: all 0.3s;
        }

        /* التجاوب مع الشاشات الصغيرة */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(var(--sidebar-width));
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-right: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- الشريط العلوي -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shield-alt"></i>
                نظام الدولة
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-home"></i>
                            الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user"></i>
                            الملف الشخصي
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-sign-out-alt"></i>
                            تسجيل الخروج
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- الشريط الجانبي -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5 class="text-center">القائمة الرئيسية</h5>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="#" class="active">
                    <i class="fas fa-tachometer-alt"></i>
                    لوحة التحكم
                </a>
            </li>

            <!-- قائمة الترميزات -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#codingSubMenu" aria-expanded="false">
                    <i class="fas fa-code"></i>
                    الترميزات
                    <i class="fas fa-angle-left float-end"></i>
                </a>
                <ul class="collapse list-unstyled" id="codingSubMenu">
                    <li><a href="{{ route('blood-types.index') }}"><i class="fas fa-tint"></i> فصائل الدم</a></li>
                    <li><a href="{{ route('marital-statuses.index') }}"><i class="fas fa-heart"></i> الحالة الاجتماعية</a></li>
                    <li><a href="{{ route('qualifications.index') }}"><i class="fas fa-graduation-cap"></i> المؤهلات العلمية</a></li>
                    <li><a href="{{ route('number-classifications.index') }}"><i class="fas fa-hashtag"></i> تصنيف الرقم</a></li>
                    <li><a href="{{ route('job-statuses.index') }}"><i class="fas fa-briefcase"></i> حالة الوظيفة</a></li>
                    <li><a href="{{ route('ranks.index') }}"><i class="fas fa-star"></i> الرتب</a></li>
                    <li><a href="{{ route('military-districts.index') }}"><i class="fas fa-star"></i> الدائرة / المنطقة</a></li>
                    <li><a href="{{ route('division.index') }}"><i class="fas fa-star"></i> الشعبة</a></li>
                    <li><a href="{{ route('course-types.index') }}"><i class="fas fa-star"></i> نوع الدورة </a></li>
                    <li><a href="{{ route('filling-agencies.index') }}"><i class="fas fa-star"></i> جهة التعبئة للملف </a></li>
                    <li><a href="{{ route('electronic-inputs.index') }}"><i class="fas fa-star"></i> مدخل الكتروني </a></li>
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="fas fa-users"></i>
                    المستخدمين
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-cog"></i>
                    الإعدادات
                </a>
            </li>
        </ul>
    </div>

    <!-- المحتوى الرئيسي -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS مع Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>   

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>   

    <!-- السكربتات المخصصة -->
    <script>
        $(document).ready(function() {
            // تبديل الشريط الجانبي في الشاشات الصغيرة
            $('.navbar-toggler').click(function() {
                $('.sidebar').toggleClass('active');
            });

            // تغيير العنصر النشط في القائمة الجانبية
            $('.sidebar-menu a').click(function() {
                $('.sidebar-menu a').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>