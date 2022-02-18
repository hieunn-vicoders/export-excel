<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'admin'], function ($api) {
        $api->get('export/{type}', 'VCComponent\Laravel\Export\Http\Controllers\Api\Admin\ExportController@export');
    });
});
