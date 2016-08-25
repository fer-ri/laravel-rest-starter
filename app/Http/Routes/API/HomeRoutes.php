<?php

$api->get('/hello', function () {
    return response()->json(['hello' => 'world']);
});

$api->group(['middleware' => 'api.auth'], function ($api) {
    $api->get('/restricted-area', function () {
        return response()->json(['restricted' => 'area']);
    });
});
