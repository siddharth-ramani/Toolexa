<?php

use App\Http\Controllers\Admin\AdSenseValidatorController;
use App\Http\Controllers\Admin\SiteAuditController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Tools\AgeController;
use App\Http\Controllers\Tools\DiscountController;
use App\Http\Controllers\Tools\EmiController;
use App\Http\Controllers\Tools\FinanceCalculatorController;
use App\Http\Controllers\Tools\GstController;
use App\Http\Controllers\Tools\HomeController;
use App\Http\Controllers\Tools\InterestController;
use App\Http\Controllers\Tools\PasswordController;
use App\Http\Controllers\Tools\PercentageController;
use App\Http\Controllers\Tools\QrController;
use App\Http\Controllers\Tools\TextController;
use App\Http\Controllers\Tools\TextUtilityController;
use App\Http\Controllers\Tools\ToolController;
use App\Http\Controllers\Tools\UnitController;
use App\Http\Controllers\TopicHubController;
use App\Http\Controllers\TrustController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Middleware\EnsureSiteAuditAdmin;
use Illuminate\Support\Facades\Route;

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
Route::post('/', [HomeController::class, 'redirectHome']);
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('robots');
Route::get('/ads.txt', [SiteController::class, 'ads'])->name('ads');
Route::get('/search', [SiteController::class, 'search'])->name('search');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/workspace', [WorkspaceController::class, 'index'])->name('workspace');
Route::get('/trust', [TrustController::class, 'show'])->defaults('page', 'trust')->name('trust.trust');
Route::get('/editorial-policy', [TrustController::class, 'show'])->defaults('page', 'editorial-policy')->name('trust.editorial-policy');
Route::get('/accuracy-policy', [TrustController::class, 'show'])->defaults('page', 'accuracy-policy')->name('trust.accuracy-policy');
Route::get('/how-we-test-tools', [TrustController::class, 'show'])->defaults('page', 'how-we-test-tools')->name('trust.how-we-test-tools');
Route::get('/authors/{slug}', [AuthorController::class, 'show'])->name('authors.show');
Route::get('/api/search', [SiteController::class, 'searchApi'])->name('search.api')->middleware('throttle:120,1');
Route::prefix('admin/site-audit')->middleware(EnsureSiteAuditAdmin::class)->group(function () {
    Route::get('/', [SiteAuditController::class, 'index'])->name('admin.site-audit.index');
    Route::post('/refresh', [SiteAuditController::class, 'refresh'])->name('admin.site-audit.refresh');
    Route::get('/export/{format}', [SiteAuditController::class, 'export'])->name('admin.site-audit.export');
});
Route::prefix('admin/adsense-validator')->middleware(EnsureSiteAuditAdmin::class)->group(function () {
    Route::get('/', [AdSenseValidatorController::class, 'index'])->name('admin.adsense-validator.index');
    Route::post('/refresh', [AdSenseValidatorController::class, 'refresh'])->name('admin.adsense-validator.refresh');
    Route::get('/export/{format}', [AdSenseValidatorController::class, 'export'])->name('admin.adsense-validator.export');
});
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/compare', [ComparisonController::class, 'index'])->name('compare.index');
Route::get('/compare/{slug}', [ComparisonController::class, 'show'])->name('compare.show');
Route::get('/topics', [TopicHubController::class, 'index'])->name('hub.index');
Route::get('/{hub}', [TopicHubController::class, 'show'])
    ->whereIn('hub', array_keys(config('hubs.topics', [])))
    ->name('hub.show');
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
