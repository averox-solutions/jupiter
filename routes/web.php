<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

if (file_exists(storage_path('installed'))) {
    Auth::routes([
        'register' => getSetting('REGISTRATION') == 'enabled',
        'verify' => getSetting('VERIFY_USERS') == 'enabled'
    ]);
} else {
    Auth::routes();
}

//home route
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['tfa'])->prefix('profile')->group(function () {
    Route::get('/info', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.profile');
    Route::post('/info', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.profile.update');

    Route::get('/security', [App\Http\Controllers\ProfileController::class, 'security'])->name('profile.security');
    Route::post('/security', [App\Http\Controllers\ProfileController::class, 'updateSecurity']);

    Route::get('/plan', [App\Http\Controllers\ProfileController::class, 'myPlan'])->name('profile.plan');
    Route::post('/cancel-plan', [App\Http\Controllers\ProfileController::class, 'cancelPlan'])->name('cancelPlan');

    Route::get('/payments', [App\Http\Controllers\ProfileController::class, 'payments'])->name('profile.payments');
    Route::get('/api', [App\Http\Controllers\ProfileController::class, 'api'])->name('profile.api');

    //contact list routes and URLs
    Route::get('/contact', [App\Http\Controllers\ProfileController::class, 'contacts'])->name('profile.contacts');
    Route::get('/contact/create', [App\Http\Controllers\ProfileController::class, 'contactForm'])->name('profile.createContactForm');
    Route::get('/contact/edit/{id}', [App\Http\Controllers\ProfileController::class, 'editContactForm'])->name('profile.editContactForm');
    Route::post('/contact/create', [App\Http\Controllers\ProfileController::class, 'createContact'])->name('profile.createContact');
    Route::post('/contact/edit/{id}', [App\Http\Controllers\ProfileController::class, 'editContact'])->name('profile.editContact');
    Route::post('/contact/delete', [App\Http\Controllers\ProfileController::class, 'deleteContact'])->name('profile.deleteContact');
    Route::get('/contact/import', [App\Http\Controllers\ProfileController::class, 'contactImportForm'])->name('profile.importContactForm');
    Route::get('/contact/download', [App\Http\Controllers\ProfileController::class, 'downloadCsvFile'])->name('profile.downloadCsvFile');
    Route::post('/contact/import-contact', [App\Http\Controllers\ProfileController::class, 'importContact'])->name('profile.importContact');
    Route::get('/tfa', [App\Http\Controllers\ProfileController::class, 'tfa'])->name('profile.tfa');
    Route::post('/tfa', [App\Http\Controllers\ProfileController::class, 'updateTfa'])->name('profile.updateTfa');
});

//check if auth mode is enabled
Route::middleware('checkAuthMode')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

//admin routes
Route::middleware(['tfa', 'auth', 'checkAdmin'])->group(function () {
    Route::get('admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
    Route::get('update', [App\Http\Controllers\AdminController::class, 'update'])->name('update');
    Route::get('check-for-update', [App\Http\Controllers\AdminController::class, 'checkForUpdate']);
    Route::get('download-update', [App\Http\Controllers\AdminController::class, 'downloadUpdate']);
    Route::get('license', [App\Http\Controllers\AdminController::class, 'license'])->name('license');
    Route::get('verify-license', [App\Http\Controllers\AdminController::class, 'verifyLicense']);
    Route::get('uninstall-license', [App\Http\Controllers\AdminController::class, 'uninstallLicense']);
    Route::get('signaling', [App\Http\Controllers\AdminController::class, 'signaling'])->name('signaling');
    Route::get('check-signaling', [App\Http\Controllers\AdminController::class, 'checkSignaling']);

    //meeting routes
    Route::get('meetings', [App\Http\Controllers\MeetingController::class, 'index'])->name('meetings');
    Route::post('update-meeting-status', [App\Http\Controllers\MeetingController::class, 'updateMeetingStatus']);
    Route::post('delete-meeting-admin', [App\Http\Controllers\MeetingController::class, 'deleteMeeting']);

    //user routes
    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::post('update-user-status', [App\Http\Controllers\UserController::class, 'updateUserStatus']);
    Route::post('delete-user', [App\Http\Controllers\UserController::class, 'deleteUser']);
    Route::get('users/create', [App\Http\Controllers\UserController::class, 'createUserForm'])->name('createUser');
    Route::post('create-user', [App\Http\Controllers\UserController::class, 'createUser'])->name('storeUser');

    //global config routes
    Route::get('global-config', [App\Http\Controllers\GlobalConfigController::class, 'index'])->name('global-config');
    Route::get('global-config/edit/{id}', [App\Http\Controllers\GlobalConfigController::class, 'edit']);
    Route::post('update-global-config', [App\Http\Controllers\GlobalConfigController::class, 'updateBasic'])->name('basic.update');
    Route::get('global-config/application', [App\Http\Controllers\GlobalConfigController::class, 'application'])->name('global-config.application');
    Route::post('global-config/application', [App\Http\Controllers\GlobalConfigController::class, 'updateApplication'])->name('application.update');
    Route::get('global-config/meeting', [App\Http\Controllers\GlobalConfigController::class, 'meeting'])->name('global-config.meeting');
    Route::post('global-config/meeting', [App\Http\Controllers\GlobalConfigController::class, 'updateMeeting'])->name('meeting.update');
    Route::get('global-config/js', [App\Http\Controllers\GlobalConfigController::class, 'customJs'])->name('global-config.js');
    Route::post('global-config/js', [App\Http\Controllers\GlobalConfigController::class, 'updateJs'])->name('js.update');
    Route::get('global-config/css', [App\Http\Controllers\GlobalConfigController::class, 'customCss'])->name('global-config.css');
    Route::post('global-config/css', [App\Http\Controllers\GlobalConfigController::class, 'updateCss'])->name('css.update');
    Route::get('global-config/smtp', [App\Http\Controllers\GlobalConfigController::class, 'smtp'])->name('global-config.smtp');
    Route::post('global-config/smtp', [App\Http\Controllers\GlobalConfigController::class, 'updateSmtp'])->name('smtp.update');
    Route::get('global-config/api', [App\Http\Controllers\GlobalConfigController::class, 'api'])->name('global-config.api');
    Route::post('global-config/test-smtp', [App\Http\Controllers\GlobalConfigController::class, 'testSmtp'])->name('test.update');
    Route::get('global-config/recaptcha', [App\Http\Controllers\GlobalConfigController::class, 'recaptcha'])->name('global-config.recaptcha');
    Route::post('global-config/recaptcha', [App\Http\Controllers\GlobalConfigController::class, 'updateRecaptcha'])->name('recaptcha.update');

    //content routes
    Route::get('content', [App\Http\Controllers\ContentController::class, 'index'])->name('content');
    Route::get('content/edit/{id}', [App\Http\Controllers\ContentController::class, 'edit']);
    Route::post('update-content', [App\Http\Controllers\ContentController::class, 'update'])->name('updateContent');

    //languages routes
    Route::get('languages', [App\Http\Controllers\LanguagesController::class, 'index'])->name('languages');
    Route::get('languages/add', [App\Http\Controllers\LanguagesController::class, 'create']);
    Route::post('create-language', [App\Http\Controllers\LanguagesController::class, 'createLanguage'])->name('createLanguage');
    Route::get('languages/edit/{id}', [App\Http\Controllers\LanguagesController::class, 'edit']);
    Route::post('update-language/{id}', [App\Http\Controllers\LanguagesController::class, 'updateLanguage'])->name('updateLanguage');
    Route::post('languages/delete', [App\Http\Controllers\LanguagesController::class, 'deleteLanguage']);
    Route::get('languages/download-english', [App\Http\Controllers\LanguagesController::class, 'downloadEnglish']);
    Route::get('languages/download-file/{code}', [App\Http\Controllers\LanguagesController::class, 'downloadFile']);

    //coupons routes
    Route::get('/coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('admin.coupons');
    Route::get('/coupons/new', [App\Http\Controllers\CouponController::class, 'create'])->name('admin.coupons.new');
    Route::get('/coupons/{id}/edit', [App\Http\Controllers\CouponController::class, 'edit'])->name('admin.coupons.edit');
    Route::post('/coupons/new', [App\Http\Controllers\CouponController::class, 'store']);
    Route::post('/coupons/{id}/edit', [App\Http\Controllers\CouponController::class, 'update']);
    Route::post('/update-coupon-status', [App\Http\Controllers\CouponController::class, 'updateStatus']);

    //plans routes
    Route::get('/plans', [App\Http\Controllers\PlanController::class, 'index'])->name('admin.plans');
    Route::get('/plans/new', [App\Http\Controllers\PlanController::class, 'create'])->name('admin.plans.new');
    Route::get('/plans/{id}/edit', [App\Http\Controllers\PlanController::class, 'edit'])->name('admin.plans.edit');
    Route::post('/plans/new', [App\Http\Controllers\PlanController::class, 'store']);
    Route::post('/plans/{id}/edit', [App\Http\Controllers\PlanController::class, 'update']);
    Route::post('/update-plan-status', [App\Http\Controllers\PlanController::class, 'updateStatus']);

    //tax rates routes
    Route::get('/tax-rates', [App\Http\Controllers\TaxRateController::class, 'index'])->name('admin.tax_rates');
    Route::get('/tax-rates/new', [App\Http\Controllers\TaxRateController::class, 'create'])->name('admin.tax_rates.new');
    Route::get('/tax-rates/{id}/edit', [App\Http\Controllers\TaxRateController::class, 'edit'])->name('admin.tax_rates.edit');
    Route::post('/tax-rates/new', [App\Http\Controllers\TaxRateController::class, 'store']);
    Route::post('/tax-rates/{id}/edit', [App\Http\Controllers\TaxRateController::class, 'update']);
    Route::post('/update-tax-rates-status', [App\Http\Controllers\TaxRateController::class, 'updateStatus']);

    //payment process routes
    Route::get('/payment-gateways', [App\Http\Controllers\GlobalConfigController::class, 'paymentGateways'])->name('admin.payment_gateways');
    Route::post('/payment-gateways', [App\Http\Controllers\GlobalConfigController::class, 'updatePaymentGateways']);

    //pages admin routes
    Route::get('/pages', 'PageController@index')->name('pages');
    Route::get('pages/add', [App\Http\Controllers\PageController::class, 'create']);
    Route::post('create-page', [App\Http\Controllers\PageController::class, 'createPage'])->name('createPage');
    Route::get('pages/edit/{id}', [App\Http\Controllers\PageController::class, 'edit']);
    Route::post('update-page/{id}', [App\Http\Controllers\PageController::class, 'updatePage'])->name('updatePage');
    Route::post('pages/delete', [App\Http\Controllers\PageController::class, 'deletePage']);

    //payment listing
    Route::get('transaction', [App\Http\Controllers\TransactionController::class, 'index'])->name('admin.transaction');
});

//checkout routes
Route::middleware(['auth', 'checkPaymentMode', 'tfa'])->prefix('checkout')->group(function () {
    Route::get('/cancelled', [App\Http\Controllers\CheckoutController::class, 'cancelled'])->name('checkout.cancelled');
    Route::get('/pending', [App\Http\Controllers\CheckoutController::class, 'pending'])->name('checkout.pending');
    Route::get('/complete', [App\Http\Controllers\CheckoutController::class, 'complete'])->name('checkout.complete');

    Route::get('/{id}', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/{id}', [App\Http\Controllers\CheckoutController::class, 'process']);
});

//general routes
Route::post('create-meeting', [App\Http\Controllers\DashboardController::class, 'createMeeting']);
Route::post('delete-meeting', [App\Http\Controllers\DashboardController::class, 'deleteMeeting']);
Route::post('edit-meeting', [App\Http\Controllers\DashboardController::class, 'editMeeting']);
Route::post('send-invite', [App\Http\Controllers\DashboardController::class, 'sendInvite']);
Route::get('get-invites', [App\Http\Controllers\DashboardController::class, 'getInvites']);
Route::get('meeting/{id}', [App\Http\Controllers\DashboardController::class, 'meeting']);
Route::get('widget', [App\Http\Controllers\DashboardController::class, 'widget']);
Route::post('check-meeting', [App\Http\Controllers\DashboardController::class, 'checkMeeting']);
Route::post('check-meeting-password', [App\Http\Controllers\DashboardController::class, 'checkMeetingPassword']);
Route::post('get-details', [App\Http\Controllers\DashboardController::class, 'getDetails']);
Route::get('languages/{locale}', [App\Http\Controllers\DashboardController::class, 'setLocale'])->name('language');
Route::get('instant/{id}/{date}', [App\Http\Controllers\DashboardController::class, 'instant'])->name('instant');
Route::get('/check-details', [App\Http\Controllers\DashboardController::class, 'checkDetails']);

//pages routes
Route::get('/pages/{id}', [App\Http\Controllers\PageController::class, 'show'])->name('pages.show');
Route::get('/pricing', [App\Http\Controllers\PricingController::class, 'index'])->name('pricing');

//webhook routes
Route::post('webhooks/stripe', [App\Http\Controllers\WebhookController::class, 'stripe'])->name('webhooks.stripe');
Route::post('webhooks/paypal', [App\Http\Controllers\WebhookController::class, 'paypal'])->name('webhooks.paypal');
Route::post('webhooks/meeting', [App\Http\Controllers\WebhookController::class, 'meeting'])->name('webhooks.meeting');
Route::post('webhooks/user', [App\Http\Controllers\WebhookController::class, 'user'])->name('webhooks.user');

Route::middleware(['auth'])->group(function () {
    //two factor authentication routes
    Route::get('two-factor-auth', [App\Http\Controllers\TwoFAController::class, 'index'])->name('tfa.index');
    Route::post('two-factor-auth', [App\Http\Controllers\TwoFAController::class, 'store'])->name('tfa.post');
    Route::get('two-factor-auth/resend', [App\Http\Controllers\TwoFAController::class, 'resend'])->name('tfa.resend');
});
