<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Configuration\BloodTypeController;
use App\Http\Controllers\Configuration\QualificationController;
use App\Http\Controllers\Configuration\MaritalStatusController;
use App\Http\Controllers\Configuration\NumberClassificationController;
use App\Http\Controllers\Configuration\JobStatusController;
use App\Http\Controllers\Configuration\RankController;
use App\Http\Controllers\Configuration\FinancialStatusController;
use App\Http\Controllers\Configuration\MilitaryDistrictController;
use App\Http\Controllers\Configuration\DivisionController;
use App\Http\Controllers\Configuration\AxisController;
use App\Http\Controllers\Configuration\CourseTypeController;
use App\Http\Controllers\Configuration\FillingAgencyController;
use App\Http\Controllers\Configuration\ElectronicInputController;
use App\Http\Controllers\Configuration\GovernorateController;
use App\Http\Controllers\Configuration\DirectorateController;
use App\Http\Controllers\Configuration\SubAreaController;
use App\Http\Controllers\Configuration\VillageController;
use App\Http\Controllers\Configuration\CountryController;
use App\Http\Controllers\Configuration\ArrestingAuthorityController;
use App\Http\Controllers\Configuration\CaseTypeController;
use App\Http\Controllers\Configuration\ConfigurationImportExportController;
use App\Http\Controllers\PersonalDataController;
use App\Http\Controllers\System\CourseController;
use App\Http\Controllers\System\WifeController;
use App\Http\Controllers\System\ArrestController;
use App\Http\Controllers\System\SuspectController;
use App\Http\Controllers\System\SecurityReportController;
use App\Http\Controllers\Data\DataImportController;
use App\Http\Controllers\Data\DataDifferentiationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $stats = [
        'personal_today' => \App\Models\PersonalData::whereDate('created_at', now())->count(),
        'villages_today' => \App\Models\Village::whereDate('created_at', now())->count(),
        'personal_data_count' => \App\Models\PersonalData::count(),
        'governorates_count' => \App\Models\Governorate::count(),
        'directorates_count' => \App\Models\Directorate::count(),
        'villages_count' => \App\Models\Village::count(),
        'total_today' => 0,
    ];
    $stats['total_today'] = $stats['personal_today'] + $stats['villages_today'];
    
    return view('dashboard', compact('stats'));
})->middleware('auth')->name('dashboard');

// System Routes
Route::middleware('auth')->prefix('system')->name('system.')->group(function () {
    Route::get('personal-data/import', [App\Http\Controllers\System\PersonalDataImportExportController::class, 'importForm'])->name('personal-data.import');
    Route::post('personal-data/import-parse', [App\Http\Controllers\System\PersonalDataImportExportController::class, 'import'])->name('personal-data.import-parse');
    Route::post('personal-data/import-store', [App\Http\Controllers\System\PersonalDataImportExportController::class, 'importStore'])->name('personal-data.import-store');
    Route::post('personal-data/validate-import', [App\Http\Controllers\System\PersonalDataImportExportController::class, 'validateImport'])->name('personal-data.validate-import');
    Route::get('personal-data/export', [App\Http\Controllers\System\PersonalDataImportExportController::class, 'exportForm'])->name('personal-data.export');
    Route::post('personal-data/export-download', [App\Http\Controllers\System\PersonalDataImportExportController::class, 'export'])->name('personal-data.export-download');
    Route::get('personal-data/template', [App\Http\Controllers\System\PersonalDataImportExportController::class, 'template'])->name('personal-data.template');

    Route::get('personal-data/fast-entry', [\App\Http\Controllers\PersonalDataController::class, 'fastEntry'])->name('personal-data.fast-entry');
    Route::post('personal-data/bulk-store', [\App\Http\Controllers\PersonalDataController::class, 'bulkStore'])->name('personal-data.bulk-store');
    Route::get('personal-data/{personal_data}/print', [\App\Http\Controllers\PersonalDataController::class, 'print'])->name('personal-data.print');
    Route::get('personal-data/{personal_data}/print-all', [\App\Http\Controllers\PersonalDataController::class, 'printFull'])->name('personal-data.print-all');
    Route::resource('personal-data', \App\Http\Controllers\PersonalDataController::class)->parameters(['personal-data' => 'personal_data']);
    Route::delete('personal-data/photo/{id}', [\App\Http\Controllers\PersonalDataController::class, 'deletePhoto'])->name('personal-data.delete-photo');
    Route::resource('secondary-data', App\Http\Controllers\System\SecondaryDataController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('wives', WifeController::class);
    Route::resource('arrests', ArrestController::class);
    Route::get('arrests/personal-data/{id}', [ArrestController::class, 'getPersonalData'])->name('arrests.get-personal-data');
    Route::delete('arrests/{arrest}/attachment', [ArrestController::class, 'deleteAttachment'])->name('arrests.delete-attachment');
    Route::resource('suspicious', SuspectController::class);
    Route::get('suspicious/personal-data/{id}', [SuspectController::class, 'getPersonalData'])->name('suspicious.get-personal-data');
    Route::delete('suspicious/{suspicious}/attachment', [SuspectController::class, 'deleteAttachment'])->name('suspicious.delete-attachment');
    Route::get('security-reports/import', [SecurityReportController::class, 'import'])->name('security-reports.import');
    Route::post('security-reports/upload', [SecurityReportController::class, 'upload'])->name('security-reports.upload');
    Route::resource('security-reports', SecurityReportController::class);
    Route::post('security-reports/store-import', [SecurityReportController::class, 'storeImport'])->name('security-reports.store-import');
    Route::get('wives/person-details/{id}', [WifeController::class, 'getPersonDetails'])->name('wives.get-person-details');
    Route::get('courses/person-details/{id}', [CourseController::class, 'getPersonDetails'])->name('courses.get-person-details');
    Route::get('personal-data/details/{id}', [\App\Http\Controllers\PersonalDataController::class, 'getDetails'])->name('personal-data.details');

    // Dynamic Pages Management
    Route::resource('pages', App\Http\Controllers\System\PageController::class);
    Route::post('pages/{page}/fields', [App\Http\Controllers\System\PageController::class, 'storeField'])->name('pages.fields.store');
    Route::put('fields/{field}', [App\Http\Controllers\System\PageController::class, 'updateField'])->name('fields.update');
    Route::delete('fields/{field}', [App\Http\Controllers\System\PageController::class, 'destroyField'])->name('fields.destroy');

    // Dynamic Data Entry
    Route::get('data/{page:slug}', [App\Http\Controllers\System\DynamicDataController::class, 'show'])->name('dynamic-data.show');
    Route::get('data/{page:slug}/create', [App\Http\Controllers\System\DynamicDataController::class, 'create'])->name('dynamic-data.create');
    Route::post('data/{page:slug}/store', [App\Http\Controllers\System\DynamicDataController::class, 'store'])->name('dynamic-data.store');
    Route::get('data/{page:slug}/{record}/edit', [App\Http\Controllers\System\DynamicDataController::class, 'edit'])->name('dynamic-data.edit');
    Route::put('data/{page:slug}/{record}', [App\Http\Controllers\System\DynamicDataController::class, 'update'])->name('dynamic-data.update');
    Route::delete('data/{page:slug}/{record}', [App\Http\Controllers\System\DynamicDataController::class, 'destroy'])->name('dynamic-data.destroy');

    // Dynamic Import/Export
    Route::get('data/{page:slug}/import', [App\Http\Controllers\System\DynamicImportExportController::class, 'importForm'])->name('dynamic-data.import');
    Route::post('data/{page:slug}/upload', [App\Http\Controllers\System\DynamicImportExportController::class, 'upload'])->name('dynamic-data.upload');
    Route::post('data/{page:slug}/import-store', [App\Http\Controllers\System\DynamicImportExportController::class, 'storeImport'])->name('dynamic-data.import-store');
    Route::get('data/{page:slug}/export', [App\Http\Controllers\System\DynamicImportExportController::class, 'export'])->name('dynamic-data.export');
    Route::get('data/{page:slug}/template', [App\Http\Controllers\System\DynamicImportExportController::class, 'template'])->name('dynamic-data.template');

    Route::resource('roles', App\Http\Controllers\System\RoleController::class);
    Route::resource('users', App\Http\Controllers\System\UserController::class);
    Route::resource('entities', App\Http\Controllers\System\EntityController::class);
    Route::resource('sections', App\Http\Controllers\System\SectionController::class);

    // Profile Management
    Route::get('profile', [App\Http\Controllers\System\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [App\Http\Controllers\System\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\System\ProfileController::class, 'updatePassword'])->name('profile.password');

    // UI Style Guide
    Route::get('ui-style-guide', function() {
        return view('system.ui-style-guide');
    })->name('ui-style-guide');
});

// Configuration Routes
Route::middleware('auth')->prefix('configuration')->name('configuration.')->group(function () {
    $cfg = function (string $resource, string $controller) {
        Route::resource($resource, $controller)->except(['show']);
        Route::get($resource  . '/search',       [$controller, 'search'])->name($resource . '.search');
        Route::post($resource . '/bulk-delete',  [$controller, 'bulkDelete'])->name($resource . '.bulk-delete');
        Route::patch($resource . '/{id}/status', [$controller, 'changeStatus'])->name($resource . '.change-status');
        Route::post($resource . '/bulk-status',  [$controller, 'bulkChangeStatus'])->name($resource . '.bulk-change-status');
        Route::get($resource  . '/export',       [$controller, 'export'])->name($resource . '.export');
        Route::get($resource  . '/import',       [$controller, 'importForm'])->name($resource . '.import');
        Route::post($resource . '/import-parse', [$controller, 'import'])->name($resource . '.import-parse');
        Route::post($resource . '/import-store', [$controller, 'importStore'])->name($resource . '.import-store');
        Route::post($resource . '/validate-import', [$controller, 'validateImport'])->name($resource . '.validate-import');
        Route::get($resource  . '/template',     [$controller, 'template'])->name($resource . '.template');
    };

    Route::get('villages/fast-entry', [VillageController::class, 'fastEntry'])->name('villages.fast-entry');
    Route::post('villages/bulk-store', [VillageController::class, 'bulkStore'])->name('villages.bulk-store');

    $cfg('blood-types',          BloodTypeController::class);
    $cfg('qualifications',       QualificationController::class);
    $cfg('marital-statuses',     MaritalStatusController::class);
    $cfg('job-statuses',         JobStatusController::class);
    $cfg('ranks',                RankController::class);
    $cfg('financial-statuses',    FinancialStatusController::class);
    $cfg('number-classifications', NumberClassificationController::class);
    $cfg('military-districts',     MilitaryDistrictController::class);
    $cfg('divisions',              DivisionController::class);
    $cfg('axes',                   AxisController::class);
    $cfg('course-types',           CourseTypeController::class);
    $cfg('filling-agencies',       FillingAgencyController::class);
    $cfg('electronic-inputs',      ElectronicInputController::class);
    $cfg('countries',      CountryController::class);
    $cfg('governorates',   GovernorateController::class);
    $cfg('directorates',   DirectorateController::class);
    Route::get('/sub-areas/import',            [SubAreaController::class, 'importForm'])->name('sub-areas.import');
    Route::post('/sub-areas/import',           [SubAreaController::class, 'import'])->name('sub-areas.import-parse');
    Route::post('/sub-areas/validate-import',  [SubAreaController::class, 'validateImport'])->name('sub-areas.validate-import');
    Route::post('/sub-areas/import-store',     [SubAreaController::class, 'importStore'])->name('sub-areas.import-store');
    Route::get('/sub-areas/template',          [SubAreaController::class, 'template'])->name('sub-areas.template');
    Route::get('/sub-areas/export',            [SubAreaController::class, 'export'])->name('sub-areas.export');

    Route::get('/villages/import',            [VillageController::class, 'importForm'])->name('villages.import');
    Route::post('/villages/import',           [VillageController::class, 'import'])->name('villages.import-parse');
    Route::post('/villages/validate-import',  [VillageController::class, 'validateImport'])->name('villages.validate-import');
    Route::post('/villages/import-store',     [VillageController::class, 'importStore'])->name('villages.import-store');
    Route::get('/villages/template',          [VillageController::class, 'template'])->name('villages.template');
    Route::get('/villages/export',            [VillageController::class, 'export'])->name('villages.export');

    $cfg('sub-areas',      SubAreaController::class);
    $cfg('villages',       VillageController::class);
    $cfg('arresting-authorities', ArrestingAuthorityController::class);
    $cfg('case-types',            CaseTypeController::class);

    Route::get('/import-progress', [ConfigurationImportExportController::class, 'getImportProgress'])->name('import.progress');
});

// AJAX Routes for Dependent Dropdowns
Route::middleware('auth')->prefix('api/geo')->name('api.geo.')->group(function () {
    Route::get('/directorates/{governorate}', function ($id) {
        return \App\Models\Directorate::where('governorate_id', $id)->pluck('name', 'id');
    })->name('directorates');

    Route::get('/sub-areas/{directorate}', function ($id) {
        return \App\Models\SubArea::where('directorate_id', $id)->pluck('name', 'id');
    })->name('sub-areas');

    Route::get('/villages/{sub_area}', function ($id) {
        return \App\Models\Village::where('sub_area_id', $id)->pluck('name', 'id');
    })->name('villages');
    
    Route::get('/divisions/{district}', function ($id) {
        return \App\Models\Division::where('military_district_id', $id)->pluck('name', 'id');
    })->name('divisions');
});

// Backward compatibility routes
Route::get('/get-directorates/{governorate}', function ($id) {
    return \App\Models\Directorate::where('governorate_id', $id)->pluck('name', 'id');
});
Route::get('/get-sub-areas/{directorate}', function ($id) {
    return \App\Models\SubArea::where('directorate_id', $id)->pluck('name', 'id');
});
Route::get('/get-divisions-by-district/{district}', function ($id) {
    return \App\Models\Division::where('military_district_id', $id)->pluck('name', 'id');
});
Route::get('/api/get-villages', function () {
    return \App\Models\Village::where('sub_area_id', request('sub_area_id'))->pluck('name', 'id');
});


Route::redirect('/blood-types', '/configuration/blood-types');

// Data Management Routes
Route::prefix('data')->name('data.')->group(function () {
    Route::get('/import',                    [DataImportController::class, 'index'])->name('import.index');
    Route::post('/import/upload',            [DataImportController::class, 'import'])->name('import.upload');
    Route::post('/import/store',             [DataImportController::class, 'store'])->name('import.store');
    Route::post('/import/create-table',      [DataImportController::class, 'createTable'])->name('import.create-table');
    Route::post('/import/replace-table',     [DataImportController::class, 'replaceTable'])->name('import.replace-table');
    Route::post('/import/merge',             [DataImportController::class, 'mergeData'])->name('import.merge');
    Route::post('/import/insert-row',        [DataImportController::class, 'insertRow'])->name('import.insert-row');
    Route::post('/import/delete-rows',       [DataImportController::class, 'deleteRows'])->name('import.delete-rows');
    Route::post('/import/insert-column',     [DataImportController::class, 'insertColumn'])->name('import.insert-column');
    Route::post('/import/delete-column',     [DataImportController::class, 'deleteColumn'])->name('import.delete-column');
    Route::post('/import/rename-column',     [DataImportController::class, 'renameColumn'])->name('import.rename-column');
    Route::post('/import/number-records',    [DataImportController::class, 'numberRecords'])->name('import.number-records');
    Route::post('/import/remove-spaces',     [DataImportController::class, 'removeSpaces'])->name('import.remove-spaces');
    Route::post('/import/replace-text',      [DataImportController::class, 'replaceText'])->name('import.replace-text');
    Route::post('/import/update-cell',       [DataImportController::class, 'updateCell'])->name('import.update-cell');
    Route::get('/import/data',               [DataImportController::class, 'getData'])->name('import.get-data');
    Route::get('/import/export',             [DataImportController::class, 'export'])->name('import.export');
    Route::get('/differentiation',                            [DataDifferentiationController::class, 'index'])->name('differentiation.index');
    Route::post('/differentiation/analyze',                   [DataDifferentiationController::class, 'analyze'])->name('differentiation.analyze');
    Route::get('/differentiation/results',                    [DataDifferentiationController::class, 'getResults'])->name('differentiation.results');
    Route::get('/differentiation/detail',                     [DataDifferentiationController::class, 'getMatchDetail'])->name('differentiation.detail');
    Route::post('/differentiation/integrate-all',             [DataDifferentiationController::class, 'integrateAll'])->name('differentiation.integrate-all');
    Route::post('/differentiation/integrate-selected',        [DataDifferentiationController::class, 'integrateSelected'])->name('differentiation.integrate-selected');
    Route::post('/differentiation/add-column',                [DataDifferentiationController::class, 'addSecondaryColumn'])->name('differentiation.add-column');
});
// Note: personal-data.fast-entry is registered inside the auth 'system.' group above.