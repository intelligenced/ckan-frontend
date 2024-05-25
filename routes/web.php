<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontpageController;
use App\Http\Controllers\SeedController;

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontpageController::class, 'index'])->name('frontpage.index');
Route::get('/explore', [FrontpageController::class, 'explore'])->name('frontpage.explore');
Route::get('/data/{id}', [FrontpageController::class, 'data'])->name('frontpage.data');

Route::get('/seeddGroups',[SeedController::class, 'seedGroups'])->name('seed.groups');
Route::get('/seedDatasets',[SeedController::class, 'seedDatasets'])->name('seed.datasets');
Route::get('/deseedDatasets',[SeedController::class, 'deseedDatasets'])->name('seed.delete.datasets');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
