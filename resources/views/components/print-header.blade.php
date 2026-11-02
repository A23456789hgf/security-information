@props([
    'title' => 'تقرير رسمي',
    'subCode' => 'وثيقة قيد وسجل بيانات • معتمدة رقمياً',
    'refNumber' => null,
    'date' => null,
    'time' => null,
    'republic' => 'الجمهورية اليمنية',
    'ministry' => 'وزارة الدفاع',
    'department' => 'دائرة شؤون الأفراد والاحتياط العام'
])

<header class="official-header">
    <!-- اليمين -->
    <div class="header-right">
        <h3 class="republic-name">{{ $republic }}</h3>
        <h4 class="ministry-title">{{ $ministry }}</h4>
        <p class="dept-title">{{ $department }}</p>
    </div>

    <!-- الوسط -->
    <div class="header-center">
        <div class="national-emblem-wrap">
            <img src="{{ asset('images/logo1.png') }}" alt="شعار" class="national-logo" onerror="this.onerror=null; this.src='/images/logo1.png';">
        </div>
        <h2 class="report-main-title">{{ $title }}</h2>
        @if($subCode)
            <span class="report-sub-code">{{ $subCode }}</span>
        @endif
    </div>

    <!-- اليسار -->
    <div class="header-left">
        <div class="meta-verification-card">
            <div class="meta-row">
                <span>الرقم المرجعي:</span>
                <strong>{{ $refNumber ?? ('#' . date('Ymd') . '-' . rand(100, 999)) }}</strong>
            </div>
            <div class="meta-row">
                <span>تاريخ الإصدار:</span>
                <span>{{ $date ?? date('Y/m/d') }}</span>
            </div>
            <div class="meta-row">
                <span>وقت التصدير:</span>
                <span>{{ $time ?? date('h:i A') }}</span>
            </div>
        </div>
    </div>
</header>
