<?php

$api->group(['middleware' => 'api.auth'], function ($api) {
    $api->get('/permissions', 'PermissionController@index');
    $api->post('/permissions', 'PermissionController@store');
    $api->get('/permissions/{uuid}', 'PermissionController@show');
    $api->put('/permissions/{uuid}', 'PermissionController@update');
    $api->delete('/permissions/{uuid}', 'PermissionController@destroy');
});
