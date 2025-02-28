<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'HomePage'])->name('HomePage');
Route::get('/login-page', [UserController::class, 'LoginPage'])->name('Login');
Route::get('/signup', [UserController::class, 'SignUp'])->name('signup');
Route::get('/send-otp', [UserController::class, 'SendOtpPage'])->name('send-otp');
Route::get('/verify-otp', [UserController::class, 'VerifyOtpPage'])->name('verify-otp');
Route::get('/reset-password', [UserController::class, 'setpassword'])->name('reset-password');

//after login action
Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboardPage'])->name('dashboard');
    ///--------------------Category Route--------------------------///
    Route::get('/category', [CategorieController::class, 'categoryPage'])->name('category');
    Route::get('/add-category', [CategorieController::class, 'addCategoryPage'])->name('add-category');
    Route::get('/edit-category/{id}', [CategorieController::class, 'editCategoryPage'])->name('edit-category');
    ///--------------------Customer Route--------------------------///
    Route::get('/customer-page', [CustomerController::class, 'customerPage'])->name('customer');
    Route::get('/customer-add', [CustomerController::class, 'customerAdd'])->name('customer-add');
    Route::get('/product-page', [ProductController::class, 'productPage'])->name('product-page');
    Route::get('/product-add', [ProductController::class, 'productAddPage'])->name('product-add');
    Route::get('/profile', [UserController::class, 'profilePage'])->name('profile');
    Route::get('/invoice', [InvoiceController::class, 'invoicePage'])->name('invoice');
    Route::get('/create-seal', [InvoiceController::class, 'createSeal'])->name('create-seal');
});




Route::post('/user-registration', [UserController::class, 'userResistration'])->name('User-Registration');
Route::post('/userLogin', [UserController::class, 'userLogin'])->name('userLogin');
Route::get('/userLogout', [UserController::class, 'userLogout'])->name('userLogout');
Route::post('/sendOTP', [UserController::class, 'sendOTP'])->name('sendOTP');
Route::post('/verifyOTP', [UserController::class, 'verifyOTP'])->name('verifyOTP');

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/resetPass', [UserController::class, 'resetPass'])->name('resetPass');
    Route::post('/updateUser', [UserController::class, 'updateUser'])->name('updateUser');
    //category section
    Route::post('/createCategorie', [CategorieController::class, 'createCategorie'])->name('createCategorie');
    Route::get('/categorieList', [CategorieController::class, 'categorieList'])->name('categorieList');
    Route::post('/updateCategorie', [CategorieController::class, 'updateCategorie'])->name('updateCategorie');
    Route::get('/deleteCategorie/{id}', [CategorieController::class, 'deleteCategorie'])->name('deleteCategorie');

    //Customer section

    Route::post('/customerCreate', [CustomerController::class, 'customerCreate'])->name('customerCreate');
    Route::post('/customerUpdate', [CustomerController::class, 'customerUpdate'])->name('customerUpdate');
    Route::get('/customerDelete/{id}', [CustomerController::class, 'customerDelete'])->name('customerDelete');
    //Product section
    Route::get('/productList', [ProductController::class, 'productList'])->name('productList');
    Route::post('/addProduct', [ProductController::class, 'addProduct'])->name('addProduct');
    Route::get('/deleteProduct/{id}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
    Route::post('/updateProduct', [ProductController::class, 'updateProduct'])->name('updateProduct');
    //Invoice 
    Route::get('/invoiceList', [InvoiceController::class, 'invoiceList'])->name('invoiceList');
    Route::get('/deleteInvoice/{id}', [InvoiceController::class, 'deleteInvoice'])->name('deleteInvoice');
    Route::post('/createInvoice', [InvoiceController::class, 'createInvoice'])->name('createInvoice');
    Route::post('/invoiceDetials', [InvoiceController::class, 'invoiceDetials'])->name('invoiceDetials');
});