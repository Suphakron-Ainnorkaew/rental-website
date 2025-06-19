<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CustomRegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostumeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\HowToRentController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PolicyController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/featured-costumes', [CostumeController::class, 'featuredIndex'])->name('featured');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [CustomRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CustomRegisterController::class, 'register']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Category Routes
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

// Additional Public Routes from app.blade.php
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions');
Route::get('/how-to-rent', [HowToRentController::class, 'index'])->name('how-to-rent');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/size-guide', [ShippingController::class, 'sizeGuide'])->name('size-guide');
Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping');
Route::get('/track-shipping', [ShippingController::class, 'track'])->name('track-shipping');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
Route::get('/help', [HelpController::class, 'index'])->name('help');
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/terms', [PolicyController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('privacy-policy');
Route::get('/return-policy', [PolicyController::class, 'return'])->name('return-policy');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Costume Routes
    Route::get('/costume/{id}', [CostumeController::class, 'show'])->name('costume.show');
    Route::get('/costume/{id}/rent', [CostumeController::class, 'rent'])->name('costume.rent');
    Route::post('/costume/rent', [CostumeController::class, 'storeRent'])->name('costume.rent.store');
    Route::post('/costume/checkout', [CostumeController::class, 'checkout'])->name('costume.checkout');
    Route::match(['get', 'post'], '/costume/payment', [CostumeController::class, 'payment'])->name('costume.payment');

    // User Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/user/chat', [RentalController::class, 'userChat'])->name('user.chat');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    // Chat Routes
    Route::get('/chat/{rental}', [ChatController::class, 'show'])->name('chat');
    Route::post('/chat/send', [RentalController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages/{rentalId}', [AdminController::class, 'getMessages'])->name('chat.messages');

    // Rental Routes
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::get('/rentals/{rental}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
    Route::put('/rentals/{id}', [RentalController::class, 'update'])->name('rentals.update');
    Route::delete('/rentals/{id}', [RentalController::class, 'destroy'])->name('rentals.destroy');
    Route::get('/rentals/{rental}/extend', [RentalController::class, 'extend'])->name('rentals.extend');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/costume', [AdminController::class, 'createCostume'])->name('admin.costume.create');
    Route::post('/admin/costume/store', [AdminController::class, 'storeCostume'])->name('admin.costume.store');
    Route::get('/admin/rentals', [AdminController::class, 'rentals'])->name('admin.rentals');
    Route::get('/admin/chats', [AdminController::class, 'chats'])->name('admin.chats');
    Route::get('/admin/chat/{rental_id}', [AdminController::class, 'chat'])->name('admin.chat');
    Route::post('/admin/rentals/{id}/confirm', [AdminController::class, 'confirmRental'])->name('admin.rentals.confirm');
    Route::post('/admin/rentals/{rental_id}/confirm-from-chat', [AdminController::class, 'confirmRentalFromChat'])->name('admin.rentals.confirm-from-chat');
    Route::post('/admin/rentals/{id}/complete', [AdminController::class, 'completeRental'])->name('admin.rentals.complete');
});