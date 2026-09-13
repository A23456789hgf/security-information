import React, { useState } from 'react';
import { Copy, Check, Download, FileCode, CheckCircle2, AlertCircle, BookOpen } from 'lucide-react';

interface BladeCodeViewerProps {
  code: string;
  onCopy: () => void;
  onDownload: () => void;
  copied: boolean;
}

export const BladeCodeViewer: React.FC<BladeCodeViewerProps> = ({
  code,
  onCopy,
  onDownload,
  copied,
}) => {
  const [activeTab, setActiveTab] = useState<'code' | 'guide'>('code');

  return (
    <div className="max-w-6xl mx-auto px-4 py-6">
      {/* Code Viewer Header */}
      <div className="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl mb-6">
        <div className="p-4 bg-slate-950 border-b border-slate-800 flex flex-wrap items-center justify-between gap-4">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-lg bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center">
              <FileCode className="w-5 h-5" />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <span className="font-mono text-sm font-bold text-white">personal-data-print.blade.php</span>
                <span className="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[11px] px-2 py-0.5 rounded-full font-medium">
                  نسخة نهائية منقحة ومختبرة
                </span>
              </div>
              <p className="text-xs text-slate-400 mt-0.5">
                كود قالب Blade متكامل مع كافة تنسيقات CSS وموجهات Laravel Blade الرسمية
              </p>
            </div>
          </div>

          <div className="flex items-center gap-2">
            <div className="flex items-center bg-slate-900 border border-slate-700 rounded-lg p-1 text-xs">
              <button
                onClick={() => setActiveTab('code')}
                className={`px-3 py-1 rounded-md cursor-pointer font-bold transition-all ${
                  activeTab === 'code' ? 'bg-blue-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'
                }`}
              >
                عرض الشفرة البرمجية
              </button>
              <button
                onClick={() => setActiveTab('guide')}
                className={`px-3 py-1 rounded-md cursor-pointer font-bold transition-all ${
                  activeTab === 'guide' ? 'bg-blue-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'
                }`}
              >
                طريقة التثبيت في Laravel
              </button>
            </div>

            <button
              onClick={onCopy}
              className="flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm"
            >
              {copied ? <Check className="w-4 h-4 text-white" /> : <Copy className="w-4 h-4" />}
              <span>{copied ? 'تم النسخ!' : 'نسخ الكود بالكامل'}</span>
            </button>

            <button
              onClick={onDownload}
              className="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 hover:text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm"
            >
              <Download className="w-4 h-4 text-blue-400" />
              <span>تحميل الملف</span>
            </button>
          </div>
        </div>

        {activeTab === 'code' ? (
          <div className="relative">
            <pre
              className="p-5 font-mono text-xs leading-relaxed text-slate-200 bg-slate-950 overflow-x-auto max-h-[700px] select-all"
              style={{ direction: 'ltr', textAlign: 'left' }}
            >
              <code>{code}</code>
            </pre>
          </div>
        ) : (
          <div className="p-6 bg-slate-900 text-slate-200 space-y-6">
            {/* Guide Step 1 */}
            <div className="bg-slate-950 border border-slate-800 rounded-xl p-4">
              <div className="flex items-center gap-2 text-blue-400 font-bold text-sm mb-2">
                <CheckCircle2 className="w-4 h-4" />
                <span>1. حفظ ملف القالب في مشروعك (Laravel Blade View)</span>
              </div>
              <p className="text-xs text-slate-400 mb-3">
                انسخ محتوى الملف وضعه في المسار التالي داخل مجلد مشروع Laravel:
              </p>
              <div className="bg-slate-900 p-2.5 rounded-lg font-mono text-xs text-amber-300 border border-slate-800" style={{ direction: 'ltr' }}>
                resources/views/reports/personal-data-print.blade.php
              </div>
            </div>

            {/* Guide Step 2 */}
            <div className="bg-slate-950 border border-slate-800 rounded-xl p-4">
              <div className="flex items-center gap-2 text-blue-400 font-bold text-sm mb-2">
                <CheckCircle2 className="w-4 h-4" />
                <span>2. استدعاء القالب من المتحكم (Controller Method)</span>
              </div>
              <p className="text-xs text-slate-400 mb-3">
                في الـ Controller الخاص بك (مثل <code>PersonalDataController.php</code>)، مرر البيانات إلى العرض:
              </p>
              <pre className="bg-slate-900 p-3 rounded-lg font-mono text-xs text-emerald-300 border border-slate-800 overflow-x-auto" style={{ direction: 'ltr' }}>
{`public function print($id)
{
    $personalData = PersonalData::with([
        'rank',
        'militaryDistrict',
        'division',
        'jobStatus',
        'bloodType',
        'maritalStatus',
        'qualification',
        'governorate',
        'directorate',
        'subArea',
        'village',
        'placeOfBirthCountry',
        'placeOfBirthGovernorate',
        'placeOfBirthDirectorate',
        'placeOfBirthVillage'
    ])->findOrFail($id);

    return view('reports.personal-data-print', compact('personalData'));
}`}
              </pre>
            </div>

            {/* Guide Step 3 */}
            <div className="bg-slate-950 border border-slate-800 rounded-xl p-4">
              <div className="flex items-center gap-2 text-amber-400 font-bold text-sm mb-2">
                <AlertCircle className="w-4 h-4" />
                <span>3. ملاحظات هامة لتصدير PDF ومكتبات التحويل (DomPDF / Browsershot)</span>
              </div>
              <ul className="text-xs text-slate-300 space-y-2 list-disc list-inside">
                <li>
                  <strong className="text-white">الطباعة عبر المتصفح (موصى بها بشدة):</strong> اضغط على زر طباعة أو استخدم <code className="text-blue-300">Ctrl + P</code>، واختر الوجهة: <strong className="text-white">Save as PDF</strong>، مع تفعيل خيار <strong className="text-white">Background graphics</strong> لتحصل على أعلى دقة ممكنة بالخطوط الأصلية.
                </li>
                <li>
                  <strong className="text-white">في حال استخدام Laravel-DomPDF:</strong> يجب التأكد من تمرير مسار الصور بصيغة <code className="text-blue-300">public_path()</code> أو <code className="text-blue-300">data:image/...;base64</code>، وضبط خيار <code className="text-blue-300">'isRemoteEnabled' =&gt; true</code> في ملف <code className="text-slate-400">config/dompdf.php</code>.
                </li>
                <li>
                  <strong className="text-white">تأمين الخط العربي:</strong> تم تضمين خط <strong>Cairo</strong> و <strong>Tajawal</strong> مع خطوط احتياطية قياسية لمنع ظهور النصوص العربية المتقطعة أو المشوهة.
                </li>
              </ul>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
