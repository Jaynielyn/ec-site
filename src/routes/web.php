<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Mail;

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

Route::get('/', [ItemController::class, 'index'])->name('index');

Route::middleware('auth')->group(function () {
    //出品
    Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
    Route::post('/image_upload', [ItemController::class, 'store'])->name('image_upload');

    //PROFILE
    Route::get('/mypage', [ProfileController::class, 'mypage'])->name('mypage');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'create'])->name('profile.create');

    // 購入ページ
    Route::get('/detail/{id}/purchase', [PurchaseController::class, 'purchase'])->name('purchase');
    Route::get('/purchase/address', [PurchaseController::class, 'showAddressForm'])->name('purchase.addressForm');
    Route::post('/purchase/update-address', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress');
    Route::post('/sold', [PurchaseController::class, 'store'])->name('purchase.store');

    //stripe
    Route::post('/checkout', [PurchaseController::class, 'checkout'])->name('checkout');
    Route::get('/success', [PurchaseController::class, 'success'])->name('checkout.success');
    Route::get('/cancel', [PurchaseController::class, 'cancel'])->name('checkout.cancel');
    Route::post('/webhook', [PurchaseController::class, 'webhook'])->name('checkout.webhook');


    // コメントページ表示
    Route::get('/comment/{item_id}', [LikeController::class, 'showComments'])->name('comment.show');
    Route::post('/comment/{item_id}', [LikeController::class, 'storeComment'])->name('product.comment.store');

    // いいね機能
    Route::post('/like/{item_id}', [LikeController::class, 'toggleLike'])->name('like.toggle');
});
// 詳細ページ
Route::get('/detail/{id}', [ItemController::class, 'detail'])->name('detail');
//検索
Route::get('/search', [SearchController::class, 'show'])->name('search.show');
// おすすめアイテム
Route::get('/recommendations', [SearchController::class, 'recommendations'])->name('recommendations.index');

// 管理者用認証
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

    Route::get('/register', [AdminController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminController::class, 'register'])->name('admin.register.submit');

    // 認証後
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        // ユーザー管理
        Route::get('/dashboard', [AdminUserController::class, 'dashboard'])->name('admin.dashboard');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        // メール作成画面表示 (GET)
        Route::get('/users/{id}/send-mail-form', [AdminUserController::class, 'sendMailForm'])->name('admin.users.sendMailForm');

        // メール送信処理 (POST)
        Route::post('/users/{id}/send-mail', [AdminUserController::class, 'sendMail'])->name('admin.users.sendMail');

        // コメントした商品の表示
        Route::get('/users/{user}/comments', [AdminUserController::class, 'showUserComments'])->name('admin.users.comments');
        Route::get('/admin/items/{item}/details', [AdminUserController::class, 'showItemDetails'])->name('admin.items.details');
        Route::delete('/admin/comments/{comment}', [AdminUserController::class, 'destroyComment'])->name('admin.comments.destroy');
    });
});

Route::get('/test-error', function () {
    return response()->json(['error' => 'Test error route works!'], 500);
});
