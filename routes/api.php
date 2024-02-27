<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;

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

Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('my-surveys', [SurveyController::class, 'mySurveys']);
    Route::post('create-survey', [SurveyController::class, 'createSurvey']);
    Route::post('add-page/{survey_id}', [SurveyController::class, 'addPage']);
    Route::post('add-element/{page_id}', [SurveyController::class, 'addElement']);
    Route::post('add-choice/{element_id}', [SurveyController::class, 'addChoice']);
    Route::get('get-survey-with-pages/{survey_id}', [SurveyController::class, 'getSurveyWithPages']);
    Route::get('get-page-with-elements/{page_id}', [SurveyController::class, 'getPageWithElements']);
    Route::get('get-element-with-choices/{element_id}', [SurveyController::class, 'getElementWithChoices']);
    Route::get('get-survey/{slug}', [SurveyController::class, 'getSurvey']);
    Route::get('auth/logout', [AuthController::class, 'logout']);
});
