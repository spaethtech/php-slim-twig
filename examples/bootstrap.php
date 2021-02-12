<?php
declare(strict_types=1);
require_once __DIR__ . "/../vendor/autoload.php";

use rspaeth\Slim\Controllers\TemplateController;
use rspaeth\Slim\DefaultApp;
use rspaeth\Slim\Controllers\AssetController;
use rspaeth\Slim\Controllers\ScriptController;
use rspaeth\Slim\Middleware\Authentication\AuthenticationHandler;
use rspaeth\Slim\Middleware\Authentication\Authenticators\FixedAuthenticator;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Psr7\Factory\ResponseFactory;

// Create and configure our DI Container.
$container = new DI\Container();
//$container->set(ContainerInterface::class, DI\create(DI\Container::class)); // NOT necessary, PHP-DI figures it out!
$container->set(ResponseFactoryInterface::class, DI\create(ResponseFactory::class));
$container->set(App::class, DI\autowire(DefaultApp::class));
$container->set(AuthenticationHandler::class, DI\create(AuthenticationHandler::class)->constructor(DI\get(App::class)));


/** @noinspection PhpUnhandledExceptionInspection */
// Create our default Application.
$app = $container->get(DefaultApp::class);

// Add and configure our routing middleware.
$app->addRoutingMiddleware();
$app->addQueryStringRoutingMiddleware("/", ["#^/public/#" => "/"]);

// Add and configure the Twig middleware.
$app->addTwigRenderingMiddleware([ __DIR__ . "/views/" ], [ "cache" => __DIR__ . "/views/.cache/"  ], true);

// Add an application-level Authenticator.
$app->addAuthenticator(new FixedAuthenticator(true));

// NOTE: This Controller handles any static assets (i.e. png, jpg, html, pdf, etc.)...
$app->addController(new AssetController($app, __DIR__."/assets/"));

// NOTE: This Controller handles any PHP scripts...
$app->addController(new ScriptController($app, __DIR__ . "/scripts/"));

// NOTE: This Controller handles any Twig templates...
$app->addController(new TemplateController($app, __DIR__."/views/"));

// Add our default error handlers.
// NOTE: Be sure to set the "displayErrorDetails" to false in production!
$app->addDefaultErrorHandlers(true, true, true);

