<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\Category;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::get('/signin', [AuthController::class, 'create'])->name('signin');

    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    Route::post('/signin', [AuthController::class, 'store'])->name('signin.store');

    // No public registration.
    Route::redirect('/signup', '/login');
    Route::redirect('/register', '/login');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// items pages
Route::resource('items', ItemController::class);

// stock transactions pages
Route::resource('stock_transactions', StockTransactionController::class);

// categories pages
Route::resource('categories', CategoryController::class);

// dashboard pages
Route::get('/', function () {
    $title = 'Dashboard';

    $totalItems = Item::count();
    $totalCategories = Category::count();
    $totalStock = Item::sum('quantity');
    $totalTransactions = StockTransaction::count();

    $availableItems = Item::where('quantity', '>', 0)->count();
    $outOfStockCount = Item::where('quantity', '<=', 0)->count();

    $recentTransactions = StockTransaction::with('item')
        ->latest()
        ->take(5)
        ->get();

    $outOfStockItems = Item::with('category')
        ->where('quantity', '<=', 0)
        ->latest()
        ->take(5)
        ->get();

    return view('pages.dashboard.ecommerce', compact(
        'title',
        'totalItems',
        'totalCategories',
        'totalStock',
        'totalTransactions',
        'availableItems',
        'outOfStockCount',
        'recentTransactions',
        'outOfStockItems'
    ));
})->name('dashboard');

// Route::get('/', function () {
//     return view('pages.dashboard.ecommerce', ['title' => 'Dashboard']);
// })->name('dashboard');

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');



// Admin only routes
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});


















