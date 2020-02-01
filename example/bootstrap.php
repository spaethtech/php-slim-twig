<?php
declare(strict_types=1);
require_once __DIR__ . "/../vendor/autoload.php";

use MVQN\Slim\DefaultApp;
use MVQN\Slim\Routes\AssetRoute;
use MVQN\Slim\Routes\ScriptRoute;
use MVQN\Slim\Routes\TemplateRoute;

// Create our default Application and enable debug mode.
$app = DefaultApp::create([], true);

// NOTE: This Controller handles any static assets (i.e. png, jpg, html, pdf, etc.)...
(new AssetRoute($app, __DIR__."/assets/"));

// NOTE: This Controller handles any PHP scripts...
(new ScriptRoute($app, __DIR__."/src/"));

// NOTE: This Controller handles any Twig templates...
(new TemplateRoute($app, __DIR__."/views/"));

// NOTE: Authenticators can be added to any of the above Route handlers.
