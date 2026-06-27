<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Tools\HomeController;
use App\Http\Controllers\Tools\GstController;
use App\Http\Controllers\Tools\ToolController;
use App\Http\Controllers\Tools\EmiController;
use App\Http\Controllers\Tools\AgeController;
use App\Http\Controllers\Tools\PercentageController;
use App\Http\Controllers\Tools\DiscountController;
use App\Http\Controllers\Tools\InterestController;
use App\Http\Controllers\Tools\PasswordController;
use App\Http\Controllers\Tools\UnitController;
use App\Http\Controllers\Tools\QrController;
use App\Http\Controllers\Tools\TextController;
use App\Http\Controllers\Tools\FinanceCalculatorController;

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

Route::get('/', [HomeController::class, 'index']);
Route::post('/', fn () => redirect('/'));
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('robots');
Route::get('/search', [SiteController::class, 'search'])->name('search');
Route::get('/{page}', [SiteController::class, 'page'])
    ->whereIn('page', ['about', 'contact', 'privacy-policy', 'terms', 'disclaimer'])
    ->name('page.show');
Route::get('/category/{category}', [SiteController::class, 'category'])->name('category.show');
Route::get('/tools/gst-calculator', [GstController::class, 'index'])->name('gst.form');
Route::post('/tools/gst-calculator', [GstController::class, 'calculate'])->name('gst.calculate');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/emi-calculator', [EmiController::class, 'calculate'])->name('emi.calculate');
Route::post('/tools/age-calculator', [AgeController::class, 'calculate'])->name('age.calculate');
Route::post('/tools/percentage-calculator', [PercentageController::class, 'calculate'])->name('percentage.calculate');
Route::post('/tools/discount-calculator', [DiscountController::class, 'calculate'])->name('discount.calculate');
Route::post('/tools/simple-interest-calculator', [InterestController::class, 'calculate'])->name('interest.calculate');
Route::post('/tools/password-generator', [PasswordController::class, 'generate'])->name('password.generate');
Route::post('/tools/unit-converter', [UnitController::class, 'convert'])->name('unit.convert');
Route::post('/tools/qr-generator', [QrController::class, 'generate'])->name('qr.generate');
Route::post('/tools/text-case-converter', [TextController::class, 'convert'])->name('text.convert');
Route::post('/tools/{slug}', [FinanceCalculatorController::class, 'calculate'])->name('finance.calculate');
