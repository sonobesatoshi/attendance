<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;
// use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index']);
Route::get('/', [RestController::class, 'index']);
Route::middleware('auth')->group(function () {
        Route::get('/', [AuthController::class, 'index']);
    });
Route::post('/attendance/start', [AttendanceController::class, 'startAttendance'])->name('attendance.start');
Route::post('/attendance/end', [AttendanceController::class, 'endAttendance'])->name('attendance.end');
Route::post('/break/start', [RestController::class, 'startRest'])->name('rest.start');
Route::post('/break/end', [RestController::class, 'endRest'])->name('rest.end');
Route::get('/attendance', [AttendanceController::class, 'pagination'])->name('attendance.pagination');
Auth::routes(['register' => VerifiedUser::class, 'verify' => true]);

