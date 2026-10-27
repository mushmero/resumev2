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

Auth::routes(['register' => false]);

Route::get('/auth/check',function(){
    return (Auth::check()) ? True : False;
});

Route::middleware(['auth'])->group(static function () {
    Route::middleware('permission')->group(static function () {
        // route with permission middleware
        Route::namespace('App\Http\Controllers')->group(static function () {
            Route::get('/home', 'HomeController@index')->name('home');
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
                    Route::match(['get', 'delete'], '/{id}/delete', 'IconsController@destroy')->name('icons.delete');
                    Route::delete('/{id}', 'IconsController@destroy')->name('icons.destroy');
                });
                Route::prefix('educationlevel')->group(static function() {
                    Route::get('/', 'EducationLevelController@index')->name('educationlevel');
                    Route::post('/', 'EducationLevelController@store')->name('educationlevel.store');
                    Route::get('/create', 'EducationLevelController@create')->name('educationlevel.create');
                    Route::get('/{id}/edit', 'EducationLevelController@edit')->name('educationlevel.edit');
                    Route::put('/{id}', 'EducationLevelController@update')->name('educationlevel.update');
                    Route::get('/{id}/show', 'EducationLevelController@show')->name('educationlevel.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'EducationLevelController@destroy')->name('educationlevel.delete');
                    Route::delete('/{id}', 'EducationLevelController@destroy')->name('educationlevel.destroy');
                });
                Route::prefix('proficiencylevel')->group(static function() {
                    Route::get('/', 'ProficiencyLevelController@index')->name('proficiencylevel');
                    Route::post('/', 'ProficiencyLevelController@store')->name('proficiencylevel.store');
                    Route::get('/create', 'ProficiencyLevelController@create')->name('proficiencylevel.create');
                    Route::get('/{id}/edit', 'ProficiencyLevelController@edit')->name('proficiencylevel.edit');
                    Route::put('/{id}', 'ProficiencyLevelController@update')->name('proficiencylevel.update');
                    Route::get('/{id}/show', 'ProficiencyLevelController@show')->name('proficiencylevel.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'ProficiencyLevelController@destroy')->name('proficiencylevel.delete');
                    Route::delete('/{id}', 'ProficiencyLevelController@destroy')->name('proficiencylevel.destroy');
                });
                Route::prefix('statuslist')->group(static function() {
                    Route::get('/', 'StatusController@index')->name('statuslist');
                    Route::post('/', 'StatusController@store')->name('statuslist.store');
                    Route::get('/create', 'StatusController@create')->name('statuslist.create');
                    Route::get('/{id}/edit', 'StatusController@edit')->name('statuslist.edit');
                    Route::put('/{id}', 'StatusController@update')->name('statuslist.update');
                    Route::get('/{id}/show', 'StatusController@show')->name('statuslist.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'StatusController@destroy')->name('statuslist.delete');
                    Route::delete('/{id}', 'StatusController@destroy')->name('statuslist.destroy');
                });
            });
            Route::namespace('Module')->group(static function(){
                Route::prefix('profiles')->group(static function(){
                    Route::get('/', 'ProfilesController@index')->name('profiles');
                    Route::post('/', 'ProfilesController@store')->name('profiles.store');
                    Route::get('/create', 'ProfilesController@create')->name('profiles.create');
                    Route::get('/countstatus', 'ProfilesController@countstatus')->name('profiles.countstatus');
                    Route::get('/checkStatusById/{id}', 'ProfilesController@checkStatusById')->name('profiles.checkstatus');
                    Route::post('/updateStatusById/{id}', 'ProfilesController@updateStatusById')->name('profiles.updatestatus');
                    Route::get('/{id}/edit', 'ProfilesController@edit')->name('profiles.edit');
                    Route::put('/{id}', 'ProfilesController@update')->name('profiles.update');
                    Route::get('/{id}/show', 'ProfilesController@show')->name('profiles.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'ProfilesController@destroy')->name('profiles.delete');
                    Route::delete('/{id}', 'ProfilesController@destroy')->name('profiles.destroy');
                    Route::get('/{id}/restore', 'ProfilesController@restore')->name('profiles.restore');
                });
                Route::prefix('socials')->group(static function(){
                    Route::get('/', 'SocialsController@index')->name('socials');
                    Route::post('/', 'SocialsController@store')->name('socials.store');
                    Route::get('/create', 'SocialsController@create')->name('socials.create');
                    Route::get('/{id}/edit', 'SocialsController@edit')->name('socials.edit');
                    Route::put('/{id}', 'SocialsController@update')->name('socials.update');
                    Route::get('/{id}/show', 'SocialsController@show')->name('socials.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'SocialsController@destroy')->name('socials.delete');
                    Route::delete('/{id}', 'SocialsController@destroy')->name('socials.destroy');
                });
                Route::prefix('educations')->group(static function(){
                    Route::get('/', 'EducationsController@index')->name('educations');
                    Route::post('/', 'EducationsController@store')->name('educations.store');
                    Route::get('/create', 'EducationsController@create')->name('educations.create');
                    Route::get('/{id}/edit', 'EducationsController@edit')->name('educations.edit');
                    Route::put('/{id}', 'EducationsController@update')->name('educations.update');
                    Route::get('/{id}/show', 'EducationsController@show')->name('educations.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'EducationsController@destroy')->name('educations.delete');
                    Route::delete('/{id}', 'EducationsController@destroy')->name('educations.destroy');
                });
                Route::prefix('languages')->group(static function(){
                    Route::get('/', 'LanguagesController@index')->name('languages');
                    Route::post('/', 'LanguagesController@store')->name('languages.store');
                    Route::get('/create', 'LanguagesController@create')->name('languages.create');
                    Route::get('/{id}/edit', 'LanguagesController@edit')->name('languages.edit');
                    Route::put('/{id}', 'LanguagesController@update')->name('languages.update');
                    Route::get('/{id}/show', 'LanguagesController@show')->name('languages.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'LanguagesController@destroy')->name('languages.delete');
                    Route::delete('/{id}', 'LanguagesController@destroy')->name('languages.destroy');
                });
                Route::prefix('interests')->group(static function(){
                    Route::get('/', 'InterestsController@index')->name('interests');
                    Route::post('/', 'InterestsController@store')->name('interests.store');
                    Route::get('/create', 'InterestsController@create')->name('interests.create');
                    Route::get('/{id}/edit', 'InterestsController@edit')->name('interests.edit');
                    Route::put('/{id}', 'InterestsController@update')->name('interests.update');
                    Route::get('/{id}/show', 'InterestsController@show')->name('interests.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'InterestsController@destroy')->name('interests.delete');
                    Route::delete('/{id}', 'InterestsController@destroy')->name('interests.destroy');
                });
                Route::prefix('experiences')->group(static function(){
                    Route::get('/', 'ExperiencesController@index')->name('experiences');
                    Route::post('/', 'ExperiencesController@store')->name('experiences.store');
                    Route::get('/create', 'ExperiencesController@create')->name('experiences.create');
                    Route::get('/{id}/edit', 'ExperiencesController@edit')->name('experiences.edit');
                    Route::put('/{id}', 'ExperiencesController@update')->name('experiences.update');
                    Route::get('/{id}/show', 'ExperiencesController@show')->name('experiences.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'ExperiencesController@destroy')->name('experiences.delete');
                    Route::delete('/{id}', 'ExperiencesController@destroy')->name('experiences.destroy');
                });
                Route::prefix('projects')->group(static function(){
                    Route::get('/', 'ProjectsController@index')->name('projects');
                    Route::post('/', 'ProjectsController@store')->name('projects.store');
                    Route::get('/create', 'ProjectsController@create')->name('projects.create');
                    Route::get('/{id}/edit', 'ProjectsController@edit')->name('projects.edit');
                    Route::put('/{id}', 'ProjectsController@update')->name('projects.update');
                    Route::get('/{id}/show', 'ProjectsController@show')->name('projects.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'ProjectsController@destroy')->name('projects.delete');
                    Route::delete('/{id}', 'ProjectsController@destroy')->name('projects.destroy');
                });
                Route::prefix('skills')->group(static function(){
                    Route::get('/', 'SkillsController@index')->name('skills');
                    Route::post('/', 'SkillsController@store')->name('skills.store');
                    Route::get('/create', 'SkillsController@create')->name('skills.create');
                    Route::get('/{id}/edit', 'SkillsController@edit')->name('skills.edit');
                    Route::put('/{id}', 'SkillsController@update')->name('skills.update');
                    Route::get('/{id}/show', 'SkillsController@show')->name('skills.show');
                    Route::match(['get', 'delete'], '/{id}/delete', 'SkillsController@destroy')->name('skills.delete');
                    Route::delete('/{id}', 'SkillsController@destroy')->name('skills.destroy');
                });
            });
        });
    });
    Route::namespace('App\Http\Controllers')->group(static function() {
        Route::get('/getAllCountries', 'HomeController@getCountryList');
        Route::get('/getVisitorByCountry', 'HomeController@getVisitorByCountry');
    });
});