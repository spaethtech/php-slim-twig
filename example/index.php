<?php
declare(strict_types=1);
require_once __DIR__ . "/bootstrap.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use MVQN\Slim\Middleware\Authentication\AuthenticationHandler;
use MVQN\Slim\Middleware\Authentication\Authenticators\CallbackAuthenticator;
use MVQN\Slim\Middleware\Authentication\Authenticators\FixedAuthenticator;
use Slim\Routing\RouteCollectorProxy;

#region Authenticator (Examples)

$app->group("/auth", function(RouteCollectorProxy $group) use ($app)
{
    $group
        ->get('/none',
            function (Request $request, Response $response, $args): Response
            {
                $response->getBody()->write("Authenticated!");
                return $response;
            });

    $group
        ->get('/fixed',
            function (Request $request, Response $response, $args): Response
            {
                $response->getBody()->write("Authenticated!");
                return $response;
            })
        ->add(new AuthenticationHandler($app))
        ->add(new FixedAuthenticator(true));

    $group
        ->get('/callback',
            function (Request $request, Response $response, $args): Response
            {
                $response->getBody()->write("Authenticated!");
                return $response;
            })
        ->add(new AuthenticationHandler($app))
        ->add(new CallbackAuthenticator(
            function(Request $request): bool
            {
                return true;
            }
        ));

});

#endregion

// NOTE: You can use any valid Slim 4 routes/groups here...

// Handle the default route, with or without the trailing slash...
$app->get("[/]", function (Request $request, Response $response, $args): Response {
    $response->getBody()->write("HOME");
    return $response;
})->setName("home");

// Finally, run the Application!
$app->run();
