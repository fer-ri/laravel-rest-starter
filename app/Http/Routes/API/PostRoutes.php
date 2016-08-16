<?php

$api->group(['middleware' => 'jwt.auth'], function ($api) {
    $api->get('/posts', 'PostController@index');
    $api->post('/posts', 'PostController@store');
    $api->get('/posts/{uuid}', 'PostController@show');
    $api->put('/posts/{uuid}', 'PostController@update');
    $api->delete('/posts/{uuid}', 'PostController@destroy');
});
