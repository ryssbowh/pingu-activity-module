<?php

Route::group(['middleware' => 'web', 'prefix' => 'activity', 'namespace' => 'Modules\Activity\Http\Controllers'], function()
{
    Route::get('/', 'ActivityController@index');
});
