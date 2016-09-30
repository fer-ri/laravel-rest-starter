<?php

$api->group(['middleware' => 'api.auth'], function ($api) {
    $api->get('/roles', 'RoleController@index');
    $api->post('/roles', 'RoleController@store');
    $api->get('/roles/{uuid}', 'RoleController@show');
    $api->put('/roles/{uuid}', 'RoleController@update');
    $api->delete('/roles/{uuid}', 'RoleController@destroy');
});
