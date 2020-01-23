<?php
declare(strict_types=1);

namespace MVQN\Slim\Routes;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface as Container;


use Slim\App;

/**
 * Class TemplateController
 *
 * Handles routing and subsequent rendering of Twig templates.
 *
 * @package UCRM\Slim\Controllers\Common
 * @author Ryan Spaeth <rspaeth@mvqn.net>
 * @final
 */
final class TemplateRoute extends BuiltInRoute
{
    /**
     * TemplateController constructor.
     *
     * @param App $app The Slim Application for which to configure routing.
     * @param string $path
     * @param string $containerKey
     */
    public function __construct(App $app, string $path, string $containerKey = "view")
    {
        $this->route = $app->get("/{file:.+}.{ext:twig}",
            function (Request $request, Response $response, array $args) use ($app, $path, $containerKey)
            {
                // Get the file and extension from the matched route.
                $file = $args["file"] ?? "index";
                $ext = $args["ext"] ?? "html";

                // Interpolate the absolute path to the static HTML file or Twig template.
                $templates = rtrim($path, "/") . "/$file.$ext";

                // Get a local reference to the Twig template renderer.
                $twig = $app->getContainer()->get($containerKey);

                // Assemble some standard data to send along to the Twig template!
                $data = [
                    "route" => $request->getAttribute("vRoute"),
                    "query" => $request->getAttribute("vQuery"),
                    "user"  => $request->getAttribute("user"),
                ];

                // IF the file exists exactly as specified...
                if (file_exists($templates) && !is_dir($templates))
                {
                    // THEN render the file.
                    return $twig->render($response, "$file.$ext", $data);
                    //$response = $app->getResponseFactory()->createResponse();
                    //$response->getBody()->write($twig->render("$file.$ext", $data));
                    //return $response;
                }
                else
                {
                    // NOTE: Inside any route closure, $this refers to the Application's Container.
                    /** @var Container $container */
                    $container = $this;

                    // OTHERWISE, return the default 404 page!
                    return $container->get("notFoundHandler")($request, $response, $data);
                }
            }
        )->setName(TemplateRoute::class);

    }

}