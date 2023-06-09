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
                Route::prefix('profiles')->group(static function(){
                    Route::get('/', 'ProfilesController@index')->name('profiles');
                    Route::post('/', 'ProfilesController@store')->name('profiles.store');
                    Route::get('/create', 'ProfilesController@create')->name('profiles.create');
                    Route::get('/{id}/edit', 'ProfilesController@edit')->name('profiles.edit');
                    Route::put('/{id}', 'ProfilesController@update')->name('profiles.update');
                    Route::get('/{id}/show', 'ProfilesController@show')->name('profiles.show');
                    Route::get('/{id}/delete', 'ProfilesController@destroy')->name('profiles.delete');
                    Route::get('/{id}/restore', 'ProfilesController@restore')->name('profiles.restore');
                });
                Route::prefix('socials')->group(static function(){
                    Route::get('/', 'SocialsController@index')->name('socials');
                    Route::post('/', 'SocialsController@store')->name('socials.store');
                    Route::get('/create', 'SocialsController@create')->name('socials.create');
                    Route::get('/{id}/edit', 'SocialsController@edit')->name('socials.edit');
                    Route::put('/{id}', 'SocialsController@update')->name('socials.update');
                    Route::get('/{id}/show', 'SocialsController@show')->name('socials.show');
                    Route::get('/{id}/delete', 'SocialsController@destroy')->name('socials.delete');
                });
                Route::prefix('educations')->group(static function(){
                    Route::get('/', 'EducationsController@index')->name('educations');
                    Route::post('/', 'EducationsController@store')->name('educations.store');
                    Route::get('/create', 'EducationsController@create')->name('educations.create');
                    Route::get('/{id}/edit', 'EducationsController@edit')->name('educations.edit');
                    Route::put('/{id}', 'EducationsController@update')->name('educations.update');
                    Route::get('/{id}/show', 'EducationsController@show')->name('educations.show');
                    Route::get('/{id}/delete', 'EducationsController@destroy')->name('educations.delete');
                });
                Route::prefix('languages')->group(static function(){
                    Route::get('/', 'LanguagesController@index')->name('languages');
                    Route::post('/', 'LanguagesController@store')->name('languages.store');
                    Route::get('/create', 'LanguagesController@create')->name('languages.create');
                    Route::get('/{id}/edit', 'LanguagesController@edit')->name('languages.edit');
                    Route::put('/{id}', 'LanguagesController@update')->name('languages.update');
                    Route::get('/{id}/show', 'LanguagesController@show')->name('languages.show');
                    Route::get('/{id}/delete', 'LanguagesController@destroy')->name('languages.delete');
                });
                Route::prefix('interests')->group(static function(){
                    Route::get('/', 'InterestsController@index')->name('interests');
                    Route::post('/', 'InterestsController@store')->name('interests.store');
                    Route::get('/create', 'InterestsController@create')->name('interests.create');
                    Route::get('/{id}/edit', 'InterestsController@edit')->name('interests.edit');
                    Route::put('/{id}', 'InterestsController@update')->name('interests.update');
                    Route::get('/{id}/show', 'InterestsController@show')->name('interests.show');
                    Route::get('/{id}/delete', 'InterestsController@destroy')->name('interests.delete');
                });
                Route::prefix('experiences')->group(static function(){
                    Route::get('/', 'ExperiencesController@index')->name('experiences');
                    Route::post('/', 'ExperiencesController@store')->name('experiences.store');
                    Route::get('/create', 'ExperiencesController@create')->name('experiences.create');
                    Route::get('/{id}/edit', 'ExperiencesController@edit')->name('experiences.edit');
                    Route::put('/{id}', 'ExperiencesController@update')->name('experiences.update');
                    Route::get('/{id}/show', 'ExperiencesController@show')->name('experiences.show');
                    Route::get('/{id}/delete', 'ExperiencesController@destroy')->name('experiences.delete');
                });
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
    Route::namespace('App\Http\Controllers')->group(static function() {
        Route::namespace('Module')->group(static function () {
            Route::prefix('/profiles')->group(static function() {
                Route::get('/countstatus', 'ProfilesController@countstatus');
                Route::get('/checkStatusById/{id}', 'ProfilesController@checkStatusById');
                Route::post('/updateStatusById/{id}', 'ProfilesController@updateStatusById');
            });
        });
    });
});