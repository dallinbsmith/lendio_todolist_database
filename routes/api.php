<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'v1'], function() {
    Route::group([
        'middleware' => 'auth:api'
      ], function() {
        Route::get('logout', [
            'uses' => 'AuthController@getLogout',
            'as' => 'authentication.logout'
        ]);
        Route::get('user', [
            'uses' => 'AuthController@getUser',
            'as' => 'authentication.user'
        ]);
      });
});
