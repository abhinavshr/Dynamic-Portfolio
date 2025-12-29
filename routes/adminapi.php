<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\admin\AdminAuthController;
use App\Http\Controllers\api\admin\CategoryController;
use App\Http\Controllers\api\admin\CertificateController;
use App\Http\Controllers\api\admin\ContactController;
use App\Http\Controllers\api\admin\EducationController;
use App\Http\Controllers\api\admin\ExperienceController;
use App\Http\Controllers\api\admin\ProjectController;
use App\Http\Controllers\api\admin\ProjectImageController;
use App\Http\Controllers\api\admin\SkillController;
use App\Http\Controllers\api\admin\SoftSkillController;

// Public Admin Routes
Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
});

// Protected Admin Routes
Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Categories
     Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories');

    // Projects
    Route::post('projects', [ProjectController::class, 'addProject'])->name('admin.addproject');
    Route::get('projects', [ProjectController::class, 'viewAllProjects'])->name('admin.viewprojects');
    Route::put('projects/{id}', [ProjectController::class, 'updateProject'])->name('admin.updateproject');
    Route::get('projects/{id}', [ProjectController::class, 'viewProjectById'])->name('admin.viewproject');
    Route::delete('projects/{id}', [ProjectController::class, 'deleteProject'])->name('admin.deleteproject');

    // Project Images
    Route::post('project-images', [ProjectImageController::class, 'storeProjectImage'])->name('admin.addprojectimage');
    Route::get('project-images', [ProjectImageController::class, 'projectImageView'])->name('admin.viewprojectimages');
    Route::get('/projects-dropdown', [ProjectController::class, 'projectDropdown'])->name('admin.projectsdropdown');
    Route::post('project-images/{id}', [ProjectImageController::class, 'updateProjectImage'])->name('admin.updateprojectimage');
    Route::delete('project-images/{id}', [ProjectImageController::class, 'deleteProjectImage'])->name('admin.deleteprojectimage');

    // Skills
    Route::post('skills', [SkillController::class, 'storeSkill'])->name('admin.addskill');
    Route::get('skills', [SkillController::class, 'viewAllSkills'])->name('admin.viewskills');
    Route::put('skills/{id}', [SkillController::class, 'updateSkill'])->name('admin.updateskill');
    Route::delete('skills/{id}', [SkillController::class, 'deleteSkill'])->name('admin.deleteskill');

    // Education
    Route::post('educations', [EducationController::class, 'storeEducation'])->name('admin.addeducation');
    Route::get('educations', [EducationController::class, 'viewAllEducations'])->name('admin.vieweducations');
    Route::get('educations/{id}', [EducationController::class, 'viewEducation'])->name('admin.vieweducation');
    Route::put('educations/{id}', [EducationController::class, 'updateEducation'])->name('admin.updateeducation');
    Route::delete('educations/{id}', [EducationController::class, 'deleteEducation'])->name('admin.deleteeducation');

    // Experience
    Route::post('experiences', [ExperienceController::class, 'storeExperience'])->name('admin.addexperience');
    Route::get('experiences', [ExperienceController::class, 'viewAllExperiences'])->name('admin.viewexperiences');
    Route::get('experiences/{id}', [ExperienceController::class, 'viewOneByOneExperience'])->name('admin.viewexperience');
    Route::put('experiences/{id}', [ExperienceController::class, 'updateExperience'])->name('admin.updateexperience');
    Route::delete('experiences/{id}', [ExperienceController::class, 'deleteExperience'])->name('admin.deleteexperience');

    // Certificates
    Route::post('certificates', [CertificateController::class, 'storeCertificate'])->name('admin.addcertificate');
    Route::get('certificates', [CertificateController::class, 'viewAllCertificates'])->name('admin.viewcertificates');
    Route::get('certificates/{id}', [CertificateController::class, 'viewOneByOneCertificate'])->name('admin.viewcertificate');
    Route::post('certificates/{id}', [CertificateController::class, 'updateCertificate'])->name('admin.updatecertificate');
    Route::delete('certificates/{id}', [CertificateController::class, 'deleteCertificate'])->name('admin.deletecertificate');

    // Soft Skills
    Route::post('soft-skills', [SoftSkillController::class, 'storeSoftSkill'])->name('admin.addsoftskill');
    Route::get('soft-skills', [SoftSkillController::class, 'viewAllSoftSkill'])->name('admin.viewsoftskills');
    Route::get('soft-skills/{id}', [SoftSkillController::class, 'viewOneByOneSoftSkill'])->name('admin.viewsoftskill');
    Route::put('soft-skills/{id}', [SoftSkillController::class, 'updateSoftSkill'])->name('admin.updatesoftskill');
    Route::delete('soft-skills/{id}', [SoftSkillController::class, 'deleteSoftSkill'])->name('admin.deletesoftskill');

    // Contact Messages
    Route::get('contacts', [ContactController::class, 'viewAllContacts'])->name('admin.viewcontacts');
    Route::get('contacts/{id}', [ContactController::class, 'viewOneContact'])->name('admin.viewcontact');
});
