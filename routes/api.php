<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\BankAccountController;
use App\Http\Controllers\V1\BusinessOnboardingController;
use App\Http\Controllers\V1\ForgotPasswordController;
use App\Http\Controllers\V1\GoogleAuthController;
use App\Http\Controllers\V1\JobController;
use App\Http\Controllers\V1\ProfileController;
use App\Http\Controllers\v1\ResetPasswordController;
use App\Http\Controllers\V1\SkillsController;
use App\Http\Controllers\V1\TalentController;
use App\Http\Controllers\V1\TalentJobsController;
use App\Http\Controllers\V1\TalentOnboardingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/talent-register', [AuthController::class, 'talentRegister']);
Route::post('v1/business-register', [AuthController::class, 'businessRegister']);

Route::post('v1/forgot-password', [ForgotPasswordController::class, 'forgot']);
Route::post('v1/reset-password', [ResetPasswordController::class, 'reset']);

Route::get('/verify/{token}', [AuthController::class, 'verify'])->name('verification.verify');
Route::get('/business-verify/{token}', [AuthController::class, 'verifys'])->name('verification.verifys');

Route::get('/auth/talent/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/talent/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// Route::get('/auth/business/google', [GoogleAuthController::class, 'redirectToGoogleBusiness']);
// Route::get('/auth/business/google/callback', [GoogleAuthController::class, 'handleGoogleCallbackBusiness']);

Route::resource('v1/skills', SkillsController::class);
Route::get('v1/list-jobs', [TalentJobsController::class, 'listjobs']);

Route::get('v1/talents', [TalentController::class, 'listtalents']);
Route::get('v1/talent/{uuid}', [TalentController::class, 'talentbyid']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    // Talent
    Route::post('v1/talent-work-details', [TalentOnboardingController::class, 'workDetails']);
    Route::post('v1/talent-portfolio', [TalentOnboardingController::class, 'portfolio']);
    Route::patch('v1/talent-edit-profile', [TalentOnboardingController::class, 'editProfile']);
    Route::get('v1/get-jobs', [TalentJobsController::class, 'jobs']);
    Route::post('v1/job-apply/{id}', [TalentJobsController::class, 'apply']);

    Route::get('v1/bank-list', [BankAccountController::class, 'banks']);
    Route::post('v1/add-bank-account', [BankAccountController::class, 'add']);
    Route::post('v1/withdrawal-pin', [BankAccountController::class, 'pin']);
    Route::post('v1/verify-pin', [BankAccountController::class, 'verify']);





    // Business
    Route::post('v1/business-details', [BusinessOnboardingController::class, 'businessDetails']);
    Route::post('v1/business-portfolio', [BusinessOnboardingController::class, 'portfolio']);
    Route::patch('v1/business-edit-profile', [BusinessOnboardingController::class, 'editProfile']);
    Route::resource('v1/job', JobController::class);


    Route::get('v1/profile', [ProfileController::class, 'profile']);

    Route::post('v1/logout', [AuthController::class, 'logout']);
});
