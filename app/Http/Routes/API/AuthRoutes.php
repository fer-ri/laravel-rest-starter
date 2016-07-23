<?php

$api->post('auth/login', 'AuthController@login');
$api->post('auth/facebook', 'AuthController@LoginWithFacebook');
$api->post('auth/register', 'AuthController@register');
$api->get('auth/activate', 'AuthController@activate');
$api->post('auth/recovery', 'AuthController@recovery');
$api->post('auth/reset', 'AuthController@reset');
$api->get('auth/refresh-token', 'AuthController@refreshToken');

$api->group(['middleware' => 'jwt.auth'], function ($api) {
    $api->get('auth/validate-token', 'AuthController@validateToken');
});
