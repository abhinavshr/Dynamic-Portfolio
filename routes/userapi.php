<?php

use App\Http\Controllers\api\user\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('/contact', [ContactController::class, 'storeContact'])->name('contact');

