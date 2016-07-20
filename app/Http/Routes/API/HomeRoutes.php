<?php

$api->get('/hello', function () {
    return response()->json(['hello' => 'world']);
});
