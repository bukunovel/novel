<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuNovelController;

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

Route::get('/buku-novel', [BukuNovelController::class, 'index']);
Route::post('/buku-novel', [BukuNovelController::class, 'store']);
Route::get('/buku-novel/{bukuNovel}', [BukuNovelController::class, 'show']);
Route::post('/buku-novel/{bukuNovel}', [BukuNovelController::class, 'update']);
Route::delete('/buku-novel/{bukuNovel}', [BukuNovelController::class, 'destroy']);
Route::get('/buku-novel-total-novel', [BukuNovelController::class, 'showByTotalNovel']);
Route::get('/buku-novel-total-view', [BukuNovelController::class, 'showByTotalView']);
Route::get('/buku-novel-hot', [BukuNovelController::class, 'indexDescending']);

