<?php
declare(strict_types=1);
require_once __DIR__ . "/../vendor/autoload.php";

use DI\Container;
use MVQN\Slim\DefaultApp;
use MVQN\Slim\Middleware\Authentication\AuthenticationHandler;
use MVQN\Slim\Routes\AssetRoute;
use MVQN\Slim\Routes\ScriptRoute;
use MVQN\Slim\Routes\TemplateRoute;
use MVQN\Slim\Middleware\Authentication\Authenticators\FixedAuthenticator;

$app = DefaultApp::create([], true);

// NOTE: This Controller handles any static assets (i.e. png, jpg, html, pdf, etc.)...
(new AssetRoute($app, __DIR__."/assets/"));
// NOTE: If one or more Authenticators are provided, they will override the application-level Authenticator(s).
//->add(new AuthenticationHandler($app))
//->add(new FixedAuthenticator(true));

// NOTE: This Controller handles any Twig templates...
(new TemplateRoute($app, __DIR__."/views/", /* [ "user" => "sessionUser"], */ ))
    ->add(new AuthenticationHandler($app))
    //->add(new FixedAuthenticator(false))
    ->add(new FixedAuthenticator(true));

// NOTE: This Controller handles any PHP scripts...
(new ScriptRoute($app, __DIR__."/src/"));
