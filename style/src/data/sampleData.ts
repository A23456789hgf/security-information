import { PersonalData, PrintSettings } from '../types';

export const initialPersonalData: PersonalData = {
  id: 10482,
  name: 'محمد عبد الله حسين الحاشدي',
  jihadi_name: 'أبو حيدر',
  national_id_number: '10010482914',
  military_number: '884920',
  date_of_birth_gregorian: '1989-05-14',
  date_of_birth_hijri: '1409/10/09 هـ',
  main_profile_photo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&auto=format&fit=crop&q=80',
  rank: {
    rank: 'رائد ركن',
    badge_code: 'O-4',
  },
  militaryDistrict: {
    name: 'المنطقة العسكرية المركزية',
  },
  division: {
    name: 'شعبة التخطيط والمعلومات',
  },
  jobStatus: {
    job_status: 'على رأس العمل (دائم)',
  },
  appointment_date: '2016-03-20',
  bloodType: {
    type: 'O+',
  },
  maritalStatus: {
    status: 'متزوج',
  },
  number_of_children: 4,
  qualification: {
    name: 'بكالوريوس علوم عسكرية وإدارية',
  },
  specialization: 'نظم معلومات واتصالات',
  governorate: {
    name: 'أمانة العاصمة - صنعاء',
  },
  directorate: {
    name: 'مديرية السبعين',
  },
  subArea: {
    name: 'حي الأصبحي الشرقي',
  },
  village: {
    name: 'شارع 24 - جوار جامع الفردوس',
  },
  placeOfBirthCountry: {
    name: 'الجمهورية اليمنية',
  },
  placeOfBirthGovernorate: {
    name: 'محافظة عمران',
  },
  placeOfBirthDirectorate: {
    name: 'مديرية خمر',
  },
  placeOfBirthVillage: {
    name: 'قرية بيت العنابي',
  },
  notes: 'تم استيفاء كافة الوثائق والبيانات الثبوتية بموجب محاضر التدقيق الميداني رقم (142/ق) لسنة 2024م. حاصل على دورة القيادة والأركان الصغرى عام 2021م.',
  phone_number: '771234567',
  emergency_contact: '778998877 (شقيق - عبد الله)',
};

export const defaultSettings: PrintSettings = {
  theme: 'official_navy',
  showWatermark: true,
  watermarkText: 'وثيقة رسمية معتمدة',
  showSignatures: true,
  showOfficialStamp: true,
  showBarcode: true,
  showQRCode: true,
  compactA4: true,
  customMinistry: 'الجمهورية اليمنية',
  customAuthority: 'التعبئة العامة',
  customUnit: 'وحدة نظم وتكنولوجيا المعلومات',
  customReportTitle: 'تقرير السجل الأساسي للمنتسب',
};
