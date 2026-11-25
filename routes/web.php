<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\User\DashboardUser;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Admin\AdminOrders;
use App\Livewire\User\ProfileUser;
use App\Livewire\User\CartPage;
use App\Livewire\User\OrderSuccessPage;
use App\Livewire\User\CheckoutPage;
use App\Livewire\Admin\Products\Index;
use App\Livewire\Admin\Products\Form;
use App\Livewire\Admin\ProfileAdmin;
use App\Livewire\User\ClothingAssistant;
use App\Livewire\User\MyOrders;
Route::view('/', 'welcome')->name('welcome');


Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});


Route::middleware('auth')->group(function () {
    Route::get('/home', DashboardUser::class)->name('home');
    Route::get('/profile-user', ProfileUser::class)->name('profile.user');
    Route::get('/cart', CartPage::class)->name('cart');
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/order-success', OrderSuccessPage::class)->name('order-success');
    Route::get('/my-orders', MyOrders::class)->name('my-orders');
    Route::get('/chat', ClothingAssistant::class)->name('chat');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');


    Route::middleware(AdminMiddleware::class)->prefix('admin')->group(function () {
        Route::get('/products', Index::class)->name('admin.products');
        Route::get('/products/create', Form::class)->name('admin.products.create');
        Route::get('/products/{product}/edit', Form::class)->name('admin.products.edit');
        Route::get('/profile-admin', ProfileAdmin::class)->name('admin.profile');
        Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
        Route::get('/orders', AdminOrders::class)->name('admin.orders');
        Route::get('/chat-analytics', \App\Livewire\Admin\ChatAnalytics::class)->name('admin.chat-analytics');
    });
});
