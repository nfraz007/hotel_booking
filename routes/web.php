<?php

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

Route::get("/", "Home@home")->name("home");
Route::get("/detail/{slack}", "Home@detail")->name("detail");
Route::get("/profile", "Profile@view")->name("profile");
Route::get("logout", "API\Auth@logout")->name("logout");