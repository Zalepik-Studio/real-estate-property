<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\NotifController;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('reset-password');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('/get-chats', [ChatsController::class, 'getChats']);

    Route::get('/direct-chats', function () {
        return view('direct-chats');
    });

    Route::get('/get-messages', [ChatsController::class, 'getMessages']); 

    Route::get('/chats', [ChatsController::class, 'chats']); 

    Route::post('/chats', [ChatsController::class, 'createChat'])->name('chats');

    Route::post('/delete-chats', [ChatsController::class, 'deleteChats'])->name('delete-chats');

    Route::post('/send-messgae', [ChatsController::class, 'sendMessage'])->name('send-message');

    Route::post('/delete-message', [ChatsController::class, 'deleteMessage'])->name('delete-message');

    Route::get('/search', [PropertyController::class, 'search'])->name('search');

    Route::post('/visit', [PropertyController::class, 'visit'])->name('visit');

    Route::get('/get-properties', [PropertyController::class, 'getProperties']);

    Route::post('/stars', [PropertyController::class, 'stars'])->name('stars');

    Route::get('/property', [PropertyController::class, 'detail'])->name('detail');  
    
    Route::get('/notifications', [NotifController::class, 'notifications']); 

    Route::get('/settings', function () {
        return view('settings');
    });

    Route::get('/update-password', [UserController::class, 'updatePassword']);  
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update-password');  

    Route::post('/delete-notif', [NotifController::class, 'deleteNotif'])->name('delete-notif');  

    Route::get('/add-property', [PropertyController::class, 'addProperty'])->middleware('role:admin,developer');

    Route::post('/add-property', [PropertyController::class, 'addProperty'])->name('add-property');

    Route::get('/update-property/{id}', [PropertyController::class, 'updateProperty'])->name('update-property');
    Route::post('/update-property/{id}', [PropertyController::class, 'updateProperty'])->name('update-property');

    Route::post('/delete-property', [PropertyController::class, 'deleteProperty'])->name('delete-property');

    Route::get('/most-visited', [PropertyController::class, 'mostVisited'])->name('most-visited');

    Route::get('/user', [UserController::class, 'profile'])->name('profile');

    Route::get('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::post('/update-profile-picture', [UserController::class, 'updateProfilePicture'])->name('update-profile-picture');   
    Route::post('/delete-profile-picture', [UserController::class, 'deleteProfilePicture'])->name('delete-profile-picture');      

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});