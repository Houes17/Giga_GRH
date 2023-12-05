<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadPdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => redirect("/admin"));

Route::get('/{record}/pdf/download', [DownloadPdfController::class, 'download'])->name('salary.pdf.download');
