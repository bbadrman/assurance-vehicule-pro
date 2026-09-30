<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

$kernel = new Kernel($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev', $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($response);