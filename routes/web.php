<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Middleware\EnsureUserIsAdmin;

use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/signin', [LoginController::class, 'showLoginForm'])->name('signin');

    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
    Route::post('/signin', [LoginController::class, 'login'])->name('signin.store');

    // No public registration for inventory system
    Route::redirect('/signup', '/login');
    Route::redirect('/register', '/login');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Protected System Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

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

    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard.redirect');

    // items pages
    Route::resource('items', ItemController::class);

    // stock transactions pages
    Route::resource('stock_transactions', StockTransactionController::class);

    // categories pages
    Route::resource('categories', CategoryController::class);

    // calendar pages
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

    // blank page
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
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});