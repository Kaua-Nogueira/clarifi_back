<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\AdminClientController;
use App\Http\Controllers\Api\Admin\AdminContentController;
use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\AdminUploadController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class);
    Route::apiResource('clients', AdminClientController::class)->except('show');
    Route::post('/uploads', AdminUploadController::class);
    Route::apiResource('contents', AdminContentController::class)->parameters(['contents' => 'content']);
});

Route::get('/dashboard', DashboardController::class);
Route::get('/contents', [ContentController::class, 'index']);
Route::get('/contents/{content:slug}', [ContentController::class, 'show']);
Route::post('/contents/{content:slug}/approve', [ContentController::class, 'approve']);
Route::post('/contents/{content:slug}/request-changes', [ContentController::class, 'requestChanges']);
Route::post('/contents/{content:slug}/comments', [CommentController::class, 'store']);
Route::patch('/comments/{comment}/resolve', [CommentController::class, 'resolve']);
