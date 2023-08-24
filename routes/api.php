<?php

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

use App\Http\Controllers\CommentController;
use App\Http\Controllers\AnswerController;

Route::get('/comments', [CommentController::class, 'index']);
Route::post('/comments', [CommentController::class, 'store'])->middleware('checkRecaptchaToken');

Route::get('/comments/{comment}/answers', [AnswerController::class, 'index']);
Route::post('/comments/{comment}/answers', [AnswerController::class, 'store'])->middleware('checkRecaptchaToken');

Route::get('/comments/{comment}/answers/{answer}/', [AnswerController::class, 'getChildAnswers']);
Route::post('/comments/{comment}/answers/{answer}/', [AnswerController::class, 'storeChildAnswer'])->middleware('checkRecaptchaToken');
