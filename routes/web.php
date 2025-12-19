<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\NearbyController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::get('/', [ExploreController::class, 'index'])->name('explore.index');
Route::get('/explore/search', [ExploreController::class, 'search'])->name('explore.search');
Route::get('/explore/discover', [ExploreController::class, 'discover'])->name('explore.discover');

Route::get('/nearby', [NearbyController::class, 'index'])->name('nearby.index');

Route::get('/journeys', [JourneyController::class, 'index'])->name('journeys.index');
Route::get('/journeys/create', [JourneyController::class, 'create'])->name('journeys.create');
Route::post('/journeys', [JourneyController::class, 'store'])->name('journeys.store');
Route::get('/journeys/{journey}/edit', [JourneyController::class, 'edit'])->name('journeys.edit');
Route::put('/journeys/{journey}', [JourneyController::class, 'update'])->name('journeys.update');
Route::delete('/journeys/{journey}', [JourneyController::class, 'destroy'])->name('journeys.destroy');
Route::get('/journeys/post/{post}', [JourneyController::class, 'getPostData'])->name('journeys.post-data');
Route::get('/journeys/{journey}', [JourneyController::class, 'show'])->name('journeys.show');

Route::get('/people', [PeopleController::class, 'index'])->name('people.index');
Route::get('/people/edit', [PeopleController::class, 'edit'])->name('people.edit');
Route::put('/people', [PeopleController::class, 'update'])->name('people.update');
Route::get('/people/{user}', [PeopleController::class, 'show'])->name('people.show');

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/vote', [PostController::class, 'vote'])->name('posts.vote');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');