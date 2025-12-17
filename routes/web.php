<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\OrganizationController;


Route::get('/', function () {
    return view('welcome');
});

// Route publique accessible sans authentification (Critère d'acceptation)
Route::get('/survey/{token}', [SurveyController::class, 'showByToken'])->name('surveys.public_show');

// Route temporaire pour générer les tokens manquants (A SUPPRIMER APRES USAGE)
Route::get('/fix-tokens', function () {
    $surveys = \App\Models\Survey::whereNull('token')->orWhere('token', '')->get();
    foreach ($surveys as $survey) {
        $survey->update(['token' => \Illuminate\Support\Str::random(64)]);
    }
    return count($surveys) . ' sondages ont été mis à jour avec un token.';
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('organizations', OrganizationController::class);
    Route::post('/organizations/{organization}/invite', [OrganizationController::class, 'invite'])->name('organizations.invite');
    Route::post('/organizations/{organization}/switch', [OrganizationController::class, 'switchOrganization'])->name('organizations.switch');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');


    Route::resource('surveys', SurveyController::class); 
});

require __DIR__ . '/auth.php';
