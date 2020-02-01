<?php
declare(strict_types=1);

namespace MVQN\Slim\Routes;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface as Container;


use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\Route;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Loader\FilesystemLoader;

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
     * @param string $path The absolute path to the templates directory.
     * @param string $twigContainerKey An optional container key, if the default key "view" is not used.
     */
    public function __construct(App $app, string $path, string $twigContainerKey = "view")
    {
        // Add the template route.
        $this->route = $app->get("/{file:.+}.{ext:twig}",
            function (Request $request, Response $response, array $args) use ($app, $path, $twigContainerKey)
            {
                /** @var Container $container */
                $container = $this;

                // Get the file and extension from the matched route.
                list($file, $ext) = array_values($args);

                // Interpolate the absolute path to the Twig template.
                $template = rtrim($path, "/") . "/$file.$ext";

                // Get local references to the Twig Environment and Loader.
                /** @var Twig $twig */
                $twig = $container->get($twigContainerKey);

                /** @var FilesystemLoader $loader */
                $loader = $twig->getLoader();

                // IF the TemplateRoute's path is not already in the Loader's list of paths, THEN add it!
                if(!in_array(realpath($path), $loader->getPaths()))
                    $loader->addPath(realpath($path));

                // Assemble some standard data to send along to the Twig template!
                $data = [
                    "attributes" => $request->getAttributes(),
                ];

                // IF the template file exists AND is not a directory...
                if (file_exists($template) && !is_dir($template))
                {
                    // ...THEN render it!
                    return $twig->render($response, "$file.$ext", $data);
                }
                else
                {
                    // OTHERWISE, return a HTTP 404 Not Found!
                    throw new HttpNotFoundException($request);
                }
            }
        )->setName(TemplateRoute::class);

    }

}
