<?php

use App\Http\Controllers\EmployeeAllowedLocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API للتحقق من الموقع عند البصمة من الموبايل
Route::post('/check-location', [EmployeeAllowedLocationController::class, 'checkLocation']);

// ZK Mediator IP Upload Routes
Route::match(['get', 'post'], '/zkt/upload', [\App\Http\Controllers\IpzktController::class, 'uploadFromMediator']);
Route::get('/zkt/ping', [\App\Http\Controllers\IpzktController::class, 'pingMediator']);

// ZK Mediator - User Sync Routes (مزامنة الموظفين من البرنامج لماكينة البصمة)
Route::get('/zkt/users', [\App\Http\Controllers\IpzktController::class, 'getUsersForDevice']);
Route::post('/zkt/users/synced', [\App\Http\Controllers\IpzktController::class, 'confirmUsersSynced']);

// ZK Mediator - Deleted Users Sync Routes (حذف الموظفين من ماكينة البصمة عند حذفهم من البرنامج)
Route::get('/zkt/users/deleted', [\App\Http\Controllers\IpzktController::class, 'getDeletedUsersForDevice']);
Route::post('/zkt/users/deleted/confirm', [\App\Http\Controllers\IpzktController::class, 'confirmDeletedUsersSynced']);
