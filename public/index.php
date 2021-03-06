<?php

use PhpBoot\Docgen\Swagger\Swagger;
use PhpBoot\Docgen\Swagger\SwaggerProvider;
use PhpBoot\Application;
use PhpBoot\Controller\Hooks\Cors;
use PhpBoot\Docgen\Swagger\Schemas\ExternalDocumentationObject;

ini_set('date.timezone', 'Asia/Shanghai');

require __DIR__ . '/../vendor/autoload.php';

//Load the configuration

$app = Application::createByDefault(
    __DIR__ . '/../config/config.php'
);

// Support Core cross-domain access, if you want to turn off this feature, just comment out this piece of code
//{{
$app->setGlobalHooks([Cors::class]);
//}}


// Interface document automatically export function, if you want to turn off this feature, just comment out this piece of code
//{{
SwaggerProvider::register($app, function (Swagger $swagger) use ($app) {
    $swagger->schemes = ['http'];
    $swagger->host = $app->get('host');
    $swagger->info->title = 'PhpBoot Example';
    $swagger->info->description = "This document is generated by PbpBoot swagger format json, and then rendered by the Swagger UI web.";
    $swagger->externalDocs = new ExternalDocumentationObject();
    $swagger->externalDocs->description = 'Interface code';
    $swagger->externalDocs->url = 'https://github.com/Kambaa/phpboot-example';
});
//}}
$app->loadRoutesFromPath(__DIR__ . '/../App/Controllers', 'App\\Controllers');

//Execute the request
$app->dispatch();