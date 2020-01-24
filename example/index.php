<?php
declare(strict_types=1);
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/bootstrap.php";

use MVQN\Slim\DefaultApp;
use MVQN\Slim\Middleware\Authentication\AuthenticationHandler;
use MVQN\Slim\Middleware\Authentication\Authenticators\CallbackAuthenticator;
use MVQN\Slim\Middleware\Authentication\Authenticators\FixedAuthenticator;
use MVQN\Slim\Routes\AssetRoute;
use MVQN\Slim\Routes\ScriptRoute;
use MVQN\Slim\Routes\TemplateRoute;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Use an immediately invoked function here, to avoid global namespace pollution...
 *
 * @author Ryan Spaeth <rspaeth@mvqn.net>
 *
 */
(function() use ($app)
{

    // Define app routes
    $app->get('/hello/{name}', function (Request $request, Response $response, $args): Response {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");
        //var_dump($request->getAttributes());
        return $response;
    })
        ->add(new AuthenticationHandler($app))
        ->add(new CallbackAuthenticator(
            function(Request $request): bool
            {
                //var_dump($request);
                return false;
            }
        ));

    $app->map(["post", "patch"],"/test", function (Request $request, Response $response, $args): Response {
        $response->getBody()->write("TEST");
        return $response;
    });


    $app->get("[/]", function (Request $request, Response $response, $args): Response {
        $response->getBody()->write("HOME");
        return $response;
    })->setName("home");




    // Run app
    $app->run();



})();

