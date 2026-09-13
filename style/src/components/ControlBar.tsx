import React from 'react';
import { PrintSettings, PrintTheme } from '../types';
import {
  Printer,
  FileCode2,
  Sliders,
  Sparkles,
  Download,
  Copy,
  Check,
  Eye,
  RotateCcw,
  ZoomIn,
  ZoomOut,
  Stamp,
  Barcode,
  QrCode,
  ShieldAlert,
} from 'lucide-react';

interface ControlBarProps {
  activeTab: 'preview' | 'code' | 'editor' | 'comparison';
  setActiveTab: (tab: 'preview' | 'code' | 'editor' | 'comparison') => void;
  settings: PrintSettings;
  setSettings: React.Dispatch<React.SetStateAction<PrintSettings>>;
  scale: number;
  setScale: (scale: number) => void;
  onPrint: () => void;
  onCopyBlade: () => void;
  onDownloadBlade: () => void;
  copied: boolean;
  onResetData: () => void;
}

export const ControlBar: React.FC<ControlBarProps> = ({
  activeTab,
  setActiveTab,
  settings,
  setSettings,
  scale,
  setScale,
  onPrint,
  onCopyBlade,
  onDownloadBlade,
  copied,
  onResetData,
}) => {
  const updateSetting = <K extends keyof PrintSettings>(key: K, value: PrintSettings[K]) => {
    setSettings((prev) => ({ ...prev, [key]: value }));
  };

  return (
    <header className="no-print sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 text-white shadow-lg">
      <div className="max-w-7xl mx-auto px-4 py-3">
        {/* Upper Row: Title, Tab Buttons, and Action CTAs */}
        <div className="flex flex-wrap items-center justify-between gap-3">
          {/* Brand & Document Name */}
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-600 via-red-600 to-blue-700 flex items-center justify-center shadow-md shadow-red-900/20">
              <Printer className="w-5 h-5 text-white" />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <h1 className="font-extrabold text-base text-white tracking-wide">
                  تنسيق ملف الطباعة الرسمي (Laravel Blade)
                </h1>
                <span className="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full font-medium">
                  جاهز للطباعة A4
                </span>
              </div>
              <p className="text-xs text-slate-400 mt-0.5">
                تنسيق سيادي معتمد متوافق مع مقاس A4 ويدعم DomPDF وBrowsershot وطابعات الليزر
              </p>
            </div>
          </div>

          {/* Navigation Tabs */}
          <div className="flex items-center bg-slate-800/90 p-1 rounded-xl border border-slate-700/80 text-xs font-semibold">
            <button
              id="tab-preview-btn"
              onClick={() => setActiveTab('preview')}
              className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all cursor-pointer ${
                activeTab === 'preview'
                  ? 'bg-blue-600 text-white shadow'
                  : 'text-slate-300 hover:text-white hover:bg-slate-700/60'
              }`}
            >
              <Eye className="w-3.5 h-3.5" />
              <span>معاينة الورقة (A4)</span>
            </button>

            <button
              id="tab-code-btn"
              onClick={() => setActiveTab('code')}
              className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all cursor-pointer ${
                activeTab === 'code'
                  ? 'bg-blue-600 text-white shadow'
                  : 'text-slate-300 hover:text-white hover:bg-slate-700/60'
              }`}
            >
              <FileCode2 className="w-3.5 h-3.5" />
              <span>كود Blade المحسّن</span>
            </button>

            <button
              id="tab-editor-btn"
              onClick={() => setActiveTab('editor')}
              className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all cursor-pointer ${
                activeTab === 'editor'
                  ? 'bg-blue-600 text-white shadow'
                  : 'text-slate-300 hover:text-white hover:bg-slate-700/60'
              }`}
            >
              <Sliders className="w-3.5 h-3.5" />
              <span>تعديل البيانات النموذجية</span>
            </button>

            <button
              id="tab-comparison-btn"
              onClick={() => setActiveTab('comparison')}
              className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all cursor-pointer ${
                activeTab === 'comparison'
                  ? 'bg-blue-600 text-white shadow'
                  : 'text-slate-300 hover:text-white hover:bg-slate-700/60'
              }`}
            >
              <Sparkles className="w-3.5 h-3.5 text-amber-400" />
              <span>جدول الفروقات والحلول</span>
            </button>
          </div>

          {/* Primary Action Buttons */}
          <div className="flex items-center gap-2">
            <button
              id="copy-blade-header-btn"
              onClick={onCopyBlade}
              className="flex items-center gap-1.5 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 hover:text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm"
              title="نسخ كود Blade المنقح"
            >
              {copied ? <Check className="w-4 h-4 text-emerald-400" /> : <Copy className="w-4 h-4" />}
              <span>{copied ? 'تم النسخ بنجاح!' : 'نسخ كود Blade'}</span>
            </button>

            <button
              id="download-blade-btn"
              onClick={onDownloadBlade}
              className="flex items-center gap-1.5 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 hover:text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm"
              title="تحميل ملف print.blade.php"
            >
              <Download className="w-4 h-4 text-blue-400" />
              <span>تحميل .blade.php</span>
            </button>

            <button
              id="trigger-print-btn"
              onClick={onPrint}
              className="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-lg text-xs font-extrabold transition-all cursor-pointer shadow-md shadow-blue-900/30 active:scale-95"
            >
              <Printer className="w-4 h-4" />
              <span>طباعة فورية (A4)</span>
            </button>
          </div>
        </div>

        {/* Lower Row: Styling Themes, Print Tweaks, and Zoom controls */}
        <div className="mt-3 pt-2.5 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3 text-xs">
          {/* Theme Switcher */}
          <div className="flex items-center gap-2">
            <span className="text-slate-400 font-medium">النمط اللوني:</span>
            <div className="flex items-center gap-1 bg-slate-800/80 p-0.5 rounded-lg border border-slate-700">
              <button
                onClick={() => updateSetting('theme', 'official_navy')}
                className={`px-2.5 py-1 rounded cursor-pointer transition-all ${
                  settings.theme === 'official_navy'
                    ? 'bg-blue-900/90 text-blue-200 font-bold border border-blue-500/50'
                    : 'text-slate-400 hover:text-slate-200'
                }`}
              >
                كحلي رسمي مع شريط أحمر
              </button>
              <button
                onClick={() => updateSetting('theme', 'republic_gold')}
                className={`px-2.5 py-1 rounded cursor-pointer transition-all ${
                  settings.theme === 'republic_gold'
                    ? 'bg-amber-950/80 text-amber-300 font-bold border border-amber-600/50'
                    : 'text-slate-400 hover:text-slate-200'
                }`}
              >
                ذهبي سيادي كلاسيكي
              </button>
              <button
                onClick={() => updateSetting('theme', 'monochrome_laser')}
                className={`px-2.5 py-1 rounded cursor-pointer transition-all ${
                  settings.theme === 'monochrome_laser'
                    ? 'bg-slate-700 text-white font-bold border border-slate-500'
                    : 'text-slate-400 hover:text-slate-200'
                }`}
              >
                أحادي عالي التباين (ليزر)
              </button>
            </div>
          </div>

          {/* Feature Toggles */}
          <div className="flex flex-wrap items-center gap-3">
            <label className="flex items-center gap-1.5 cursor-pointer text-slate-300 hover:text-white">
              <input
                type="checkbox"
                checked={settings.compactA4}
                onChange={(e) => updateSetting('compactA4', e.target.checked)}
                className="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0 w-3.5 h-3.5"
              />
              <span>تطابق دقيق لصفحة A4 واحدة</span>
            </label>

            <label className="flex items-center gap-1.5 cursor-pointer text-slate-300 hover:text-white">
              <input
                type="checkbox"
                checked={settings.showWatermark}
                onChange={(e) => updateSetting('showWatermark', e.target.checked)}
                className="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0 w-3.5 h-3.5"
              />
              <span className="flex items-center gap-1">
                <ShieldAlert className="w-3 h-3 text-slate-400" />
                العلامة المائية
              </span>
            </label>

            <label className="flex items-center gap-1.5 cursor-pointer text-slate-300 hover:text-white">
              <input
                type="checkbox"
                checked={settings.showBarcode}
                onChange={(e) => updateSetting('showBarcode', e.target.checked)}
                className="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0 w-3.5 h-3.5"
              />
              <span className="flex items-center gap-1">
                <Barcode className="w-3 h-3 text-slate-400" />
                الباركود
              </span>
            </label>

            <label className="flex items-center gap-1.5 cursor-pointer text-slate-300 hover:text-white">
              <input
                type="checkbox"
                checked={settings.showQRCode}
                onChange={(e) => updateSetting('showQRCode', e.target.checked)}
                className="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0 w-3.5 h-3.5"
              />
              <span className="flex items-center gap-1">
                <QrCode className="w-3 h-3 text-slate-400" />
                رمز QR
              </span>
            </label>

            <label className="flex items-center gap-1.5 cursor-pointer text-slate-300 hover:text-white">
              <input
                type="checkbox"
                checked={settings.showSignatures}
                onChange={(e) => updateSetting('showSignatures', e.target.checked)}
                className="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0 w-3.5 h-3.5"
              />
              <span>صندوق التوقيعات</span>
            </label>

            <label className="flex items-center gap-1.5 cursor-pointer text-slate-300 hover:text-white">
              <input
                type="checkbox"
                checked={settings.showOfficialStamp}
                onChange={(e) => updateSetting('showOfficialStamp', e.target.checked)}
                className="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0 w-3.5 h-3.5"
              />
              <span className="flex items-center gap-1">
                <Stamp className="w-3 h-3 text-slate-400" />
                الختم الدائري
              </span>
            </label>
          </div>

          {/* Zoom and Reset Controls */}
          <div className="flex items-center gap-2">
            <div className="flex items-center bg-slate-800 border border-slate-700 rounded-lg px-2 py-0.5 gap-1.5">
              <button
                onClick={() => setScale(Math.max(0.6, scale - 0.1))}
                className="text-slate-400 hover:text-white p-0.5 cursor-pointer"
                title="تصغير المعاينة"
              >
                <ZoomOut className="w-3.5 h-3.5" />
              </button>
              <span className="text-[11px] font-mono w-10 text-center font-bold text-slate-200">
                {Math.round(scale * 100)}%
              </span>
              <button
                onClick={() => setScale(Math.min(1.4, scale + 0.1))}
                className="text-slate-400 hover:text-white p-0.5 cursor-pointer"
                title="تكبير المعاينة"
              >
                <ZoomIn className="w-3.5 h-3.5" />
              </button>
            </div>

            <button
              onClick={onResetData}
              className="flex items-center gap-1 text-slate-400 hover:text-slate-200 px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 cursor-pointer"
              title="إعادة تعيين البيانات الافتراضية"
            >
              <RotateCcw className="w-3 h-3" />
              <span>استعادة النموذج</span>
            </button>
          </div>
        </div>
      </div>
    </header>
  );
};
