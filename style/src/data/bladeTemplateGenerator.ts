import { PrintSettings } from '../types';

export function generateBladeCode(settings: PrintSettings): string {
  const isGold = settings.theme === 'republic_gold';
  const isMonochrome = settings.theme === 'monochrome_laser';

  const primaryColor = isMonochrome ? '#0f172a' : (isGold ? '#1e3a5f' : '#0f2942');
  const accentColor = isMonochrome ? '#334155' : (isGold ? '#b45309' : '#b91c1c');
  const headerBorderColor = isMonochrome ? '#0f172a' : (isGold ? '#b45309' : '#0f2942');
  const tableHeaderBg = isMonochrome ? '#f1f5f9' : (isGold ? '#fef3c7' : '#f0f4f8');
  const tableHeaderColor = isMonochrome ? '#0f172a' : (isGold ? '#78350f' : '#0f2942');

  return `@extends('layouts.print')

{{-- 
=============================================================================
* تقرير السجل الأساسي والبيانات الشخصية - نسخة الطباعة المعتمدة والمحسّنة
* تم التطوير والتنسيق وفق المعايير الإدارية والسيادية للطباعة الورقية وتصدير PDF
* يدعم: المتصفح مباشرة (Ctrl+P)، حزم PDF (Laravel DomPDF / Browsershot / Snappy)
=============================================================================
--}}

@section('title', 'تقرير السجل الأساسي - ' . ($personalData->name ?? 'منتسب'))

@section('styles')
    <style>
        /* -------------------------------------------------------------
           1. إعدادات قياس الصفحة وهوامش الطباعة القياسية (A4 Portrait)
        ------------------------------------------------------------- */
        @page {
            size: A4 portrait;
            margin: ${settings.compactA4 ? '10mm 12mm' : '15mm 15mm'};
        }

        /* تحسينات الخط والتوافق الطباعي */
        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            font-family: 'Cairo', 'Tajawal', 'Almarai', 'Segoe UI', Tahoma, sans-serif;
            direction: rtl;
            background-color: #ffffff;
            color: #0f172a;
            margin: 0;
            padding: 0;
            line-height: 1.45;
            font-size: 11pt;
        }

        .print-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            background: #ffffff;
        }

        /* -------------------------------------------------------------
           2. الترويسة الرسمية (3 أعمدة هندسية متوازنة مع الشعار)
        ------------------------------------------------------------- */
        .official-header {
            display: grid;
            grid-template-columns: 1.2fr 1.6fr 1.2fr;
            align-items: center;
            border-bottom: 2.5px solid ${headerBorderColor};
            padding-bottom: 12px;
            margin-bottom: 14px;
            position: relative;
        }

        .official-header::after {
            content: '';
            position: absolute;
            bottom: -2.5px;
            right: 0;
            width: 38%;
            height: 2.5px;
            background-color: ${accentColor};
        }

        .header-right {
            text-align: right;
            border-left: 1px dashed #cbd5e1;
            padding-left: 10px;
        }

        .header-center {
            text-align: center;
            padding: 0 10px;
        }

        .header-left {
            text-align: left;
            border-right: 1px dashed #cbd5e1;
            padding-right: 10px;
        }

        .header-right .republic-name {
            font-size: 15px;
            font-weight: 800;
            color: ${primaryColor};
            margin: 0;
            letter-spacing: 0.3px;
        }

        .header-right .ministry-title {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin: 2px 0;
        }

        .header-right .dept-title {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin: 0;
        }

        /* الشعار الوطني */
        .national-emblem-wrap {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
        }

        .national-emblem-wrap svg {
            width: 44px;
            height: 44px;
            fill: ${primaryColor};
        }

        .report-main-title {
            font-size: 19px;
            font-weight: 900;
            color: ${primaryColor};
            margin: 2px 0;
            letter-spacing: -0.2px;
        }

        .report-sub-code {
            display: inline-block;
            font-size: 10px;
            color: #64748b;
            margin: 0;
            font-weight: 600;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        /* بطاقة التوثيق المرجعي والباركود */
        .meta-verification-card {
            display: inline-flex;
            flex-direction: column;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            padding: 6px 10px;
            border-radius: 6px;
            text-align: right;
            font-size: 10.5px;
            min-width: 175px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            color: #334155;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-row strong {
            color: ${primaryColor};
            font-weight: 700;
        }

        .barcode-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 4px;
            padding-top: 3px;
            border-top: 1px dashed #cbd5e1;
        }

        /* -------------------------------------------------------------
           3. تخطيط الشبكة المتوازن للتقرير (Grid Layout)
        ------------------------------------------------------------- */
        .grid-profile-row {
            display: grid;
            grid-template-columns: 1fr 190px;
            gap: 12px;
            margin-bottom: 12px;
            align-items: stretch;
        }

        .grid-two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        /* -------------------------------------------------------------
           4. بطاقة الصورة وهوية المنتسب (Profile Card)
        ------------------------------------------------------------- */
        .profile-card {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .photo-frame {
            width: 110px;
            height: 125px;
            margin: 0 auto 8px;
            border: 2px solid ${primaryColor};
            border-radius: 6px;
            overflow: hidden;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-frame .no-photo-icon {
            color: #94a3b8;
            font-size: 42px;
        }

        .profile-name-badge {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 3px 0;
            line-height: 1.3;
        }

        .profile-rank-tag {
            display: inline-block;
            font-size: 11.5px;
            font-weight: 800;
            color: ${accentColor};
            background: #fef2f2;
            padding: 2px 10px;
            border-radius: 12px;
            border: 1px solid #fecaca;
        }

        .jihadi-name-pill {
            margin-top: 6px;
            background: #e2e8f0;
            color: #1e293b;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* -------------------------------------------------------------
           5. البطاقات وجداول البيانات ذات التنسيق الدقيق
        ------------------------------------------------------------- */
        .section-card {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .section-header {
            background: ${tableHeaderBg};
            padding: 7px 12px;
            border-bottom: 1.5px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-header-title {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            font-size: 12.5px;
            font-weight: 800;
            color: ${tableHeaderColor};
        }

        .section-header-title i {
            color: ${accentColor};
            font-size: 13px;
        }

        .section-header-badge {
            font-size: 9.5px;
            font-weight: 700;
            background: #ffffff;
            color: #475569;
            padding: 1px 6px;
            border-radius: 3px;
            border: 1px solid #cbd5e1;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .data-table th,
        .data-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .data-table tr:last-child th,
        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            width: 25%;
            border-left: 1px solid #e2e8f0;
            text-align: right;
            white-space: nowrap;
        }

        .data-table td {
            color: #0f172a;
            font-weight: 600;
            text-align: right;
        }

        .data-table td.highlight {
            color: ${accentColor};
            font-weight: 800;
        }

        .blood-badge {
            display: inline-block;
            background: #fee2e2;
            color: #b91c1c;
            padding: 1px 8px;
            border-radius: 4px;
            font-weight: 800;
            border: 1px solid #fca5a5;
            font-size: 11.5px;
        }

        /* -------------------------------------------------------------
           6. قسم الملاحظات الرسمية
        ------------------------------------------------------------- */
        .notes-box {
            background: #fcfcfd;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .notes-content {
            padding: 8px 12px;
            font-size: 11px;
            color: #334155;
            line-height: 1.5;
            margin: 0;
            background: #ffffff;
        }

        /* -------------------------------------------------------------
           7. قسم التوقيعات والاعتماد الرسمي (Official Signatures)
        ------------------------------------------------------------- */
        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 14px;
            margin-bottom: 12px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 10px;
            background: #ffffff;
            text-align: center;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sig-role {
            font-size: 11px;
            font-weight: 800;
            color: ${primaryColor};
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 25px;
        }

        .sig-line {
            font-size: 10px;
            color: #64748b;
        }

        /* -------------------------------------------------------------
           8. تذييل الصفحة والعلامة المائية
        ------------------------------------------------------------- */
        .report-footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1.5px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9.5px;
            color: #64748b;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .watermark-layer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-32deg);
            font-size: 68pt;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.035);
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
            user-select: none;
            letter-spacing: 4px;
        }

        /* -------------------------------------------------------------
           9. قواعد الطباعة المتقدمة (Print Specific Optimizations)
        ------------------------------------------------------------- */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            .section-card,
            .profile-card,
            .meta-verification-card,
            .signature-box,
            .notes-box {
                box-shadow: none !important;
            }
        }

        /* أزرار التحكم خارج الطباعة */
        .controls-wrapper {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 25px;
            margin-bottom: 20px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
    </style>
@endsection

@section('content')
    <div class="print-container">

        ${settings.showWatermark ? `{{-- العلامة المائية الرسمية --}}
        <div class="watermark-layer">
            ${settings.watermarkText || 'وثيقة رسمية معتمدة'}
        </div>` : ''}

        {{-- 1. الترويسة الرسمية (3 أعمدة هندسية متوازنة) --}}
        <header class="official-header">
            <div class="header-right">
                <h3 class="republic-name">{{ $settings['ministry'] ?? '${settings.customMinistry}' }}</h3>
                <h4 class="ministry-title">{{ $settings['authority'] ?? '${settings.customAuthority}' }}</h4>
                <p class="dept-title">{{ $settings['unit'] ?? '${settings.customUnit}' }}</p>
            </div>

            <div class="header-center">
                <div class="national-emblem-wrap">
                    {{-- شعار الجمهورية المتجهي عالي الدقة --}}
                    <svg viewBox="0 0 100 100" fill="currentColor">
                        <path d="M50 10 L62 38 L94 38 L68 56 L78 86 L50 68 L22 86 L32 56 L6 38 L38 38 Z" opacity="0.15" />
                        <path d="M50 15 C58 28 72 32 85 36 C75 48 65 60 50 82 C35 60 25 48 15 36 C28 32 42 28 50 15 Z" fill="none" stroke="currentColor" stroke-width="2.5" />
                        <circle cx="50" cy="42" r="14" fill="none" stroke="currentColor" stroke-width="2" />
                        <path d="M50 32 L54 42 L46 42 Z" fill="currentColor" />
                    </svg>
                </div>
                <h2 class="report-main-title">{{ $settings['report_title'] ?? '${settings.customReportTitle}' }}</h2>
                <span class="report-sub-code">وثيقة قيد وسجل بيانات شخصية • وثيقة رقمية معتمدة</span>
            </div>

            <div class="header-left">
                <div class="meta-verification-card">
                    <div class="meta-row">
                        <span>الرقم المرجعي:</span>
                        <strong>#{{ str_pad($personalData->id ?? 1, 6, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div class="meta-row">
                        <span>تاريخ الإصدار:</span>
                        <span>{{ now()->format('Y/m/d') }}</span>
                    </div>
                    <div class="meta-row">
                        <span>وقت التصدير:</span>
                        <span>{{ now()->format('H:i') }}</span>
                    </div>
                    ${settings.showBarcode ? `<div class="barcode-preview">
                        <svg style="width: 100%; height: 20px;" viewBox="0 0 140 22">
                            <rect x="2" y="1" width="3" height="18" fill="#1e293b"/>
                            <rect x="8" y="1" width="1.5" height="18" fill="#1e293b"/>
                            <rect x="13" y="1" width="4" height="18" fill="#1e293b"/>
                            <rect x="20" y="1" width="2" height="18" fill="#1e293b"/>
                            <rect x="25" y="1" width="5" height="18" fill="#1e293b"/>
                            <rect x="34" y="1" width="2" height="18" fill="#1e293b"/>
                            <rect x="40" y="1" width="3" height="18" fill="#1e293b"/>
                            <rect x="46" y="1" width="1" height="18" fill="#1e293b"/>
                            <rect x="50" y="1" width="4" height="18" fill="#1e293b"/>
                            <rect x="58" y="1" width="2.5" height="18" fill="#1e293b"/>
                            <rect x="64" y="1" width="1.5" height="18" fill="#1e293b"/>
                            <rect x="70" y="1" width="4" height="18" fill="#1e293b"/>
                            <rect x="78" y="1" width="2" height="18" fill="#1e293b"/>
                            <rect x="83" y="1" width="5" height="18" fill="#1e293b"/>
                            <rect x="92" y="1" width="1.5" height="18" fill="#1e293b"/>
                            <rect x="97" y="1" width="3" height="18" fill="#1e293b"/>
                            <rect x="103" y="1" width="4" height="18" fill="#1e293b"/>
                            <rect x="111" y="1" width="2" height="18" fill="#1e293b"/>
                            <rect x="116" y="1" width="5" height="18" fill="#1e293b"/>
                            <rect x="125" y="1" width="2" height="18" fill="#1e293b"/>
                            <rect x="130" y="1" width="4" height="18" fill="#1e293b"/>
                        </svg>
                        <span style="font-size: 8px; color: #64748b; letter-spacing: 2px;">{{ str_pad($personalData->military_number ?? $personalData->id, 10, '0', STR_PAD_LEFT) }}</span>
                    </div>` : ''}
                </div>
            </div>
        </header>

        {{-- 2. القسم الأول: البيانات التعريفية الأساسية وبطاقة الصورة الشخصية --}}
        <section class="grid-profile-row">
            {{-- جدول البيانات التعريفية الأساسية --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-id-card"></i>
                        <span>البيانات التعريفية الأساسية</span>
                    </div>
                    <span class="section-header-badge">السجل المدني والعسكري</span>
                </div>
                <table class="data-table">
                    <tbody>
                        <tr>
                            <th>الاسم الكامل واللقب</th>
                            <td colspan="3" style="font-size: 13px; font-weight: 800; color: #0f172a;">
                                {{ $personalData->name ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>الرقم الوطني / البطاقة</th>
                            <td style="font-family: monospace; font-size: 12px; font-weight: 700;">{{ $personalData->national_id_number ?? '-' }}</td>
                            <th>الرقم العسكري</th>
                            <td style="font-family: monospace; font-size: 12px; font-weight: 800; color: ${accentColor};">{{ $personalData->military_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الميلاد (ميلادي)</th>
                            <td>{{ !empty($personalData->date_of_birth_gregorian) ? (is_string($personalData->date_of_birth_gregorian) ? $personalData->date_of_birth_gregorian : $personalData->date_of_birth_gregorian->format('Y/m/d')) : '-' }}</td>
                            <th>تاريخ الميلاد (هجري)</th>
                            <td>{{ $personalData->date_of_birth_hijri ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الجنسية</th>
                            <td>{{ $personalData->placeOfBirthCountry->name ?? 'يمني' }}</td>
                            <th>الديانة</th>
                            <td>مسلم</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- بطاقة الهوية والصورة --}}
            <div class="profile-card">
                <div class="photo-frame">
                    @if(!empty($personalData->main_profile_photo))
                        {{-- متوافق مع العرض المباشر والطباعة --}}
                        <img src="{{ Str::startsWith($personalData->main_profile_photo, ['http://', 'https://']) ? $personalData->main_profile_photo : asset('storage/' . $personalData->main_profile_photo) }}" alt="الصورة الشخصية" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="no-photo-icon" style="display: none;"><i class="fas fa-user-shield"></i></div>
                    @else
                        <div class="no-photo-icon"><i class="fas fa-user-tie"></i></div>
                    @endif
                </div>
                <h3 class="profile-name-badge">{{ $personalData->name ?? 'غير محدد' }}</h3>
                <div class="profile-rank-tag">{{ $personalData->rank->rank ?? 'بدون رتبة مسجلة' }}</div>
                @if(!empty($personalData->jihadi_name))
                    <div class="jihadi-name-pill">
                        <i class="fas fa-tag"></i>
                        <span>{{ $personalData->jihadi_name }}</span>
                    </div>
                @endif
            </div>
        </section>

        {{-- 3. القسم الثاني: البيانات الوظيفية والبيانات الطبية/الاجتماعية --}}
        <section class="grid-two-columns">
            {{-- البيانات الوظيفية والعسكرية --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-award"></i>
                        <span>البيانات الوظيفية والعسكرية</span>
                    </div>
                    <span class="section-header-badge">الوضع الإداري</span>
                </div>
                <table class="data-table">
                    <tbody>
                        <tr>
                            <th>الرتبة العسكرية</th>
                            <td class="highlight">{{ $personalData->rank->rank ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الدائرة / المنطقة</th>
                            <td>{{ $personalData->militaryDistrict->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الشعبة / الوحدة</th>
                            <td>{{ $personalData->division->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>حالة الوظيفة</th>
                            <td>{{ $personalData->jobStatus->job_status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ التعيين</th>
                            <td>{{ !empty($personalData->appointment_date) ? (is_string($personalData->appointment_date) ? $personalData->appointment_date : $personalData->appointment_date->format('Y/m/d')) : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- البيانات الطبية والاجتماعية --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-notes-medical"></i>
                        <span>البيانات الطبية والاجتماعية</span>
                    </div>
                    <span class="section-header-badge">المؤهلات والحالة</span>
                </div>
                <table class="data-table">
                    <tbody>
                        <tr>
                            <th>فصيلة الدم</th>
                            <td>
                                <span class="blood-badge">{{ $personalData->bloodType->type ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>الحالة الاجتماعية</th>
                            <td>{{ $personalData->maritalStatus->status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>عدد الأبناء</th>
                            <td>{{ $personalData->number_of_children ?? '0' }}</td>
                        </tr>
                        <tr>
                            <th>المؤهل العلمي</th>
                            <td>{{ $personalData->qualification->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>التخصص الدقيق</th>
                            <td>{{ $personalData->specialization ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 4. القسم الثالث: بيانات السكن الحالي ومكان الميلاد --}}
        <section class="grid-two-columns">
            {{-- بيانات السكن الحالي --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>بيانات السكن والإقامة الحالية</span>
                    </div>
                </div>
                <table class="data-table">
                    <tbody>
                        <tr>
                            <th>المحافظة</th>
                            <td>{{ $personalData->governorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>المديرية</th>
                            <td>{{ $personalData->directorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>العزلة / المنطقة</th>
                            <td>{{ $personalData->subArea->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>القرية / الشارع</th>
                            <td>{{ $personalData->village->name ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- بيانات الميلاد --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-compass"></i>
                        <span>مكان الميلاد والنشأة</span>
                    </div>
                </div>
                <table class="data-table">
                    <tbody>
                        <tr>
                            <th>الدولة</th>
                            <td>{{ $personalData->placeOfBirthCountry->name ?? 'اليمن' }}</td>
                        </tr>
                        <tr>
                            <th>المحافظة</th>
                            <td>{{ $personalData->placeOfBirthGovernorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>المديرية</th>
                            <td>{{ $personalData->placeOfBirthDirectorate->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>القرية / المحلة</th>
                            <td>{{ $personalData->placeOfBirthVillage->name ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 5. ملاحظات إضافية --}}
        @if(!empty($personalData->notes))
            <section class="notes-box">
                <div class="section-header">
                    <div class="section-header-title">
                        <i class="fas fa-comment-alt"></i>
                        <span>ملاحظات إدارية وتدقيق القيد</span>
                    </div>
                </div>
                <p class="notes-content">{{ $personalData->notes }}</p>
            </section>
        @endif

        ${settings.showSignatures ? `{{-- 6. مربع الاعتمادات والتوقيعات الرسمية والختم --}}
        <section class="signatures-grid">
            <div class="signature-box">
                <div class="sig-role">إعداد وتجهيز المختص</div>
                <div class="sig-line">الاسم: .......................................</div>
                <div class="sig-line">التوقيع: .....................................</div>
            </div>

            <div class="signature-box">
                <div class="sig-role">تدقيق ومراجعة شؤون القوى البشرية</div>
                <div class="sig-line">الاسم: .......................................</div>
                <div class="sig-line">التوقيع: .....................................</div>
            </div>

            <div class="signature-box" style="position: relative;">
                <div class="sig-role">اعتماد مدير الدائرة / الختم الرسمي</div>
                ${settings.showOfficialStamp ? `<div style="position: absolute; bottom: 4px; left: 10px; width: 62px; height: 62px; border: 2px dashed ${accentColor}; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; transform: rotate(-8deg); opacity: 0.85;">
                    <span style="font-size: 7px; color: ${accentColor}; font-weight: 800;">الختم المعتمد</span>
                    <i class="fas fa-certificate" style="font-size: 14px; color: ${accentColor}; margin: 2px 0;"></i>
                    <span style="font-size: 6px; color: ${accentColor};">وحدة المعلومات</span>
                </div>` : ''}
                <div class="sig-line">التوقيع والختم: .......................</div>
            </div>
        </section>` : ''}

        {{-- 7. تذييل التقرير والأمان الرقمي --}}
        <footer class="report-footer">
            <div>
                <span>طُبع بواسطة المشغل: </span>
                <strong>{{ auth()->user()->name ?? 'النظام الآلي الموحد' }}</strong>
                <span style="margin: 0 6px;">|</span>
                <span>تاريخ الطباعة: {{ now()->format('Y/m/d - H:i:s') }}</span>
            </div>
            <div>
                <span>نظام إدارة السجلات والقوى البشرية الموحد</span>
                <span style="margin: 0 4px;">•</span>
                <span style="font-weight: 700;">صفحة 1 من 1</span>
            </div>
        </footer>
    </div>

    {{-- أزرار التحكم في الشاشة (مخفية تلقائياً عند الطباعة والتصدير) --}}
    <div class="no-print controls-wrapper">
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-700 text-white rounded-lg font-bold hover:bg-blue-800 transition-colors shadow flex items-center gap-2 text-sm cursor-pointer">
            <i class="fas fa-print"></i>
            <span>طباعة الاستمارة الرسمية (Ctrl + P)</span>
        </button>
        <button onclick="window.history.back()" class="px-5 py-2.5 bg-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-300 transition-colors flex items-center gap-2 text-sm cursor-pointer">
            <i class="fas fa-arrow-right"></i>
            <span>العودة للنظام</span>
        </button>
    </div>
@endsection
`;
}
