<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApproversController;
use App\Http\Controllers\Api\ApprovalStagesController;
use App\Http\Controllers\Api\ExpensesController;


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

Route::post('/approvers', [ApproversController::class, 'store']);
Route::post('/approval-stages', [ApprovalStagesController::class, 'store']);
Route::put('/approval-stages/{id}', [ApprovalStagesController::class, 'update']);
Route::post('/expense', [ExpensesController::class, 'store']);
Route::get('/expense', [ExpensesController::class, 'list']);
Route::get('/expense/{id}', [ExpensesController::class, 'show']);
Route::patch('/expense/{id}/approve', [ExpensesController::class, 'approve']);