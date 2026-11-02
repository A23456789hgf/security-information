<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalData extends Model
{
    use HasFactory;

    protected $table = 'personal_data';

    protected $fillable = [
        'name',
        'jihadi_name',
        'phone_1',
        'phone_2',
        'phone_3',
        'national_id_number',
        'military_number',
        'date_of_birth_gregorian',
        'date_of_birth_hijri',
        'blood_type_id',
        'marital_status_id',
        'qualification_id',
        'number_classification_id',
        'job_status_id',
        'rank_id',
        'military_district_id',
        'division_id',
        'unit',
        'job',
        'workplace',
        'job_specialty',
        'jihadist_number',
        'financial_status_id',
        'place_of_birth_country_id',
        'place_of_birth_governorate_id',
        'place_of_birth_directorate_id',
        'place_of_birth_sub_area_id',
        'place_of_birth_village_id',
        'country_id',
        'governorate_id',
        'directorate_id',
        'sub_area_id',
        'village_id',
        'internal_entity_id',
        'main_profile_photo',
    ];

    protected $appends = ['photo_url'];

    protected $casts = [
        'date_of_birth_gregorian' => 'date',
    ];

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }

    public function numberClassification()
    {
        return $this->belongsTo(NumberClassification::class);
    }

    public function jobStatus()
    {
        return $this->belongsTo(JobStatus::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function militaryDistrict()
    {
        return $this->belongsTo(MilitaryDistrict::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function placeOfBirthCountry()
    {
        return $this->belongsTo(Country::class, 'place_of_birth_country_id');
    }

    public function placeOfBirthGovernorate()
    {
        return $this->belongsTo(Governorate::class, 'place_of_birth_governorate_id');
    }

    public function placeOfBirthDirectorate()
    {
        return $this->belongsTo(Directorate::class, 'place_of_birth_directorate_id');
    }

    public function placeOfBirthSubArea()
    {
        return $this->belongsTo(SubArea::class, 'place_of_birth_sub_area_id');
    }

    public function placeOfBirthVillage()
    {
        return $this->belongsTo(Village::class, 'place_of_birth_village_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function subArea()
    {
        return $this->belongsTo(SubArea::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function photos()
    {
        return $this->hasMany(PersonalDataPhoto::class);
    }

    public function securityFiles()
    {
        return $this->hasMany(SecurityFile::class, 'personal_data_id');
    }

    public function entity()
    {
        return $this->belongsTo(InternalEntity::class, 'internal_entity_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function wives()
    {
        return $this->hasMany(Wife::class);
    }

    public function relatives()
    {
        return $this->hasMany(Relative::class);
    }

    public function enemyAreaRelatives()
    {
        return $this->hasMany(EnemyAreaRelative::class);
    }

    public function travelingRelatives()
    {
        return $this->hasMany(TravelingRelative::class);
    }

    public function arrests()
    {
        return $this->hasMany(Arrest::class);
    }

    public function financialStatus()
    {
        return $this->belongsTo(FinancialStatus::class);
    }

    public function secondaryData()
    {
        return $this->hasOne(SecondaryData::class);
    }

    /**
     * Get security reports matching this person's ID or Military Number
     */
    public function securityReports()
    {
        return SecurityReport::where(function ($query) {
            $hasCondition = false;
            if ($this->national_id_number) {
                $query->where('id_number', $this->national_id_number);
                $hasCondition = true;
            }
            if ($this->military_number) {
                if ($hasCondition) {
                    $query->orWhere('military_number', $this->military_number);
                } else {
                    $query->where('military_number', $this->military_number);
                    $hasCondition = true;
                }
            }
            if ($this->name) {
                if ($hasCondition) {
                    $query->orWhere('full_name', $this->name);
                } else {
                    $query->where('full_name', $this->name);
                }
            }
        })->get();
    }

    public function suspects()
    {
        return $this->hasMany(Suspect::class);
    }

    /**
     * Get dynamic records where this person is mentioned (by ID or military number)
     */
    public function dynamicRecords()
    {
        $identifiers = array_filter([$this->national_id_number, $this->military_number]);
        if (empty($identifiers)) {
            return collect();
        }

        // Find record IDs where any field value matches the identifiers
        $recordIds = \App\Models\RecordValue::whereIn('value', $identifiers)
            ->pluck('record_id')
            ->unique();

        return \App\Models\Record::whereIn('id', $recordIds)
            ->with(['page', 'values.field'])
            ->get();
    }

    /**
     * Get system user account associated with this person
     */
    public function relatedUser()
    {
        // Try to find a user by name (exact match)
        return \App\Models\User::where('name', $this->name)->first();
    }

    /**
     * Get profile photo URL
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->main_profile_photo) {
            return asset('storage/' . $this->main_profile_photo);
        }

        $photo = $this->relationLoaded('photos') ? $this->photos->first() : $this->photos()->first();
        if ($photo && $photo->photo_path) {
            return asset('storage/' . $photo->photo_path);
        }

        return null;
    }
}
