<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/recipe/create', [App\Http\Controllers\CreateRecipeController::class, "create"])
	->name("create_recipe");
Route::post('/recipe/create', [App\Http\Controllers\CreateRecipeController::class, "store"])
	->name("store_recipe");

Route::get('/recipe', [App\Http\Controllers\RecipeListController::class, "show"])
    ->name("recipe_list");

/* 詳細・編集 */
Route::get('/recipe/{id}', [App\Http\Controllers\RecipeController::class, "show"])
    ->name("recipe_detail");
Route::get('/recipe/edit/{id}', [App\Http\Controllers\RecipeController::class, "form"])
    ->name("recipe_edit");
Route::post('/recipe/edit/{id}', [App\Http\Controllers\RecipeController::class, "update"])
    ->name("update_recipe");