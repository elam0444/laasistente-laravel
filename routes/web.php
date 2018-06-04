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
Route::group(['middleware' => ['auth']], function () {

    Route::get('/', ['as' => 'home', 'uses' => 'Auth\AuthenticationController@index']);

    Route::group(['prefix' => 'user'], function () {

        Route::get(
            '{hashedUserId}',
            [
                'as' => 'user.details',
                'middleware' => 'hashids',
                'uses' => 'Users\UserController@getProfile'
            ]
        );

        Route::post(
            '{hashedUserId}',
            [
                'as' => 'user.details.update',
                'middleware' => 'hashids',
                'uses' => 'Users\UserController@updateProfile'
            ]
        );
    });

    Route::group(['prefix' => 'requirement-list'], function () {
        Route::get(
            '/',
            [
                'as' => 'requirement.list',
                'middleware' => 'hashids',
                'uses' => 'Requirements\RequirementController@getRequirements'
            ]
        );

        Route::get(
            '/data',
            [
                'as' => 'requirement.list.data',
                'middleware' => 'hashids',
                'uses' => 'Requirements\RequirementController@getRequirementsData'
            ]
        );
    });

    Route::group(['prefix' => 'requirement'], function () {

        Route::get(
            '/',
            [
                'as' => 'requirement.new',
                'middleware' => 'hashids',
                'uses' => 'Requirements\RequirementController@getRequirement'
            ]
        );

        Route::get(
            '{hashedRequirementId}',
            [
                'as' => 'requirement.edit',
                'middleware' => 'hashids',
                'uses' => 'Requirements\RequirementController@getRequirement'
            ]
        );

        Route::post(
            '/',
            [
                'as' => 'requirement.create',
                'uses' => 'Requirements\RequirementController@createRequirement'
            ]
        );

        Route::post(
            '{hashedRequirementId}',
            [
                'as' => 'requirement.update',
                'middleware' => 'hashids',
                'uses' => 'Requirements\RequirementController@updateRequirement'
            ]
        );
    });

    Route::group(['prefix' => 'service'], function () {

        Route::get(
            '/new',
            [
                'as' => 'service.new',
                'middleware' => 'hashids',
                'uses' => 'Services\ServiceController@getService'
            ]
        );

        Route::post(
            '/',
            [
                'as' => 'service.create',
                'uses' => 'Services\ServiceController@createService'
            ]
        );

        Route::get(
            '{hashedServiceId}',
            [
                'as' => 'service.edit',
                'middleware' => 'hashids',
                'uses' => 'Services\ServiceController@getRequirement'
            ]
        );
    });

    Route::group(['prefix' => 'service'], function () {
        Route::get(
            '/',
            [
                'as' => 'service.list',
                'middleware' => 'hashids',
                'uses' => 'Services\ServiceController@getServices'
            ]
        );
    });

    Route::group(['prefix' => 'requirement-service'], function () {
        Route::post(
            '/{hashedRequirementServiceId}/status',
            [
                'as' => 'requirement.service.status.update',
                'middleware' => 'hashids',
                'uses' => 'Requirements\RequirementController@setStatus'
            ]
        );

        Route::post(
            '/{hashedRequirementServiceId}/associate',
            [
                'as' => 'requirement.service.associate.update',
                'middleware' => 'hashids',
                'uses' => 'Requirements\RequirementController@setAssociate'
            ]
        );
    });

});

Route::group(['middleware' => ['guest'], 'namespace' => 'Auth'], function () {
    Route::get('/auth/login', ['as' => 'auth.login', 'uses' => 'AuthenticationController@getLogin']);
    Route::post('/auth/login', 'AuthenticationController@postLogin');

    Route::get(
        '/signup',
        [
            'as' => 'clients.sign_up',
            'uses' => 'AuthenticationController@getSignUpView'
        ]
    );

    Route::post(
        '/signup',
        [
            'as' => 'clients.signup.save',
            'uses' => 'AuthenticationController@postSignUp'
        ]
    );

});

Route::get(
    '/address',
    [
        'as' => 'system.address',
        'uses' => 'Auth\AuthenticationController@getAddress'
    ]
);

Route::get(
    '/states',
    [
        'as' => 'system.states',
        'uses' => 'Auth\AuthenticationController@getStates'
    ]
);

Route::get(
    '/cities',
    [
        'as' => 'system.cities',
        'uses' => 'Auth\AuthenticationController@getCities'
    ]
);


Route::get('/auth/logout', [
    'as' => 'auth.logout',
    'uses' => 'Auth\AuthenticationController@getLogout'
]);