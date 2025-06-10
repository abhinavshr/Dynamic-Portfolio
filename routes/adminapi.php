<?php

use App\Http\Controllers\api\admin\AdminAuthController;
use App\Http\Controllers\api\admin\ProjectController;
use App\Http\Controllers\api\admin\ProjectImageController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::post('addproject', [ProjectController::class, 'addProject'])->name('admin.addproject');
    Route::get('viewprojects', [ProjectController::class, 'viewAllProjects'])->name('admin.viewprojects');
    Route::put('updateproject/{id}', [ProjectController::class, 'updateProject'])->name('admin.updateproject');
    Route::delete('deleteproject/{id}', [ProjectController::class, 'deleteProject'])->name('admin.deleteproject');
    Route::post('projectimage', [ProjectImageController::class, 'addProjectImage'])->name('admin.projectimage');
    Route::get('viewprojectimages', [ProjectImageController::class, 'projectImageView'])->name('admin.viewprojectimages');
    Route::post('updateprojectimage/{id}', [ProjectImageController::class, 'updateProjectImage'])->name('admin.updateprojectimage');
    Route::delete('deleteprojectimage/{id}', [ProjectImageController::class, 'deleteProjectImage'])->name('admin.deleteprojectimage');
});
