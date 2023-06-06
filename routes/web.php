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
                Route::prefix('educationlevel')->group(static function() {
                    Route::get('/', 'EducationLevelController@index')->name('educationlevel');
                    Route::post('/', 'EducationLevelController@store')->name('educationlevel.store');
                    Route::get('/create', 'EducationLevelController@create')->name('educationlevel.create');
                    Route::get('/{id}/edit', 'EducationLevelController@edit')->name('educationlevel.edit');
                    Route::put('/{id}', 'EducationLevelController@update')->name('educationlevel.update');
                    Route::get('/{id}/show', 'EducationLevelController@show')->name('educationlevel.show');
                    Route::get('/{id}/delete', 'EducationLevelController@destroy')->name('educationlevel.delete');
                });
                Route::prefix('proficiencylevel')->group(static function() {
                    Route::get('/', 'ProficiencyLevelController@index')->name('proficiencylevel');
                    Route::post('/', 'ProficiencyLevelController@store')->name('proficiencylevel.store');
                    Route::get('/create', 'ProficiencyLevelController@create')->name('proficiencylevel.create');
                    Route::get('/{id}/edit', 'ProficiencyLevelController@edit')->name('proficiencylevel.edit');
                    Route::put('/{id}', 'ProficiencyLevelController@update')->name('proficiencylevel.update');
                    Route::get('/{id}/show', 'ProficiencyLevelController@show')->name('proficiencylevel.show');
                    Route::get('/{id}/delete', 'ProficiencyLevelController@destroy')->name('proficiencylevel.delete');
                });
                Route::prefix('statuslist')->group(static function() {
                    Route::get('/', 'StatusController@index')->name('statuslist');
                    Route::post('/', 'StatusController@store')->name('statuslist.store');
                    Route::get('/create', 'StatusController@create')->name('statuslist.create');
                    Route::get('/{id}/edit', 'StatusController@edit')->name('statuslist.edit');
                    Route::put('/{id}', 'StatusController@update')->name('statuslist.update');
                    Route::get('/{id}/show', 'StatusController@show')->name('statuslist.show');
                    Route::get('/{id}/delete', 'StatusController@destroy')->name('statuslist.delete');
                });
            });
            Route::namespace('Module')->group(static function(){
                Route::prefix('skills')->group(static function(){
                    Route::get('/', 'SkillsController@index')->name('skills');
                    Route::post('/', 'SkillsController@store')->name('skills.store');
                    Route::get('/create', 'SkillsController@create')->name('skills.create');
                    Route::get('/{id}/edit', 'SkillsController@edit')->name('skills.edit');
                    Route::put('/{id}', 'SkillsController@update')->name('skills.update');
                    Route::get('/{id}/show', 'SkillsController@show')->name('skills.show');
                    Route::get('/{id}/delete', 'SkillsController@destroy')->name('skills.delete');
                });
            });
        });
    });
});