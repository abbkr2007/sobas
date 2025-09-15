<?php

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Security\RolePermission;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SlipController;

// Packages


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
// routes/web.php
// Route::get('/test-env', function () {
//     return response()->json([
//         'app_name' => env('APP_NAME'),
//         'env' => env('APP_ENV'),
//         'debug' => env('APP_DEBUG'),
//     ]);
// });

// Route::get('/send-test-email', function () {
//     // Send a test email
//     Mail::raw('This is a test email using Mailtrap SMTP!', function ($message) {
//         $message->to('abbkr2007@gmail.com')
//                 ->subject('Test Email');
//     });

//     return 'Test email sent successfully!';
// });


require __DIR__ . '/auth.php';

Route::get('/storage', function () {
    Artisan::call('storage:link');
});

// GET ZONE AND BRANCH MENUS
// Route::get('get-Zone', [ZoneAndBranchController::class, 'getZone']);
// Route::post('get-branch', [ZoneAndBranchController::class, 'getBranch'])->name('get-branch');
//UI Pages Routs
Route::get('/d', [HomeController::class, 'uisheet'])->name('uisheet');

Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

    return "Cleared!";

});

    Route::get('/payment/redirect', [RegisteredUserController::class, 'redirectToGateway'])->name('payment.redirectToGateway');
    Route::get('/payment/callback', [RegisteredUserController::class, 'handleGatewayCallback'])->name('payment.callback');

    Route::get('/slip', [SlipController::class, 'index'])->name('slip');


    Route::group(['middleware' => 'auth'], function () {
    
   

Route::get('/application', [ApplicationController::class, 'create'])->name('application.form');
Route::post('/application-submit', [ApplicationController::class, 'store'])->name('application.submit');
Route::get('/application-preview/{id}', [ApplicationController::class, 'preview'])->name('application.preview');

    // Permission Module
    Route::get('/role-permission', [RolePermission::class, 'index'])->name('role.permission.list');
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::get('/application', [ApplicationController::class, 'showForm'])->name('application.show');
  

    Route::post('/application/submit', [ApplicationController::class, 'submitForm'])->name('application.submit');

    // Paystack payment


    Route::get('/application/slip', [ApplicationController::class, 'slip'])->name('application.slip');
    // Dashboard Routes
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/submit-paper', [HomeController::class, 'submit_paper'])->name('submit-paper');
    Route::get('/document/history', [DocumentController::class, 'history'])->name('document.history');
    Route::get('/documents/{id}', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('/download-qr', [HomeController::class, 'downloadQR'])->name('download.qr');
    Route::get('/documents/update-status/{id}/{status}', [DocumentController::class, 'updateStatus'])->name('documents.updateStatus');




    Route::post('/documents', [DocumentController::class, 'store'])->name('document.store');

    Route::put('/role-permission-update', [RoleController::class, 'update'])->name('role-permission.update');




    Route::get('/documents', [HomeController::class, 'index'])->name('documents.index');


    Route::match(['get', 'post'], '/signup', [HomeController::class, 'signup'])->name('auth.signup');

    // Users Module
    Route::resource('users', UserController::class);
    // Route::get('/users/get-branches/{zone_id}', [UserController::class, 'getBranches']);

});

//App Details Page => 'Dashboard'], function() {
Route::group(['prefix' => 'menu-style'], function () {
    //MenuStyle Page Routs
    Route::get('horizontal', [HomeController::class, 'horizontal'])->name('menu-style.horizontal');
    Route::get('dual-horizontal', [HomeController::class, 'dualhorizontal'])->name('menu-style.dualhorizontal');
    Route::get('dual-compact', [HomeController::class, 'dualcompact'])->name('menu-style.dualcompact');
    Route::get('boxed', [HomeController::class, 'boxed'])->name('menu-style.boxed');
    Route::get('boxed-fancy', [HomeController::class, 'boxedfancy'])->name('menu-style.boxedfancy');
});

//App Details Page => 'special-pages'], function() {
Route::group(['prefix' => 'special-pages'], function () {
    //Example Page Routs
    Route::get('billing', [HomeController::class, 'billing'])->name('special-pages.billing');
    Route::get('calender', [HomeController::class, 'calender'])->name('special-pages.calender');
    Route::get('kanban', [HomeController::class, 'kanban'])->name('special-pages.kanban');
    Route::get('pricing', [HomeController::class, 'pricing'])->name('special-pages.pricing');
    Route::get('rtl-support', [HomeController::class, 'rtlsupport'])->name('special-pages.rtlsupport');
    Route::get('timeline', [HomeController::class, 'timeline'])->name('special-pages.timeline');
});

//Widget Routs
Route::group(['prefix' => 'widget'], function () {
    Route::get('widget-basic', [HomeController::class, 'widgetbasic'])->name('widget.widgetbasic');
    Route::get('widget-chart', [HomeController::class, 'widgetchart'])->name('widget.widgetchart');
    Route::get('widget-card', [HomeController::class, 'widgetcard'])->name('widget.widgetcard');
});

//Maps Routs
Route::group(['prefix' => 'maps'], function () {
    Route::get('google', [HomeController::class, 'google'])->name('maps.google');
    Route::get('vector', [HomeController::class, 'vector'])->name('maps.vector');
});

//Auth pages Routs
Route::group(['prefix' => 'auth'], function () {

    Route::get('signin', [HomeController::class, 'signin'])->name('auth.signin');

    Route::get('confirmmail', [HomeController::class, 'confirmmail'])->name('auth.confirmmail');
    Route::get('lockscreen', [HomeController::class, 'lockscreen'])->name('auth.lockscreen');
    Route::get('recoverpw', [HomeController::class, 'recoverpw'])->name('auth.recoverpw');
    Route::get('userprivacysetting', [HomeController::class, 'userprivacysetting'])->name('auth.userprivacysetting');
});

//Error Page Route
Route::group(['prefix' => 'errors'], function () {
    Route::get('error404', [HomeController::class, 'error404'])->name('errors.error404');
    Route::get('error500', [HomeController::class, 'error500'])->name('errors.error500');
    Route::get('maintenance', [HomeController::class, 'maintenance'])->name('errors.maintenance');
});


//Forms Pages Routs
Route::group(['prefix' => 'forms'], function () {
    Route::get('element', [HomeController::class, 'element'])->name('forms.element');
    Route::get('wizard', [HomeController::class, 'wizard'])->name('forms.wizard');
    Route::get('validation', [HomeController::class, 'validation'])->name('forms.validation');
});


//Table Page Routs
Route::group(['prefix' => 'table'], function () {
    Route::get('bootstraptable', [HomeController::class, 'bootstraptable'])->name('table.bootstraptable');
    Route::get('datatable', [HomeController::class, 'datatable'])->name('table.datatable');
});

//Icons Page Routs
Route::group(['prefix' => 'icons'], function () {
    Route::get('solid', [HomeController::class, 'solid'])->name('icons.solid');
    Route::get('outline', [HomeController::class, 'outline'])->name('icons.outline');
    Route::get('dualtone', [HomeController::class, 'dualtone'])->name('icons.dualtone');
    Route::get('colored', [HomeController::class, 'colored'])->name('icons.colored');
});
//Extra Page Routs
Route::get('privacy-policy', [HomeController::class, 'privacypolicy'])->name('pages.privacy-policy');
Route::get('terms-of-use', [HomeController::class, 'termsofuse'])->name('pages.term-of-use');
