export interface PersonalData {
  id: number;
  name: string;
  jihadi_name?: string;
  national_id_number: string;
  military_number: string;
  date_of_birth_gregorian: string;
  date_of_birth_hijri?: string;
  main_profile_photo?: string;
  rank?: {
    rank: string;
    badge_code?: string;
  };
  militaryDistrict?: {
    name: string;
  };
  division?: {
    name: string;
  };
  jobStatus?: {
    job_status: string;
  };
  appointment_date?: string;
  bloodType?: {
    type: string;
  };
  maritalStatus?: {
    status: string;
  };
  number_of_children: number | string;
  qualification?: {
    name: string;
  };
  specialization?: string;
  governorate?: {
    name: string;
  };
  directorate?: {
    name: string;
  };
  subArea?: {
    name: string;
  };
  village?: {
    name: string;
  };
  placeOfBirthCountry?: {
    name: string;
  };
  placeOfBirthGovernorate?: {
    name: string;
  };
  placeOfBirthDirectorate?: {
    name: string;
  };
  placeOfBirthVillage?: {
    name: string;
  };
  notes?: string;
  phone_number?: string;
  emergency_contact?: string;
}

export type PrintTheme = 'official_navy' | 'republic_gold' | 'monochrome_laser';

export interface PrintSettings {
  theme: PrintTheme;
  showWatermark: boolean;
  watermarkText: string;
  showSignatures: boolean;
  showOfficialStamp: boolean;
  showBarcode: boolean;
  showQRCode: boolean;
  compactA4: boolean;
  customMinistry: string;
  customAuthority: string;
  customUnit: string;
  customReportTitle: string;
}
