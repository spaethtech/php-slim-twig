<?php
declare(strict_types=1);

namespace MVQN\Slim;

use DI;
use MVQN\Slim\Middleware\Authentication\Authenticators\FixedAuthenticator;
use MVQN\Slim\Middleware\Handlers\MethodNotAllowedHandler;
use MVQN\Slim\Middleware\Handlers\NotFoundHandler;
use MVQN\Slim\Middleware\Handlers\UnauthorizedHandler;
use MVQN\Slim\Middleware\Routing\QueryStringRouter;
use MVQN\Twig\Extensions\QueryStringRouterExtension;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

class DefaultApp
{


    public static function create(array $options = [], bool $debug = false)
    {
        $defaultOptions = [
            "twig" => [
                "paths" => [], // dirname(debug_backtrace()[0]["file"])."/views/"
                "options" => [],
            ]
        ];

        $options = array_merge($defaultOptions, $options);



        AppFactory::setContainer($container = new DI\Container());
        $app = AppFactory::create();

        // Necessary for injection of the base App, as a ResponseFactory is required to function properly.
        $container->set(ResponseFactoryInterface::class, DI\create(ResponseFactory::class));

        // Add Routing Middleware.
        $app->addRoutingMiddleware();

        $container->set("view", function() use ($options, $debug)
        {
            $twig = Twig::create($options["twig"]["paths"], $options["twig"]["options"]);
            //$twig->getEnvironment()->addGlobal("home", "/index.php");

            $twig->addExtension(new QueryStringRouterExtension($_SERVER["SCRIPT_NAME"], [], $debug));
            //QueryStringRouterExtension::addGlobal("user", "Ryan", "ucrm");

            return $twig;
        });

        TwigMiddleware::createFromContainer($app);




        $app->add(new QueryStringRouter("/", ["#/public/#" => "/"]));






        /**
         * Add Error Handling Middleware
         *
         * @param bool $displayErrorDetails Should be set to false in production
         * @param bool $logErrors Parameter is passed to the default ErrorHandler
         * @param bool $logErrorDetails Display error details in error log which can be replaced by a callable of your choice.

         * Note: This middleware should be added last, as it will not handle any exceptions/errors for anything added after it!
         */
        $errorMiddleware = $app->addErrorMiddleware($debug, true, true);
        $errorMiddleware->setErrorHandler(HttpUnauthorizedException::class, new UnauthorizedHandler($app)); // 401
        $errorMiddleware->setErrorHandler(HttpNotFoundException::class, new NotFoundHandler($app)); // 404
        $errorMiddleware->setErrorHandler(HttpMethodNotAllowedException::class, new MethodNotAllowedHandler($app)); // 405

        return $app;
    }



}
