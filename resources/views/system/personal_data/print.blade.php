@extends('layouts.print')

@section('title', 'تقرير البيانات الشخصية - ' . ($personalData->name ?? 'موظف'))

@section('styles')
    <style>
        /* تنسيقات مخصصة للطباعة على A4 */
        body {
            font-family: 'Cairo', 'Tahoma', sans-serif;
            direction: rtl;
            background: white;
            padding: 1.5cm 1cm;
            margin: 0;
            line-height: 1.6;
        }

        .print-container {
            max-width: 100%;
            margin: 0 auto;
        }

        /* رأس التقرير الرسمي */
        .official-header {
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .official-header::after {
            content: '';
            position: absolute;
            bottom: -3px;
            right: 0;
            width: 30%;
            height: 3px;
            background-color: #e74c3c;
        }

        .ministry-logo {
            font-size: 48px;
            color: #2c3e50;
        }

        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0 5px;
        }

        .ref-number {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        /* بطاقة الصورة الشخصية */
        .photo-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 15px;
            color: white;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .photo-frame {
            width: 180px;
            height: 180px;
            margin: 0 auto 15px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            overflow: hidden;
            background: white;
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-frame .no-photo {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            color: #6c757d;
            font-size: 80px;
        }

        .photo-card h3 {
            font-size: 18px;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .photo-card p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        /* أقسام التقرير */
        .section-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            page-break-inside: avoid;
        }

        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .section-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }

        .section-header i {
            margin-left: 10px;
            color: #667eea;
        }

        .section-body {
            padding: 20px;
        }

        /* الجداول */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table th,
        .info-table td {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
        }

        .info-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            width: 30%;
            font-size: 14px;
        }

        .info-table td {
            font-size: 15px;
            color: #34495e;
        }

        .info-table tr:hover td {
            background-color: #f8f9fa;
        }

        /* الجداول الصغيرة */
        .compact-table {
            width: 100%;
            border-collapse: collapse;
        }

        .compact-table th,
        .compact-table td {
            padding: 10px 12px;
            border: 1px solid #e9ecef;
        }

        .compact-table th {
            background-color: #f1f3f5;
            font-weight: 600;
            color: #2c3e50;
            width: 35%;
            font-size: 13px;
        }

        .compact-table td {
            font-size: 14px;
        }

        /* العناوين الفرعية */
        .subtitle {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #667eea;
        }

        /* تذييل الصفحة */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            font-size: 13px;
            color: #6c757d;
        }

        .signature-area {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 2px solid #2c3e50;
            margin: 5px 0 10px;
        }

        /* ختم التقرير */
        .stamp {
            position: relative;
            width: 120px;
            height: 120px;
            border: 3px solid #e74c3c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
            opacity: 0.7;
            transform: rotate(-15deg);
        }

        .stamp::before {
            content: '●';
            position: absolute;
            width: 100%;
            height: 100%;
            font-size: 100px;
            opacity: 0.2;
        }

        /* تحكم في الطباعة */
        @media print {
            body {
                padding: 0.5cm;
            }

            .no-print {
                display: none !important;
            }

            .section-card {
                break-inside: avoid;
            }

            .photo-card {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .section-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* شبكة متجاوبة */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }

        .col-4 {
            width: 33.333%;
            padding: 0 12px;
        }

        .col-6 {
            width: 50%;
            padding: 0 12px;
        }

        .col-8 {
            width: 66.667%;
            padding: 0 12px;
        }

        .col-12 {
            width: 100%;
            padding: 0 12px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }
    </style>
@endsection

@section('content')
    <div class="print-container">
        <!-- رأس التقرير الرسمي -->
        <div class="official-header">
            <div class="row align-items-center">
                <div class="col-4 text-end">
                    <div class="ministry-logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h6 class="mb-0">الجمهورية اليمنية</h6>
                    <h5 class="fw-bold">التعبئة العامة</h5>
                    <p>وحدة المعلومات</p>
                </div>

                <div class="col-4 text-center">
                    <h2 class="report-title">تقرير البيانات الشخصية</h2>
                    <p class="text-muted">Personal Data Report</p>
                </div>

                <div class="col-4 text-start">
                    <div class="ref-number">
                        <p class="mb-1"><strong>الرقم الصمرجعي:</strong>
                            #{{ str_pad($personalData->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="mb-1"><strong>تاريخ التقرير:</strong> {{ now()->format('d/m/Y') }}</p>
                        <p class="mb-0"><strong>الصفحة:</strong> 1/1</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصورة الشخصية والبيانات الأساسية -->
        <div class="row mb-4">
            <div class="col-4">
                <div class="photo-card">
                    <div class="photo-frame">
                        @if($personalData->main_profile_photo)
                            <img src="{{ public_path('storage/' . $personalData->main_profile_photo) }}" alt="الصورة الشخصية">
                        @else
                            <div class="no-photo">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        @endif
                    </div>
                    <h3>{{ $personalData->name ?? 'غير محدد' }}</h3>
                    <p>{{ $personalData->rank->rank ?? '-----' }}</p>
                </div>
            </div>

            <div class="col-8">
                <div class="section-card">
                    <div class="section-header">
                        <h5><i class="fas fa-id-card"></i> البيانات التعريفية الأساسية</h5>
                    </div>
                    <div class="section-body">
                        <table class="info-table">
                            <tr>
                                <th>الاسم الرباعي واللقب</th>
                                <td colspan="3" class="fw-bold">{{ $personalData->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>الاسم الجهادي</th>
                                <td>{{ $personalData->jihadi_name ?? '-' }}</td>
                                <th>الرقم الوطني</th>
                                <td>{{ $personalData->national_id_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ الميلاد (ميلادي)</th>
                                <td>{{ $personalData->date_of_birth_gregorian ? $personalData->date_of_birth_gregorian->format('d/m/Y') : '-' }}
                                </td>
                                <th>تاريخ الميلاد (هجري)</th>
                                <td>{{ $personalData->date_of_birth_hijri ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>الجنسية</th>
                                <td>يمني</td>
                                <th>الديانة</th>
                                <td>مسلم</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- البيانات الشخصية التفصيلية -->
        <div class="row">
            <div class="col-6">
                <div class="section-card h-100">
                    <div class="section-header">
                        <h5><i class="fas fa-heartbeat"></i> البيانات الطبية والاجتماعية</h5>
                    </div>
                    <div class="section-body">
                        <table class="compact-table">
                            <tr>
                                <th>فصيلة الدم</th>
                                <td>
                                    <span class="badge bg-danger">{{ $personalData->bloodType->type ?? '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>الحالة الاجتماعية</th>
                                <td>{{ $personalData->maritalStatus->status ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>عدد الأبناء</th>
                                <td>{{ $personalData->number_of_children ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>المؤهل العلمي</th>
                                <td>{{ $personalData->qualification->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>التخصص</th>
                                <td>{{ $personalData->specialization ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>تصنيف الرقم</th>
                                <td>{{ $personalData->numberClassification->classification ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="section-card h-100">
                    <div class="section-header">
                        <h5><i class="fas fa-briefcase"></i> البيانات الوظيفية والعسكرية</h5>
                    </div>
                    <div class="section-body">
                        <table class="compact-table">
                            <tr>
                                <th>الرتبة</th>
                                <td><strong>{{ $personalData->rank->rank ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <th>حالة الوظيفة</th>
                                <td>{{ $personalData->jobStatus->job_status ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ التعيين</th>
                                <td>{{ $personalData->appointment_date ? $personalData->appointment_date->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>الرقم العسكري</th>
                                <td>{{ $personalData->military_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>الدائرة العسكرية</th>
                                <td>{{ $personalData->militaryDistrict->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>الشعبة / الوحدة</th>
                                <td>{{ $personalData->division->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- مكان السكن -->
        <div class="section-card">
            <div class="section-header">
                <h5><i class="fas fa-home"></i> بيانات السكن الحالي</h5>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-6">
                        <table class="compact-table">
                            <tr>
                                <th>المحافظة</th>
                                <td>{{ $personalData->governorate->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>المديرية</th>
                                <td>{{ $personalData->directorate->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="compact-table">
                            <tr>
                                <th>العزلة / المنطقة</th>
                                <td>{{ $personalData->subArea->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>القرية / الشارع</th>
                                <td>{{ $personalData->village->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- مكان الميلاد -->
        <div class="section-card">
            <div class="section-header">
                <h5><i class="fas fa-map-marker-alt"></i> بيانات الميلاد</h5>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-6">
                        <table class="compact-table">
                            <tr>
                                <th>الدولة</th>
                                <td>{{ $personalData->placeOfBirthCountry->name ?? 'اليمن' }}</td>
                            </tr>
                            <tr>
                                <th>المحافظة</th>
                                <td>{{ $personalData->placeOfBirthGovernorate->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="compact-table">
                            <tr>
                                <th>المديرية</th>
                                <td>{{ $personalData->placeOfBirthDirectorate->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>القرية / الحي</th>
                                <td>{{ $personalData->placeOfBirthVillage->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات إضافية -->
        @if($personalData->notes)
            <div class="section-card">
                <div class="section-header">
                    <h5><i class="fas fa-sticky-note"></i> ملاحظات إضافية</h5>
                </div>
                <div class="section-body">
                    <p class="mb-0">{{ $personalData->notes }}</p>
                </div>
            </div>
        @endif

        <!-- توقيع وختم التقرير -->
        <div class="signature-area">
            <div class="row">
                <div class="col-4 text-center">
                    <div class="signature-line mx-auto"></div>
                    <p class="mb-0">توقيع الموظف</p>
                </div>
                <div class="col-4 text-center">
                    <div class="signature-line mx-auto"></div>
                    <p class="mb-0">مدير الإدارة</p>
                </div>
                <div class="col-4 text-center">
                    <div class="stamp mx-auto">
                        مختوم
                    </div>
                </div>
            </div>
        </div>

        <!-- تذييل الصفحة -->
        <div class="report-footer">
            <div class="row">
                <div class="col-6 text-end">
                    <!-- <p class="mb-0">نظام إدارة شؤون الموظفين - وزارة الداخلية</p> -->
                </div>
                <div class="col-6 text-start">
                    <p class="mb-0">تم الإنشاء بواسطة: {{ auth()->user()->name ?? 'النظام' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- زر الطباعة (يظهر فقط في الشاشة وليس عند الطباعة) -->
    <div class="no-print text-center mt-5">
        <button onclick="window.print()" class="btn btn-primary btn-lg px-5 shadow">
            <i class="fas fa-print me-2"></i>
            طباعة التقرير بصيغة PDF
        </button>

        <button onclick="window.history.back()" class="btn btn-secondary btn-lg px-5 shadow me-2">
            <i class="fas fa-arrow-right me-2"></i>
            رجوع
        </button>
    </div>
@endsection