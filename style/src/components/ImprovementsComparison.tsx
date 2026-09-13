import React from 'react';
import { CheckCircle2, XCircle, Sparkles, Shield, Printer, Code, Eye } from 'lucide-react';

export const ImprovementsComparison: React.FC = () => {
  const comparisonList = [
    {
      title: 'انضباط الصفحة الواحدة (A4 Single-Page Precision)',
      before: 'الهوامش الكبيرة (1.5cm) وحجم البطاقات غير المحسوب تسبب في تسرب سطرين أو تذييل التقرير إلى صفحة ثانية فارغة ومشوهة عند الطباعة.',
      after: 'حساب دقيق لارتفاع العناصر وتطبيق هوامش قياسية (10mm - 12mm) مع تنسيق خطوط متناسق لضمان احتواء كافة الأقسام في ورقة A4 واحدة متكاملة دون أي تسرب.',
      category: 'هندسة الصفحة',
    },
    {
      title: 'شعار الدولة والترويسة السيادية (Official Emblem)',
      before: 'استخدام أيقونة خط FontAwesome (fas fa-shield-alt) وهي أيقونة عامة قد تفشل في التحميل في بيئات الطباعة وPDF، وتفتقر للهيبة الرسمية.',
      after: 'تضمين شعار الجمهورية اليمنية المتجهي (Vector SVG) المدمج كلياً، يطبع بدقة لانهائية (300+ DPI) ولا يعتمد على خطوط خارجية، مع تقسيم ثلاثي متوازن.',
      category: 'الهوية المؤسسية',
    },
    {
      title: 'الباركود ورمز الاستجابة السريعة (Barcode & QR Verification)',
      before: 'معلومات مرجعية نصية عادية بدون أي آليات تحقق رقمي أو قراءة ضوئية في الأجهزة الأمنية والميدانية.',
      after: 'توليد باركود أمني متجهي للرقم العسكري/المرجعي مع رمز تحقق أمني مشفر (Security Verification) يمنح الاستمارة موثوقية إدارية عليا.',
      category: 'الأمان والتوثيق',
    },
    {
      title: 'حماية الجداول من التقطيع الطباعي (Page-Break Protection)',
      before: 'غياب قواعد break-inside الحديثة مما قد يؤدي في متصفحات معينة أو مكتبات DomPDF إلى شطر الجدول إلى نصفين بين الصفحات.',
      after: 'إضافة page-break-inside: avoid و break-inside: avoid إجبارياً على كافة بطاقات الأقسام وصناديق التوقيعات لمنع أي انشطار عشوائي.',
      category: 'جودة التصدير',
    },
    {
      title: 'الألوان والظلال والطباعة الليزرية (Print Color Adjustment)',
      before: 'استخدام ظلال ضبابية (box-shadow) وحواف غير محددة للألوان مما يسبب تحول الظلال إلى بقع رمادية باهتة على طابعات الليزر المكتبية.',
      after: 'تطبيق -webkit-print-color-adjust: exact واستبدال الظلال بحدود صلبة دقيقة (Crisp Hairline Borders) مع خيار نمط الطباعة الأحادية عالية التباين.',
      category: 'دقة الطباعة',
    },
    {
      title: 'صندوق التوقيعات والختم الرسمي الدائري (Signatures & Stamp)',
      before: 'لا يوجد أي موضع لتوقيع المختص أو مراجعة شؤون الأفراد أو موضع مخصص للختم الإداري.',
      after: 'إضافة قسم ثلاثي معتمد: إعداد وتجهيز المختص، تدقيق القوى البشرية، واعتماد مدير الدائرة مع موضع دائري مخصص للختم الرسمي.',
      category: 'الإجراءات الإدارية',
    },
    {
      title: 'أمان كود Laravel Blade ومعالجة القيم الفارغة (Null-Safety)',
      before: 'محاولات استدعاء مباشر لخصائص العلاقات مثل $personalData->rank->rank بدون التحقق من احتمالية كونها null مما قد يسبب خطأ 500.',
      after: 'تأمين كامل لكافة الحقول باستخدام $personalData->rank?->rank ?? "-" ومعالجة ذكية للتواريخ والروابط بصيغة Str::startsWith و asset().',
      category: 'الأمان البرمجي',
    },
  ];

  return (
    <div className="max-w-5xl mx-auto px-4 py-6 space-y-6">
      <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6">
        <div className="flex items-center gap-3 mb-2">
          <div className="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center">
            <Sparkles className="w-5 h-5" />
          </div>
          <div>
            <h2 className="text-lg font-black text-white">
              تقرير التحسينات والمعايير الهندسية المطبقة
            </h2>
            <p className="text-xs text-slate-400">
              مقارنة تفصيلية بين ملف الطباعة السابق والتنسيق الاحترافي المطور
            </p>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 gap-4">
        {comparisonList.map((item, index) => (
          <div
            key={index}
            className="bg-slate-900 border border-slate-800 rounded-xl p-5 hover:border-slate-700 transition-all shadow-sm"
          >
            <div className="flex items-center justify-between mb-3">
              <h3 className="font-extrabold text-sm text-white flex items-center gap-2">
                <span className="w-6 h-6 rounded-full bg-blue-600/20 text-blue-400 border border-blue-500/30 text-xs flex items-center justify-center font-mono">
                  {index + 1}
                </span>
                <span>{item.title}</span>
              </h3>
              <span className="text-[11px] bg-slate-800 text-slate-300 px-2.5 py-0.5 rounded-full border border-slate-700">
                {item.category}
              </span>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs mt-3">
              {/* Previous State */}
              <div className="bg-red-950/30 border border-red-900/40 rounded-lg p-3.5 text-slate-300">
                <div className="flex items-center gap-1.5 text-red-400 font-bold mb-1.5 text-[11.5px]">
                  <XCircle className="w-4 h-4" />
                  <span>في الملف السابق:</span>
                </div>
                <p className="leading-relaxed text-slate-300/90">{item.before}</p>
              </div>

              {/* Improved State */}
              <div className="bg-emerald-950/30 border border-emerald-900/40 rounded-lg p-3.5 text-slate-200">
                <div className="flex items-center gap-1.5 text-emerald-400 font-bold mb-1.5 text-[11.5px]">
                  <CheckCircle2 className="w-4 h-4" />
                  <span>في التنسيق المحترف الجديد:</span>
                </div>
                <p className="leading-relaxed text-slate-200">{item.after}</p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};
