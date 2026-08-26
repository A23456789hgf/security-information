<?php

use Illuminate\Support\Facades\Route;
use Modules\Differences\Controllers\DifferencesController;

Route::middleware(['web', 'auth'])->prefix('data/differences')->name('data.differences.')->group(function () {
    Route::get('/', [DifferencesController::class, 'index'])->name('index');
    Route::get('/seed-mock-data', [DifferencesController::class, 'seedMockData'])->name('seed-mock-data');
    Route::post('/store', [DifferencesController::class, 'store'])->name('store');
    Route::get('/settings/view', [DifferencesController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [DifferencesController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/add-target', [DifferencesController::class, 'addTarget'])->name('settings.add-target');
    Route::post('/settings/toggle-target/{target}', [DifferencesController::class, 'toggleTarget'])->name('settings.toggle-target');
    
    Route::get('/{analysis}', [DifferencesController::class, 'show'])->name('show');
    Route::get('/{analysis}/progress', [DifferencesController::class, 'progress'])->name('progress');
    Route::get('/result/{result}/detail', [DifferencesController::class, 'detail'])->name('detail');
    Route::get('/{analysis}/export-excel', [DifferencesController::class, 'exportExcel'])->name('export-excel');
    Route::get('/{analysis}/print-pdf', [DifferencesController::class, 'printPdf'])->name('print-pdf');
});
