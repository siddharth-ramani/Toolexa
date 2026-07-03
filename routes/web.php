<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Discover\DiscoverController;
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
use App\Http\Controllers\Tools\TextUtilityController;
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
Route::get('/ads.txt', [SiteController::class, 'ads'])->name('ads');
Route::get('/search', [SiteController::class, 'search'])->name('search');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/discover', [DiscoverController::class, 'create'])->name('discover.index');
Route::post('/discover', [DiscoverController::class, 'storeDefault'])->name('discover.store.default');
Route::get('/discover/{slug}/demo', [DiscoverController::class, 'demo'])->where('slug', '[a-z0-9-]+')->name('discover.feature.demo');
Route::get('/discover/{slug}', [DiscoverController::class, 'feature'])->where('slug', 'how-people-see-you')->name('discover.feature.create');
Route::post('/discover/{slug}', [DiscoverController::class, 'store'])->where('slug', 'how-people-see-you')->name('discover.store');
Route::get('/discover/{id}', [DiscoverController::class, 'show'])->whereAlphaNumeric('id')->name('discover.show');
Route::post('/discover/{id}', [DiscoverController::class, 'submit'])->whereAlphaNumeric('id')->name('discover.submit');
Route::get('/discover/{id}/thanks', [DiscoverController::class, 'thanks'])->whereAlphaNumeric('id')->name('discover.thanks');
Route::get('/discover/{id}/photo', [DiscoverController::class, 'photo'])->whereAlphaNumeric('id')->name('discover.photo');
Route::get('/discover/{id}/share/{token}', [DiscoverController::class, 'share'])->whereAlphaNumeric('id')->whereAlphaNumeric('token')->name('discover.share');
Route::get('/discover/{id}/results', [DiscoverController::class, 'publicResults'])->whereAlphaNumeric('id')->name('discover.public-results');
Route::get('/discover/{id}/results/{token}', [DiscoverController::class, 'results'])->whereAlphaNumeric('id')->whereAlphaNumeric('token')->name('discover.results');
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
foreach (['word-counter', 'character-counter', 'remove-duplicate-lines', 'remove-extra-spaces', 'text-repeater', 'base64-encoder', 'base64-decoder', 'url-encoder-decoder', 'md5-hash-generator', 'lorem-ipsum-generator'] as $textToolSlug) {
    Route::post('/tools/'.$textToolSlug, [TextUtilityController::class, 'process'])
        ->defaults('slug', $textToolSlug)
        ->name('text-tools.'.$textToolSlug);
}
Route::post('/tools/{slug}', [FinanceCalculatorController::class, 'calculate'])->name('finance.calculate');
