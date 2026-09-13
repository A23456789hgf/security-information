import React, { useState, useMemo } from 'react';
import { initialPersonalData, defaultSettings } from './data/sampleData';
import { generateBladeCode } from './data/bladeTemplateGenerator';
import { DocumentPreview } from './components/DocumentPreview';
import { ControlBar } from './components/ControlBar';
import { BladeCodeViewer } from './components/BladeCodeViewer';
import { DataEditor } from './components/DataEditor';
import { ImprovementsComparison } from './components/ImprovementsComparison';
import { PersonalData, PrintSettings } from './types';
import { Printer, Copy, Check, Sparkles, FileCode2, ArrowRight } from 'lucide-react';

export default function App() {
  const [data, setData] = useState<PersonalData>(initialPersonalData);
  const [settings, setSettings] = useState<PrintSettings>(defaultSettings);
  const [activeTab, setActiveTab] = useState<'preview' | 'code' | 'editor' | 'comparison'>('preview');
  const [scale, setScale] = useState<number>(0.95);
  const [copied, setCopied] = useState<boolean>(false);

  const generatedBladeCode = useMemo(() => {
    return generateBladeCode(settings);
  }, [settings]);

  const handlePrint = () => {
    window.print();
  };

  const handleCopyBlade = () => {
    navigator.clipboard.writeText(generatedBladeCode);
    setCopied(true);
    setTimeout(() => setCopied(false), 2500);
  };

  const handleDownloadBlade = () => {
    const blob = new Blob([generatedBladeCode], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'personal-data-print.blade.php';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  };

  const handleResetData = () => {
    setData(initialPersonalData);
    setSettings(defaultSettings);
  };

  return (
    <div className="min-h-screen bg-slate-950 text-slate-100 flex flex-col font-['Cairo',sans-serif] selection:bg-blue-600 selection:text-white">
      {/* Top Navigation and Controls */}
      <ControlBar
        activeTab={activeTab}
        setActiveTab={setActiveTab}
        settings={settings}
        setSettings={setSettings}
        scale={scale}
        setScale={setScale}
        onPrint={handlePrint}
        onCopyBlade={handleCopyBlade}
        onDownloadBlade={handleDownloadBlade}
        copied={copied}
        onResetData={handleResetData}
      />

      {/* Main View Area */}
      <main className="flex-1 overflow-x-auto p-4 md:p-8 flex flex-col items-center">
        {activeTab === 'preview' && (
          <div className="w-full flex flex-col items-center">
            {/* Quick tips bar */}
            <div className="no-print w-full max-w-4xl mb-4 bg-slate-900/90 border border-slate-800 rounded-xl px-4 py-2.5 flex flex-wrap items-center justify-between text-xs text-slate-300 gap-2">
              <div className="flex items-center gap-2">
                <span className="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse" />
                <span className="font-semibold text-white">معاينة ورقة A4 حقيقية (210mm × 297mm):</span>
                <span className="text-slate-400">كافة العناصر مضبوطة بدقة لمنع تجاوز صفحة واحدة</span>
              </div>
              <div className="flex items-center gap-2">
                <button
                  onClick={handlePrint}
                  className="flex items-center gap-1.5 px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded-md font-bold cursor-pointer transition-all shadow"
                >
                  <Printer className="w-3.5 h-3.5" />
                  <span>طباعة فورية</span>
                </button>
                <button
                  onClick={() => setActiveTab('code')}
                  className="flex items-center gap-1.5 px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-md font-bold cursor-pointer transition-all border border-slate-700"
                >
                  <FileCode2 className="w-3.5 h-3.5 text-blue-400" />
                  <span>نسخ كود Blade</span>
                </button>
              </div>
            </div>

            {/* Document Render Wrapper */}
            <div className="w-full flex justify-center overflow-x-auto py-2">
              <DocumentPreview data={data} settings={settings} scale={scale} />
            </div>

            {/* Bottom Controls (for preview mode) */}
            <div className="no-print mt-8 flex flex-wrap items-center justify-center gap-3">
              <button
                onClick={handlePrint}
                className="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-black text-sm transition-all shadow-lg flex items-center gap-2 cursor-pointer"
              >
                <Printer className="w-4 h-4" />
                <span>طباعة التقرير (Ctrl + P)</span>
              </button>

              <button
                onClick={handleCopyBlade}
                className="px-5 py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white rounded-xl font-bold text-sm transition-all flex items-center gap-2 cursor-pointer"
              >
                {copied ? <Check className="w-4 h-4 text-emerald-400" /> : <Copy className="w-4 h-4" />}
                <span>{copied ? 'تم النسخ بنجاح!' : 'نسخ كود Blade المنقح'}</span>
              </button>

              <button
                onClick={() => setActiveTab('comparison')}
                className="px-5 py-3 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-amber-400 rounded-xl font-bold text-sm transition-all flex items-center gap-2 cursor-pointer"
              >
                <Sparkles className="w-4 h-4" />
                <span>ما الذي تم إصلاحه وتحسينه؟</span>
              </button>
            </div>
          </div>
        )}

        {activeTab === 'code' && (
          <BladeCodeViewer
            code={generatedBladeCode}
            onCopy={handleCopyBlade}
            onDownload={handleDownloadBlade}
            copied={copied}
          />
        )}

        {activeTab === 'editor' && (
          <DataEditor
            data={data}
            setData={setData}
            settings={settings}
            setSettings={setSettings}
          />
        )}

        {activeTab === 'comparison' && <ImprovementsComparison />}
      </main>

      {/* Footer */}
      <footer className="no-print mt-auto py-4 border-t border-slate-900 bg-slate-950/80 text-center text-xs text-slate-500">
        منصة تنسيق وتطوير استمارات وتقارير الطباعة لـ Laravel Blade • متوافق مع كافة الطابعات ومكتبات تصدير PDF
      </footer>
    </div>
  );
}
