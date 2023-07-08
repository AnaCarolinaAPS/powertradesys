<?php

use App\Http\Controllers\ProfileController;
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

use App\Models\User;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', 'DashboardController@index')->middleware(['auth'])->name('dashboard');

Route::get('/dashboard', function () {
    $id = Auth::user()->id;
    $user = User::find($id);
    $isAdmin = $user->isAdmin();
    $isClient = $user->isClient();
    $isLogistics = $user->isLogistics();
    $isFinancial = $user->isFinancial();

    return view('admin.index', compact('isAdmin', 'isClient', 'isLogistics', 'isFinancial'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
