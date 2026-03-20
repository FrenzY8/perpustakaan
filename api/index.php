<?php

$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

foreach ([
    $storagePath . '/framework/views',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/cache',
    $storagePath . '/logs',
    $cachePath
] as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");
putenv("SESSION_DRIVER=file");
putenv("SESSION_PATH=$storagePath/framework/sessions");
putenv("CACHE_DRIVER=file");
putenv("LOG_CHANNEL=stderr");

putenv("APP_SERVICES_CACHE=$cachePath/services.php");
putenv("APP_PACKAGES_CACHE=$cachePath/packages.php");
putenv("APP_CONFIG_CACHE=$cachePath/config.php");
putenv("APP_ROUTES_CACHE=$cachePath/routes.php");

require __DIR__ . '/../public/index.php';