import React from 'react';
import { PersonalData, PrintSettings } from '../types';

interface DocumentPreviewProps {
  data: PersonalData;
  settings: PrintSettings;
  scale?: number;
}

export const DocumentPreview: React.FC<DocumentPreviewProps> = ({
  data,
  settings,
  scale = 1,
}) => {
  const isGold = settings.theme === 'republic_gold';
  const isMonochrome = settings.theme === 'monochrome_laser';

  // Palette variables
  const primaryColor = isMonochrome ? '#0f172a' : (isGold ? '#1e3a5f' : '#0f2942');
  const accentColor = isMonochrome ? '#334155' : (isGold ? '#b45309' : '#dc2626');
  const borderColor = isMonochrome ? '#94a3b8' : (isGold ? '#cbd5e1' : '#cbd5e1');
  const tableHeaderBg = isMonochrome ? '#f1f5f9' : (isGold ? '#fef3c7' : '#f0f4f8');
  const tableHeaderColor = isMonochrome ? '#0f172a' : (isGold ? '#78350f' : '#0f2942');

  const paddedId = String(data.id || 1).padStart(6, '0');
  const barcodeValue = String(data.military_number || data.id || '104820').padStart(10, '0');

  return (
    <div
      id="printable-document-container"
      className="printable-area mx-auto bg-white text-slate-900 transition-all shadow-2xl relative"
      style={{
        width: '210mm',
        minHeight: '297mm',
        padding: settings.compactA4 ? '10mm 12mm' : '14mm 15mm',
        boxSizing: 'border-box',
        transform: scale !== 1 ? `scale(${scale})` : undefined,
        transformOrigin: 'top center',
        fontFamily: "'Cairo', 'Tajawal', sans-serif",
        direction: 'rtl',
      }}
    >
      {/* Watermark Layer */}
      {settings.showWatermark && (
        <div
          className="watermark-element pointer-events-none select-none absolute inset-0 flex items-center justify-center overflow-hidden z-0"
          style={{ opacity: 0.045 }}
        >
          <span
            className="font-black text-slate-900 whitespace-nowrap text-center leading-none"
            style={{
              fontSize: '62pt',
              transform: 'rotate(-32deg)',
              letterSpacing: '4px',
            }}
          >
            {settings.watermarkText || 'وثيقة رسمية معتمدة'}
          </span>
        </div>
      )}

      {/* Main Document Content */}
      <div className="relative z-10 flex flex-col justify-between" style={{ minHeight: '273mm' }}>
        <div>
          {/* 1. Official Header */}
          <header
            className="grid items-center pb-2.5 mb-3 relative"
            style={{
              gridTemplateColumns: '1.2fr 1.6fr 1.2fr',
              borderBottom: `2.5px solid ${primaryColor}`,
            }}
          >
            {/* Red / Accent line accent */}
            <div
              className="absolute -bottom-[2.5px] right-0 h-[2.5px]"
              style={{ width: '38%', backgroundColor: accentColor }}
            />

            {/* Right: State / Ministry titles */}
            <div className="text-right pl-2 border-l border-dashed border-slate-300">
              <h1 className="text-[14px] font-extrabold m-0 leading-tight" style={{ color: primaryColor }}>
                {settings.customMinistry}
              </h1>
              <h2 className="text-[12.5px] font-bold text-slate-700 m-0 my-0.5">
                {settings.customAuthority}
              </h2>
              <p className="text-[10.5px] font-semibold text-slate-500 m-0">
                {settings.customUnit}
              </p>
            </div>

            {/* Center: Emblem & Report Title */}
            <div className="text-center px-2">
              <div className="flex flex-col items-center justify-center mb-0.5">
                {/* Yemeni National Eagle Vector */}
                <svg
                  viewBox="0 0 100 100"
                  className="w-10 h-10 transition-colors"
                  style={{ fill: primaryColor }}
                >
                  <path
                    d="M50 12 C52 14 55 18 56 22 C64 24 74 27 86 33 C78 44 68 53 50 82 C32 53 22 44 14 33 C26 27 36 24 44 22 C45 18 48 14 50 12 Z"
                    fill={primaryColor}
                    opacity="0.12"
                  />
                  <path
                    d="M50 16 L53 24 L61 24 L55 29 L57 37 L50 32 L43 37 L45 29 L39 24 L47 24 Z"
                    fill={isGold ? '#b45309' : primaryColor}
                  />
                  <path
                    d="M20 34 C30 31 40 28 50 28 C60 28 70 31 80 34 C72 45 62 55 50 78 C38 55 28 45 20 34 Z"
                    fill="none"
                    stroke={primaryColor}
                    strokeWidth="2.2"
                  />
                  <circle cx="50" cy="46" r="10" fill="none" stroke={accentColor} strokeWidth="1.8" />
                  <path d="M46 44 L50 49 L54 44" fill="none" stroke={primaryColor} strokeWidth="1.8" />
                </svg>
              </div>
              <h2 className="text-[17px] font-black tracking-tight m-0 leading-tight" style={{ color: primaryColor }}>
                {settings.customReportTitle}
              </h2>
              <div className="inline-block mt-0.5">
                <span className="text-[9.5px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                  وثيقة قيد وسجل بيانات شخصية • نظام القوى البشرية
                </span>
              </div>
            </div>

            {/* Left: Metadata & Verification Card */}
            <div className="text-left pr-2 border-r border-dashed border-slate-300 flex justify-end">
              <div
                className="bg-slate-50 rounded p-1.5 text-right text-[10px] w-full max-w-[170px]"
                style={{ border: `1.5px solid ${borderColor}` }}
              >
                <div className="flex justify-between items-center mb-0.5 text-slate-600">
                  <span>الرقم المرجعي:</span>
                  <strong className="font-mono text-slate-900 font-bold">#{paddedId}</strong>
                </div>
                <div className="flex justify-between items-center mb-0.5 text-slate-600">
                  <span>تاريخ الإصدار:</span>
                  <span className="font-mono">{new Date().toISOString().split('T')[0].replace(/-/g, '/')}</span>
                </div>
                <div className="flex justify-between items-center mb-0.5 text-slate-600">
                  <span>وقت التصدير:</span>
                  <span className="font-mono">11:45</span>
                </div>

                {settings.showBarcode && (
                  <div className="mt-1 pt-1 border-t border-dashed border-slate-300 flex flex-col items-center">
                    <svg className="w-full h-4" viewBox="0 0 140 18" preserveAspectRatio="none">
                      <rect x="2" y="0" width="3" height="18" fill="#1e293b" />
                      <rect x="8" y="0" width="1.5" height="18" fill="#1e293b" />
                      <rect x="13" y="0" width="4" height="18" fill="#1e293b" />
                      <rect x="20" y="0" width="2" height="18" fill="#1e293b" />
                      <rect x="25" y="0" width="5" height="18" fill="#1e293b" />
                      <rect x="34" y="0" width="2" height="18" fill="#1e293b" />
                      <rect x="40" y="0" width="3" height="18" fill="#1e293b" />
                      <rect x="46" y="0" width="1" height="18" fill="#1e293b" />
                      <rect x="50" y="0" width="4" height="18" fill="#1e293b" />
                      <rect x="58" y="0" width="2.5" height="18" fill="#1e293b" />
                      <rect x="64" y="0" width="1.5" height="18" fill="#1e293b" />
                      <rect x="70" y="0" width="4" height="18" fill="#1e293b" />
                      <rect x="78" y="0" width="2" height="18" fill="#1e293b" />
                      <rect x="83" y="0" width="5" height="18" fill="#1e293b" />
                      <rect x="92" y="0" width="1.5" height="18" fill="#1e293b" />
                      <rect x="97" y="0" width="3" height="18" fill="#1e293b" />
                      <rect x="103" y="0" width="4" height="18" fill="#1e293b" />
                      <rect x="111" y="0" width="2" height="18" fill="#1e293b" />
                      <rect x="116" y="0" width="5" height="18" fill="#1e293b" />
                      <rect x="125" y="0" width="2" height="18" fill="#1e293b" />
                      <rect x="130" y="0" width="4" height="18" fill="#1e293b" />
                    </svg>
                    <span className="font-mono text-[8px] text-slate-500 tracking-wider">
                      {barcodeValue}
                    </span>
                  </div>
                )}
              </div>
            </div>
          </header>

          {/* 2. Top Grid: Identity Info + Photo Card */}
          <div
            className="grid gap-2.5 mb-2.5 items-stretch"
            style={{ gridTemplateColumns: '1fr 175px' }}
          >
            {/* Basic Identity Details */}
            <div
              className="bg-white rounded-lg overflow-hidden flex flex-col justify-between"
              style={{ border: `1.5px solid ${borderColor}` }}
            >
              <div
                className="px-3 py-1.5 flex items-center justify-between"
                style={{
                  backgroundColor: tableHeaderBg,
                  borderBottom: `1.5px solid ${borderColor}`,
                }}
              >
                <div className="flex items-center gap-1.5 text-[12px] font-extrabold" style={{ color: tableHeaderColor }}>
                  <i className="fas fa-id-card text-[12px]" style={{ color: accentColor }} />
                  <span>البيانات التعريفية الأساسية</span>
                </div>
                <span className="text-[9.5px] font-bold text-slate-600 bg-white px-1.5 py-0.5 rounded border border-slate-300">
                  السجل المدني والعسكري
                </span>
              </div>

              <table className="w-full text-[11px] border-collapse">
                <tbody>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200 w-[22%]">
                      الاسم الرباعي واللقب
                    </th>
                    <td colSpan={3} className="p-1.5 px-2 text-slate-900 font-bold text-[12.5px]">
                      {data.name || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200 w-[22%]">
                      الرقم الوطني / الهوية
                    </th>
                    <td className="p-1.5 px-2 font-mono font-bold text-slate-800 w-[28%]">
                      {data.national_id_number || '-'}
                    </td>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200 w-[22%]">
                      الرقم العسكري
                    </th>
                    <td className="p-1.5 px-2 font-mono font-bold text-[12px] w-[28%]" style={{ color: accentColor }}>
                      {data.military_number || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      تاريخ الميلاد (ميلادي)
                    </th>
                    <td className="p-1.5 px-2 font-mono text-slate-800">
                      {data.date_of_birth_gregorian || '-'}
                    </td>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      تاريخ الميلاد (هجري)
                    </th>
                    <td className="p-1.5 px-2 text-slate-800">
                      {data.date_of_birth_hijri || '-'}
                    </td>
                  </tr>
                  <tr>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      الجنسية
                    </th>
                    <td className="p-1.5 px-2 text-slate-800">
                      {data.placeOfBirthCountry?.name || 'يمني'}
                    </td>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      الديانة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800">
                      مسلم
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            {/* Profile Photo Card */}
            <div
              className="bg-slate-50 rounded-lg p-2.5 text-center flex flex-col items-center justify-center"
              style={{ border: `1.5px solid ${borderColor}` }}
            >
              <div
                className="w-24 h-28 mb-1.5 rounded overflow-hidden bg-slate-200 flex items-center justify-center"
                style={{
                  border: `2px solid ${primaryColor}`,
                  boxShadow: '0 1px 2px rgba(0,0,0,0.08)',
                }}
              >
                {data.main_profile_photo ? (
                  <img
                    src={data.main_profile_photo}
                    alt={data.name}
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <i className="fas fa-user-shield text-slate-400 text-4xl" />
                )}
              </div>

              <h3 className="text-[13px] font-extrabold text-slate-900 m-0 leading-tight">
                {data.name || 'بدون اسم'}
              </h3>

              <div
                className="mt-1 inline-block text-[11px] font-bold px-2 py-0.5 rounded-full"
                style={{
                  color: accentColor,
                  backgroundColor: '#fee2e2',
                  border: '1px solid #fecaca',
                }}
              >
                {data.rank?.rank || 'بدون رتبة'}
              </div>

              {data.jihadi_name && (
                <div className="mt-1 bg-slate-200/90 text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1">
                  <i className="fas fa-tag text-[9px]" />
                  <span>{data.jihadi_name}</span>
                </div>
              )}
            </div>
          </div>

          {/* 3. Second Row: Job/Military Info + Medical/Social Info */}
          <div className="grid grid-cols-2 gap-2.5 mb-2.5">
            {/* Military & Job Info */}
            <div className="bg-white rounded-lg overflow-hidden" style={{ border: `1.5px solid ${borderColor}` }}>
              <div
                className="px-3 py-1.5 flex items-center justify-between"
                style={{
                  backgroundColor: tableHeaderBg,
                  borderBottom: `1.5px solid ${borderColor}`,
                }}
              >
                <div className="flex items-center gap-1.5 text-[12px] font-extrabold" style={{ color: tableHeaderColor }}>
                  <i className="fas fa-award text-[12px]" style={{ color: accentColor }} />
                  <span>البيانات الوظيفية والعسكرية</span>
                </div>
                <span className="text-[9px] font-bold text-slate-600 bg-white px-1.5 py-0.2 rounded border border-slate-300">
                  الوضع الإداري
                </span>
              </div>
              <table className="w-full text-[10.5px] border-collapse">
                <tbody>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200 w-[38%]">
                      الرتبة العسكرية
                    </th>
                    <td className="p-1.5 px-2 font-extrabold text-[11px]" style={{ color: accentColor }}>
                      {data.rank?.rank || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      الدائرة / المنطقة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.militaryDistrict?.name || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      الشعبة / الوحدة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.division?.name || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      حالة الوظيفة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.jobStatus?.job_status || '-'}
                    </td>
                  </tr>
                  <tr>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      تاريخ التعيين
                    </th>
                    <td className="p-1.5 px-2 font-mono text-slate-800">
                      {data.appointment_date || '-'}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            {/* Medical & Social Info */}
            <div className="bg-white rounded-lg overflow-hidden" style={{ border: `1.5px solid ${borderColor}` }}>
              <div
                className="px-3 py-1.5 flex items-center justify-between"
                style={{
                  backgroundColor: tableHeaderBg,
                  borderBottom: `1.5px solid ${borderColor}`,
                }}
              >
                <div className="flex items-center gap-1.5 text-[12px] font-extrabold" style={{ color: tableHeaderColor }}>
                  <i className="fas fa-heartbeat text-[12px]" style={{ color: accentColor }} />
                  <span>البيانات الطبية والاجتماعية</span>
                </div>
                <span className="text-[9px] font-bold text-slate-600 bg-white px-1.5 py-0.2 rounded border border-slate-300">
                  الحالة والمؤهل
                </span>
              </div>
              <table className="w-full text-[10.5px] border-collapse">
                <tbody>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200 w-[38%]">
                      فصيلة الدم
                    </th>
                    <td className="p-1.5 px-2">
                      <span className="inline-block bg-red-100 text-red-700 font-mono font-extrabold text-[12px] px-2 py-0.2 rounded border border-red-300">
                        {data.bloodType?.type || '-'}
                      </span>
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      الحالة الاجتماعية
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.maritalStatus?.status || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      عدد الأبناء
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-bold font-mono">
                      {data.number_of_children !== undefined ? data.number_of_children : '0'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      المؤهل العلمي
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.qualification?.name || '-'}
                    </td>
                  </tr>
                  <tr>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      التخصص
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.specialization || '-'}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          {/* 4. Third Row: Address & Place of Birth */}
          <div className="grid grid-cols-2 gap-2.5 mb-2.5">
            {/* Current Residence */}
            <div className="bg-white rounded-lg overflow-hidden" style={{ border: `1.5px solid ${borderColor}` }}>
              <div
                className="px-3 py-1.5 flex items-center justify-between"
                style={{
                  backgroundColor: tableHeaderBg,
                  borderBottom: `1.5px solid ${borderColor}`,
                }}
              >
                <div className="flex items-center gap-1.5 text-[12px] font-extrabold" style={{ color: tableHeaderColor }}>
                  <i className="fas fa-home text-[12px]" style={{ color: accentColor }} />
                  <span>بيانات السكن والإقامة الحالية</span>
                </div>
              </div>
              <table className="w-full text-[10.5px] border-collapse">
                <tbody>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200 w-[38%]">
                      المحافظة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.governorate?.name || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      المديرية
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.directorate?.name || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      العزلة / المنطقة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.subArea?.name || '-'}
                    </td>
                  </tr>
                  <tr>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      القرية / الشارع
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.village?.name || '-'}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            {/* Place of Birth */}
            <div className="bg-white rounded-lg overflow-hidden" style={{ border: `1.5px solid ${borderColor}` }}>
              <div
                className="px-3 py-1.5 flex items-center justify-between"
                style={{
                  backgroundColor: tableHeaderBg,
                  borderBottom: `1.5px solid ${borderColor}`,
                }}
              >
                <div className="flex items-center gap-1.5 text-[12px] font-extrabold" style={{ color: tableHeaderColor }}>
                  <i className="fas fa-map-marker-alt text-[12px]" style={{ color: accentColor }} />
                  <span>مكان الميلاد والنشأة</span>
                </div>
              </div>
              <table className="w-full text-[10.5px] border-collapse">
                <tbody>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200 w-[38%]">
                      الدولة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.placeOfBirthCountry?.name || 'اليمن'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      المحافظة
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.placeOfBirthGovernorate?.name || '-'}
                    </td>
                  </tr>
                  <tr className="border-b border-slate-200">
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      المديرية
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.placeOfBirthDirectorate?.name || '-'}
                    </td>
                  </tr>
                  <tr>
                    <th className="bg-slate-50 text-slate-600 font-bold p-1.5 px-2 text-right border-l border-slate-200">
                      القرية / الحي
                    </th>
                    <td className="p-1.5 px-2 text-slate-800 font-semibold">
                      {data.placeOfBirthVillage?.name || '-'}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          {/* 5. Additional Notes */}
          {data.notes && (
            <div className="bg-white rounded-lg overflow-hidden mb-2.5" style={{ border: `1.5px solid ${borderColor}` }}>
              <div
                className="px-3 py-1 flex items-center justify-between"
                style={{
                  backgroundColor: tableHeaderBg,
                  borderBottom: `1px solid ${borderColor}`,
                }}
              >
                <div className="flex items-center gap-1.5 text-[11px] font-extrabold" style={{ color: tableHeaderColor }}>
                  <i className="fas fa-sticky-note text-[11px]" style={{ color: accentColor }} />
                  <span>ملاحظات إدارية وتدقيق القيد</span>
                </div>
              </div>
              <div className="p-2 text-[10.5px] text-slate-700 leading-relaxed bg-white">
                {data.notes}
              </div>
            </div>
          )}

          {/* 6. Signatures & Official Approvals */}
          {settings.showSignatures && (
            <div className="grid grid-cols-3 gap-2.5 my-2">
              <div
                className="border border-slate-300 rounded p-2 text-center bg-white min-h-[75px] flex flex-col justify-between"
              >
                <div
                  className="text-[10.5px] font-extrabold pb-1 border-b border-dashed border-slate-300"
                  style={{ color: primaryColor }}
                >
                  إعداد وتجهيز المختص
                </div>
                <div className="text-[9.5px] text-slate-500">الاسم: .............................</div>
                <div className="text-[9.5px] text-slate-500">التوقيع: ...........................</div>
              </div>

              <div
                className="border border-slate-300 rounded p-2 text-center bg-white min-h-[75px] flex flex-col justify-between"
              >
                <div
                  className="text-[10.5px] font-extrabold pb-1 border-b border-dashed border-slate-300"
                  style={{ color: primaryColor }}
                >
                  تدقيق شؤون القوى البشرية
                </div>
                <div className="text-[9.5px] text-slate-500">الاسم: .............................</div>
                <div className="text-[9.5px] text-slate-500">التوقيع: ...........................</div>
              </div>

              <div
                className="border border-slate-300 rounded p-2 text-center bg-white min-h-[75px] flex flex-col justify-between relative"
              >
                <div
                  className="text-[10.5px] font-extrabold pb-1 border-b border-dashed border-slate-300"
                  style={{ color: primaryColor }}
                >
                  اعتماد مدير الدائرة / الختم
                </div>

                {/* Simulated Official Stamp */}
                {settings.showOfficialStamp && (
                  <div
                    className="absolute left-2 bottom-1 w-14 h-14 rounded-full border-2 border-dashed flex flex-col items-center justify-center pointer-events-none"
                    style={{
                      borderColor: accentColor,
                      color: accentColor,
                      transform: 'rotate(-10deg)',
                      opacity: 0.85,
                    }}
                  >
                    <span className="text-[6.5px] font-black">الختم المعتمد</span>
                    <i className="fas fa-certificate text-[11px] my-0.5" />
                    <span className="text-[5.5px] font-bold">وحدة المعلومات</span>
                  </div>
                )}

                <div className="text-[9.5px] text-slate-500">التوقيع والختم: .................</div>
              </div>
            </div>
          )}
        </div>

        {/* 7. Footer: Audit & Security Trail */}
        <footer
          className="pt-2 mt-2 flex items-center justify-between text-[9px] text-slate-500 border-t"
          style={{ borderColor: borderColor }}
        >
          <div className="flex items-center gap-2">
            <span>طُبع بواسطة المشغل: <strong className="text-slate-800">النظام الآلي الموحد</strong></span>
            <span>|</span>
            <span>تاريخ الطباعة: {new Date().toISOString().replace('T', ' ').slice(0, 19)}</span>
          </div>

          <div className="flex items-center gap-2">
            <span>نظام إدارة السجلات والقوى البشرية</span>
            <span>•</span>
            <span className="font-bold text-slate-700">صفحة 1 من 1</span>
            {settings.showQRCode && (
              <div className="inline-flex items-center gap-1 bg-slate-100 p-0.5 px-1 rounded border border-slate-200">
                <i className="fas fa-qrcode text-[10px] text-slate-700" />
                <span className="text-[7.5px] font-mono">VRF-{paddedId}</span>
              </div>
            )}
          </div>
        </footer>
      </div>
    </div>
  );
};
