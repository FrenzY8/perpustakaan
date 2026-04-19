<?php

/* 
 * Tugas Akhir
 * Rifky Fadillah
 * Peminjaman Buku
 * 04 Februari 2026
 */

namespace App\Http\Controllers;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// LANDING
Route::get('/', [PageController::class, 'home']);

// NOTIF
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/read/{id}', [NotificationController::class, 'read']);
Route::post('/notifications/mark-as-read', [NotificationController::class, 'read_all']);

// BUKU
Route::post('/detail/{id}/komentar', [BookController::class, 'komentar_create']);
Route::post('/detail/{id}/rating', [BookController::class, 'rating']);
Route::get('/detail/{id}', [BookController::class, 'show']);
Route::post('/buku/{id}/komentar', [BookController::class, 'komentar']);
Route::post('/wishlist/{id}', [BookController::class, 'wishlist']);
Route::get('/buku', [BookController::class, 'index']);
Route::post('/pinjam/{id}', [BookController::class, 'pinjam']);

// DASHBOARD
Route::get('/dashboard', [DashboardController::class,'index']);
Route::get('/dashboard/pinjaman', [DashboardController::class, 'pinjaman']);
Route::delete('/dashboard/pinjaman/delete/{id}', [DashboardController::class, 'pinjaman_delete']);
Route::post('/dashboard/kembalikan/{id}', [DashboardController::class, 'kembalikan']);
Route::get('/dashboard/history', [DashboardController::class, 'history']);
Route::get('/dashboard/wishlist', [DashboardController::class, 'wishlist']);
Route::get('/dashboard/uang', [DashboardController::class, 'uang']);
Route::get('/dashboard/history/delete/{id}', [DashboardController::class, 'history_delete']);

// PROFILE
Route::get('/profile', [ProfileController::class, 'index']);
Route::post('/profile/update', [ProfileController::class, 'update']);

// CHAT
Route::get('/chat/jokobot', [ChatbotController::class, 'index']);
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');  
Route::get('/chat/history/{receiverId}', [ChatController::class, 'getMessages']);
Route::post('/chat/send-user', [ChatController::class, 'sendMessage']);
Route::post('/chat/send', [ChatbotController::class, 'chat']);

// ADMIN PANEL
Route::get('/admin/chart', [AdminController::class, 'index']);
Route::get('/admin/panel', [AdminController::class, 'index_panel']);
Route::get('/admin/peminjaman', [AdminController::class, 'index_pinjaman']);
Route::post('/admin/peminjaman/approve/{id}', [AdminController::class, 'terima_pinjaman']);
Route::post('/admin/peminjaman/reject/{id}', [AdminController::class, 'tolak_pinjaman']);
Route::post('/admin/denda/reset/{id}', [AdminController::class, 'reset_denda']);
Route::post('/admin/denda/potong/{id}', [AdminController::class, 'potong_denda']);
Route::post('/admin/peminjaman/kembali/{id}', [AdminController::class, 'kembali']);
Route::post('/admin/books/store', [AdminController::class, 'store_book']);
Route::put('/admin/books/update', [AdminController::class, 'update_book']);
Route::post('/admin/users/update-role', [AdminController::class, 'update_role']);
Route::post('/admin/categories/store', [AdminController::class, 'addCategory']);
Route::put('/admin/categories/update/{id}', [AdminController::class, 'editCategory']);
Route::delete('/admin/categories/delete/{id}', [AdminController::class, 'deleteCategory']);
Route::delete('/admin/books/delete/{id}', [AdminController::class, 'delete_book']);
Route::post('/admin/users/store', [AdminController::class, 'store_user']);
Route::delete('/admin/users/{id}', [AdminController::class, 'delete_user']);

// AUTH
Route::post('/otp/resend', [AuthController::class, 'otp_resend']);
Route::post('/otp/verify', [AuthController::class, 'otp_verify']);
Route::get('/login', [AuthController::class, 'login_page']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/daftar', [AuthController::class, 'daftar_page']);
Route::get('/otp', [AuthController::class, 'otp']);
Route::get('/reset_password', [AuthController::class, 'reset_page']);
Route::post('/reset_password_process', [AuthController::class, 'reset_password_process']);
Route::post('/update_password', [AuthController::class, 'update_password']);
Route::get('/reset-password-form', [AuthController::class, 'reset_form_page']);
Route::get('/reset-notice', [AuthController::class, 'reset_form_notice'])->name('password.notice');
Route::post('/users/store', [AuthController::class, 'users_store']);

// GOOGLE AUTH
Route::get('/auth/google', [AuthController::class, 'google_redirect']);
Route::get('/auth/google/callback', [AuthController::class, 'google_callback']);