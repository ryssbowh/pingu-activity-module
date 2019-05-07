<?php

Route::group(['middleware' => 'web', 'prefix' => 'activity', 'namespace' => 'Pingu\Activity\Http\Controllers'], function()
{
    Route::get('/', 'ActivityController@index');
});
