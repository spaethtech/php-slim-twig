<?php
declare(strict_types=1);

namespace MVQN\Slim;

use DI;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

use MVQN\Slim\Middleware\Handlers\MethodNotAllowedHandler;
use MVQN\Slim\Middleware\Handlers\NotFoundHandler;
use MVQN\Slim\Middleware\Handlers\UnauthorizedHandler;
use MVQN\Slim\Middleware\Routing\QueryStringRouter;
use MVQN\Twig\Extensions\QueryStringRouterExtension;

/**
 * Class DefaultApp
 *
 * @package MVQN\Slim
 * @author Ryan Spaeth <rspaeth@mvqn.net>
 */
class DefaultApp
{
    /**
     * Default application options.
     */
    protected const DEFAULT_OPTIONS = [
        "twig" => [
            "paths" => [],
            "options" => [],
        ]
    ];

    /**
     * Create our default Slim Application with PHP-DI Container, Twig Renderer, Error Handling and custom Middleware.
     *
     * @param array $options Any options to be merged with the default options.
     * @param bool $debug Determines whether or not debug messages and logging will be enabled, defaults to FALSE.
     * @return App A Slim Application.
     */
    public static function create(array $options = [], bool $debug = false): App
    {
        // Merge any user-supplied options with the defaults.
        $options = array_merge(self::DEFAULT_OPTIONS, $options);

        // Create the application using a PHP-DI Container, as we will be configuring it later.
        $app = AppFactory::createFromContainer($container = new DI\Container());

        #region Container

        // Use Slim's own PSR-7 ResponseFactory.
        $container->set(ResponseFactoryInterface::class, DI\create(ResponseFactory::class));

        // Use our customized Twig instance for template rendering, using the default name "view".
        $container->set("view", function() use ($options, $debug)
        {
            $twig = Twig::create($options["twig"]["paths"], $options["twig"]["options"]);
            //$twig->getEnvironment()->addGlobal("home", "/index.php");

            $twig->addExtension(new QueryStringRouterExtension($_SERVER["SCRIPT_NAME"], [], $debug));
            //QueryStringRouterExtension::addGlobal("user", "Ryan", "ucrm");

            return $twig;
        });

        // NOTE: Add any additional PHP-DI Container configuration here...

        #endregion

        #region Middleware

        // Add Slim's Built-In Routing Middleware.
        $app->addRoutingMiddleware();

        // Configure Slim's Twig Middleware.
        TwigMiddleware::createFromContainer($app);

        // Add our QueryStringRouter Middleware and include any desired options.
        $app->add(new QueryStringRouter("/", ["#/public/#" => "/"]));

        /**
         * Add Error Handling Middleware
         *
         * @param bool $displayErrorDetails Should be set to false in production
         * @param bool $logErrors Parameter is passed to the default ErrorHandler
         * @param bool $logErrorDetails Display error details in error log which can be replaced by any callable.

         * NOTE: This middleware should be added last, as it will not handle any errors for anything added after it!
         */
        $errorMiddleware = $app->addErrorMiddleware($debug, true, true);

        // Add our own HTTP 401 Unauthorized handler.
        $errorMiddleware->setErrorHandler(HttpUnauthorizedException::class, new UnauthorizedHandler($app));

        // Add our own HTTP 404 Not Found handler.
        $errorMiddleware->setErrorHandler(HttpNotFoundException::class, new NotFoundHandler($app));

        // Add our own HTTP 405 Method Not Allowed handler.
        $errorMiddleware->setErrorHandler(HttpMethodNotAllowedException::class, new MethodNotAllowedHandler($app));

        #endregion

        return $app;
    }

}
