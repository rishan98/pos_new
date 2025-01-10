<?php

use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductInventoryController;
use App\Http\Controllers\Admin\SupplierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    });
    
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);

    // Inventory
    Route::get('/product-inventory', [ProductInventoryController::class, 'index'])->name('inventory.index');
    Route::put('/product-inventory/edit/{id}', [ProductInventoryController::class, 'edit'])->name('inventory.edit');

    // Supplier
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/supplier/new', [SupplierController::class, 'createUI'])->name('suppliers.new');
    Route::post('/supplier/new', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::get('/supplier/edit/{id}', [SupplierController::class, 'editUI'])->name('suppliers.edit');
    Route::put('/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('suppliers.update');
    Route::delete('/supplier/delete/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);

    // Transaltions route for React component
    Route::get('/locale/{type}', function ($type) {
        $translations = trans($type);
        return response()->json($translations);
    });
});
