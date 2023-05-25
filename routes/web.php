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

Route::get('/', 'App\Http\Controllers\Welcome\WelcomeController@index')->name('welcome');

Auth::routes();

Route::get('/auth/check',function(){
    return (Auth::check()) ? True : False;
});

Route::middleware(['auth'])->group(static function () {
    Route::middleware('permission')->group(static function () {
        // route with permission middleware
        Route::namespace('App\Http\Controllers')->group(static function () {
            Route::namespace('Settings')->group(static function (){
                Route::get('/attachments','AttachmentsController@index')->name('attachments');
                Route::prefix('icons')->group(static function() {
                    Route::get('/', 'IconsController@index')->name('icons');
                    Route::post('/', 'IconsController@store')->name('icons.store');
                    Route::get('/create', 'IconsController@create')->name('icons.create');
                    Route::post('/import', 'IconsController@import')->name('icons.import');
                    Route::get('/{id}/edit', 'IconsController@edit')->name('icons.edit');
                    Route::put('/{id}', 'IconsController@update')->name('icons.update');
                    Route::get('/{id}/show', 'IconsController@show')->name('icons.show');
                    Route::get('/{id}/delete', 'IconsController@destroy')->name('icons.delete');
                });
            });
        });
    });
});