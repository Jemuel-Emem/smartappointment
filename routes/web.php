<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin;

use App\Http\Controllers\AuthLogout;
use App\Http\Middleware\SuperAdmin;
use App\Http\Middleware\User;
use Illuminate\Support\Facades\Auth;
Route::view('/', 'welcome');




Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->is_admin == 2) {
            return redirect()->route('spdashboard');
        } else if(Auth::user()->is_admin == 1){
    return redirect()->route('admindashboard');
        } else {
    return redirect()->route('userdashboard');
        }


    })->name('dashboard');
});


Route::post('/logout', [AuthLogout::class, 'logout'])->name('logouts');

Route::prefix('admin')->middleware(['auth', admin::class])->group(function () {
    Route::get('/Admindashboard', function () {
        return view('admin.index');
    })->name('admindashboard');


   Route::get('/admin.services', function () {
        return view('admin.services');
    })->name('admin.services');

     Route::get('/admin.staffs', function () {
        return view('admin.staffs');
    })->name('admin.staffs');

       Route::get('/admin.appointments', function () {
        return view('admin.appointments');
    })->name('admin.appointments');


});


Route::prefix('SuperAdmin')->middleware(['auth', SuperAdmin::class])->group(function () {
    Route::get('/superadmindashboard', function () {
        return view('sp.index');
    })->name('spdashboard');

    Route::get('/sp.department', function () {
        return view('sp.department');
    })->name('sp.department');


});


Route::prefix('User')->middleware(['auth', User::class])->group(function () {
    Route::get('/useradmindashboard', function () {
        return view('user.index');
    })->name('userdashboard');

      Route::get('/user.appointment', function () {
        return view('user.appointment');
    })->name('user.appointment');

      Route::get('/user.profile', function () {
        return view('user.profile');
    })->name('user.profile');

        Route::get('/user.status', function () {
        return view('user.status');
    })->name('user.status');


});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
