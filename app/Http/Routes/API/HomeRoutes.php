<?php

$api->get('/hello', function () {
    return response()->json(['hello' => 'world']);
});

$api->group(['middleware' => 'jwt.auth'], function ($api) {
    $api->get('/restricted-area', function () {
        return response()->json(['restricted' => 'area']);
    });
});
