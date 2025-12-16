<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('organizations', \App\Http\Controllers\OrganizationController::class);
    Route::post('/organizations/{organization}/invite', [\App\Http\Controllers\OrganizationController::class, 'invite'])->name('organizations.invite');
    Route::post('/organizations/{organization}/switch', [\App\Http\Controllers\OrganizationController::class, 'switchOrganization'])->name('organizations.switch');

    Route::get('/surveys', [\App\Http\Controllers\SurveyController::class, 'index'])->name('surveys.index');
    Route::post('/survey/create', [\App\Http\Controllers\SurveyController::class, 'create'] )->name('organizations.create');
    Route::post('/survey/modify');
    Route::post('/survey/delete', );
});

require __DIR__.'/auth.php';
