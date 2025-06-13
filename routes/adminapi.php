<?php

use App\Http\Controllers\api\admin\AdminAuthController;
use App\Http\Controllers\api\admin\CertificateController;
use App\Http\Controllers\api\admin\ContactController;
use App\Http\Controllers\api\admin\EducationController;
use App\Http\Controllers\api\admin\ExperienceController;
use App\Http\Controllers\api\admin\ProjectController;
use App\Http\Controllers\api\admin\ProjectImageController;
use App\Http\Controllers\api\admin\SkillController;
use App\Http\Controllers\api\admin\SoftSkillController;
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
    Route::post('addskill', [SkillController::class, 'storeSkill'])->name('admin.addskill');
    Route::get('viewskills', [SkillController::class, 'viewAllSkills'])->name('admin.viewskills');
    Route::put('updateskill/{id}', [SkillController::class, 'updateSkill'])->name('admin.updateskill');
    Route::delete('deleteskill/{id}', [SkillController::class, 'deleteSkill'])->name('admin.deleteskill');
    Route::post('addeducation', [EducationController::class, 'storeEducation'])->name('admin.addeducation');
    Route::get('vieweducations', [EducationController::class, 'viewAllEducations'])->name('admin.vieweducations');
    Route::put('updateeducation/{id}', [EducationController::class, 'updateEducation'])->name('admin.updateeducation');
    Route::delete('deleteeducation/{id}', [EducationController::class, 'deleteEducation'])->name('admin.deleteeducation');
    Route::get('vieweducation/{id}', [EducationController::class, 'viewEducation'])->name('admin.vieweducation');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::post('addexperience', [ExperienceController::class, 'storeExperience'])->name('admin.addexperience');
    Route::get('viewexperiences', [ExperienceController::class, 'viewAllExperiences'])->name('admin.viewexperiences');
    Route::put('updateexperience/{id}', [ExperienceController::class, 'updateExperience'])->name('admin.updateexperience');
    Route::delete('deleteexperience/{id}', [ExperienceController::class, 'deleteExperience'])->name('admin.deleteexperience');
    Route::get('viewonebyoneexperience/{id}', [ExperienceController::class, 'viewOneByOneExperience'])->name('admin.viewonebyoneexperience');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::post('addcertificate', [CertificateController::class, 'storeCertificate'])->name('admin.addcertificate');
    Route::get('viewcertificates', [CertificateController::class, 'viewAllCertificates'])->name('admin.viewcertificates');
    Route::get('viewonebyonecertificate/{id}', [CertificateController::class, 'viewOneByOneCertificate'])->name('admin.viewonebyonecertificate');
    Route::post('updatecertificate/{id}', [CertificateController::class, 'updateCertificate'])->name('admin.updatecertificate');
    Route::delete('deletecertificate/{id}', [CertificateController::class, 'deleteCertificate'])->name('admin.deletecertificate');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::post('addsoftskill', [SoftSkillController::class, 'storeSoftSkill'])->name('admin.addsoftskill');
    Route::get('viewsoftskills', [SoftSkillController::class, 'viewAllSoftSkill'])->name('admin.viewsoftskills');
    Route::get('viewonebyonesoftskill/{id}', [SoftSkillController::class, 'viewOneByOneSoftSkill'])->name('admin.viewonebyonesoftskill');
    Route::put('updatesoftskill/{id}', [SoftSkillController::class, 'updateSoftSkill'])->name('admin.updatesoftskill');
    Route::delete('deletesoftskill/{id}', [SoftSkillController::class, 'deleteSoftSkill'])->name('admin.deletesoftskill');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('viewcontact', [ContactController::class, 'viewAllContacts'])->name('admin.viewcontact');
    Route::get('viewonebyonecontact/{id}', [ContactController::class, 'viewOneContact'])->name('admin.viewonebyonecontact');
});
