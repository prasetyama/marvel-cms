<?php

use Symfony\Component\HttpFoundation\Request;

//require __DIR__.'/../app/autoload.php'; ORI
//include_once __DIR__.'/../app/bootstrap.php.cache'; ORI

$loader = require_once __DIR__.'/../app/bootstrap.php.cache'; //New

require_once __DIR__.'/../app/AppKernel.php'; //New

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
