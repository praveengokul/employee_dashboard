<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/view-employees', [EmployeeController::class, 'index'])->name('employees.index');

    Route::middleware('role:admin')->group(function () {
        Route::post('/store-employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/delete-employee/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // Route::middleware('role:user')->group(function () {
    //     Route::get('/employees', [EmployeeController::class, 'index']);
    // });
});
