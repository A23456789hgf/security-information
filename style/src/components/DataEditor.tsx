import React from 'react';
import { PersonalData, PrintSettings } from '../types';
import { User, Shield, Briefcase, Heart, Home, FileText, Settings, Image as ImageIcon } from 'lucide-react';

interface DataEditorProps {
  data: PersonalData;
  setData: React.Dispatch<React.SetStateAction<PersonalData>>;
  settings: PrintSettings;
  setSettings: React.Dispatch<React.SetStateAction<PrintSettings>>;
}

export const DataEditor: React.FC<DataEditorProps> = ({
  data,
  setData,
  settings,
  setSettings,
}) => {
  const handleChange = (field: keyof PersonalData, value: any) => {
    setData((prev) => ({ ...prev, [field]: value }));
  };

  const handleNestedChange = (parent: keyof PersonalData, child: string, value: string) => {
    setData((prev) => ({
      ...prev,
      [parent]: {
        ...(prev[parent] as object),
        [child]: value,
      },
    }));
  };

  return (
    <div className="max-w-5xl mx-auto px-4 py-6 space-y-6">
      {/* Overview Banner */}
      <div className="bg-gradient-to-r from-blue-900/50 via-slate-900 to-indigo-950 border border-blue-800/40 p-5 rounded-2xl">
        <h2 className="text-lg font-black text-white flex items-center gap-2">
          <Settings className="w-5 h-5 text-blue-400" />
          <span>محرر البيانات النموذجية واختبار مرونة استمارة الطباعة</span>
        </h2>
        <p className="text-xs text-slate-300 mt-1">
          قم بتعديل بيانات المنتسب أو الجهة الحكومية لمعاينة كيف يتكيف التنسيق الهندسي لـ A4 مع النصوص الطويلة، الصور، والرتب المختلفة فورياً.
        </p>
      </div>

      {/* 1. Header Customization */}
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 className="text-sm font-extrabold text-blue-400 flex items-center gap-2 mb-4">
          <Shield className="w-4 h-4" />
          <span>بيانات الترويسة الرسمية (جهة الإصدار)</span>
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الدولة / الكيان:</label>
            <input
              type="text"
              value={settings.customMinistry}
              onChange={(e) => setSettings((s) => ({ ...s, customMinistry: e.target.value }))}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-medium focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الوزارة / الهيئة:</label>
            <input
              type="text"
              value={settings.customAuthority}
              onChange={(e) => setSettings((s) => ({ ...s, customAuthority: e.target.value }))}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-medium focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الإدارة / الوحدة:</label>
            <input
              type="text"
              value={settings.customUnit}
              onChange={(e) => setSettings((s) => ({ ...s, customUnit: e.target.value }))}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-medium focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">عنوان التقرير:</label>
            <input
              type="text"
              value={settings.customReportTitle}
              onChange={(e) => setSettings((s) => ({ ...s, customReportTitle: e.target.value }))}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-medium focus:border-blue-500 focus:outline-none"
            />
          </div>
        </div>
      </div>

      {/* 2. Identity Data */}
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 className="text-sm font-extrabold text-blue-400 flex items-center gap-2 mb-4">
          <User className="w-4 h-4" />
          <span>البيانات التعريفية والشخصية</span>
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الاسم الرباعي واللقب:</label>
            <input
              type="text"
              value={data.name}
              onChange={(e) => handleChange('name', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-bold focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الاسم الحركي / الجهادي:</label>
            <input
              type="text"
              value={data.jihadi_name || ''}
              onChange={(e) => handleChange('jihadi_name', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الرقم الوطني / الهوية:</label>
            <input
              type="text"
              value={data.national_id_number}
              onChange={(e) => handleChange('national_id_number', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-mono focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الرقم العسكري:</label>
            <input
              type="text"
              value={data.military_number}
              onChange={(e) => handleChange('military_number', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-mono font-bold text-amber-400 focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">تاريخ الميلاد (ميلادي):</label>
            <input
              type="text"
              value={data.date_of_birth_gregorian}
              onChange={(e) => handleChange('date_of_birth_gregorian', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-mono focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">تاريخ الميلاد (هجري):</label>
            <input
              type="text"
              value={data.date_of_birth_hijri || ''}
              onChange={(e) => handleChange('date_of_birth_hijri', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div className="md:col-span-2 lg:col-span-3">
            <label className="block text-slate-400 mb-1 font-semibold flex items-center gap-1.5">
              <ImageIcon className="w-3.5 h-3.5 text-slate-400" />
              <span>رابط الصورة الشخصية (أو اتركه فارغاً لتجربة الرمز الافتراضي):</span>
            </label>
            <div className="flex gap-2">
              <input
                type="text"
                value={data.main_profile_photo || ''}
                onChange={(e) => handleChange('main_profile_photo', e.target.value)}
                placeholder="https://..."
                className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white text-xs font-mono focus:border-blue-500 focus:outline-none"
              />
              <button
                type="button"
                onClick={() => handleChange('main_profile_photo', '')}
                className="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs cursor-pointer"
              >
                مسح الصورة
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* 3. Military & Job Info */}
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 className="text-sm font-extrabold text-blue-400 flex items-center gap-2 mb-4">
          <Briefcase className="w-4 h-4" />
          <span>البيانات الوظيفية والعسكرية</span>
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الرتبة العسكرية:</label>
            <input
              type="text"
              value={data.rank?.rank || ''}
              onChange={(e) => handleNestedChange('rank', 'rank', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-bold focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الدائرة / المنطقة:</label>
            <input
              type="text"
              value={data.militaryDistrict?.name || ''}
              onChange={(e) => handleNestedChange('militaryDistrict', 'name', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الشعبة / الوحدة:</label>
            <input
              type="text"
              value={data.division?.name || ''}
              onChange={(e) => handleNestedChange('division', 'name', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">حالة الوظيفة:</label>
            <input
              type="text"
              value={data.jobStatus?.job_status || ''}
              onChange={(e) => handleNestedChange('jobStatus', 'job_status', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">تاريخ التعيين:</label>
            <input
              type="text"
              value={data.appointment_date || ''}
              onChange={(e) => handleChange('appointment_date', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white font-mono focus:border-blue-500 focus:outline-none"
            />
          </div>
        </div>
      </div>

      {/* 4. Medical & Social */}
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 className="text-sm font-extrabold text-blue-400 flex items-center gap-2 mb-4">
          <Heart className="w-4 h-4" />
          <span>البيانات الطبية والاجتماعية والمؤهلات</span>
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">فصيلة الدم:</label>
            <input
              type="text"
              value={data.bloodType?.type || ''}
              onChange={(e) => handleNestedChange('bloodType', 'type', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-red-400 font-mono font-bold focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">الحالة الاجتماعية:</label>
            <input
              type="text"
              value={data.maritalStatus?.status || ''}
              onChange={(e) => handleNestedChange('maritalStatus', 'status', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">عدد الأبناء:</label>
            <input
              type="number"
              value={data.number_of_children}
              onChange={(e) => handleChange('number_of_children', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div>
            <label className="block text-slate-400 mb-1 font-semibold">المؤهل العلمي:</label>
            <input
              type="text"
              value={data.qualification?.name || ''}
              onChange={(e) => handleNestedChange('qualification', 'name', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
          <div className="md:col-span-2">
            <label className="block text-slate-400 mb-1 font-semibold">التخصص:</label>
            <input
              type="text"
              value={data.specialization || ''}
              onChange={(e) => handleChange('specialization', e.target.value)}
              className="w-full bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-blue-500 focus:outline-none"
            />
          </div>
        </div>
      </div>

      {/* 5. Address and Notes */}
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h3 className="text-sm font-extrabold text-blue-400 flex items-center gap-2 mb-4">
          <FileText className="w-4 h-4" />
          <span>الملاحظات الرسمية</span>
        </h3>
        <div>
          <label className="block text-slate-400 mb-1 font-semibold text-xs">نص الملاحظات الإضافية:</label>
          <textarea
            rows={3}
            value={data.notes || ''}
            onChange={(e) => handleChange('notes', e.target.value)}
            className="w-full bg-slate-950 border border-slate-700 rounded-lg p-3 text-white text-xs leading-relaxed focus:border-blue-500 focus:outline-none"
          />
        </div>
      </div>
    </div>
  );
};
