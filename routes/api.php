<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => [
        'localize',
        'auth:api'
    ]
], function () {

    Route::get('cases/{caseType}', 'ResponseApiController@index');

    Route::get('case-notes/{caseType}/{id}', 'CaseNotesController@index')->name('case-notes.index');

    Route::post('case-notes/{caseType}/{id}', 'CaseNotesController@store')->name('case-notes.create');

    Route::post('case-notes/{caseType}/{caseId}/{noteId}/star', 'CaseNotesController@star')->name('api.case-notes.star');

    Route::get('upcoming-followups', 'UpcomingFollowupsController@index')->name('api.upcoming-followups');
    Route::get('upcoming-followups/counts', 'UpcomingFollowupsController@counts')->name('api.upcoming-followups.counts');

    Route::get('recent-activities', 'RecentActivitiesController@index')->name('api.recent-activities');

    Route::resource('users', 'UserController')->middleware(['permission:users_management']);

    Route::group(['prefix' => 'users', 'middleware' => ['permission:users_management']], function () {
        Route::post('{user}/activate', 'UserController@activate');
        Route::post('{user}/deactivate', 'UserController@deactivate');
    });

    Route::get('roles', 'RoleController@index');

    Route::get('cards', 'CardsController@index');

    Route::get('/job-openings/{jobOpening}/matches', 'JobOpeningMatchController@matches')->name('api.matches');

    Route::post('/job-openings/{jobOpening}/matches', 'JobOpeningMatchController@store')->name('api.matches');

    Route::get('/job-openings/{jobOpening}/matches/saved', 'JobOpeningMatchController@savedList')->name('api.matches.saved');

    Route::get('/job-seekers/{jobSeeker}/matches', 'JobSeekerController@matches')->name('api.job-seeker.matches');
    Route::get('/job-seekers/{jobSeeker}/candidates', 'JobSeekerController@candidates')->name('api.job-seeker.candidates');
    Route::get('/job-seekers/{jobSeeker}/screening', 'JobSeekerController@screening')->name('api.job-seeker.screening');

    Route::get('/firms/{firm}/matches', 'FirmController@matches')->name('api.firm.matches');
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localize',
    ]
], function () {
    Route::post('auth/login', 'Auth\AuthController@login');
    Route::post('auth/signup', 'Auth\AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('auth/logout', 'Auth\AuthController@logout');
    });
});