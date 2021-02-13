<?php
declare(strict_types=1);

namespace MVQN\Slim\Controllers;

use MVQN\Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface as Container;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;
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
final class TemplateController extends Controller
{
    /**
     * @var string The base path to use when loading templates.
     */
    protected $path;

    /**
     * @var string The container key to use when looking up Twig, defaults to "view".
     */
    protected $twigContainerKey;

    /**
     * TemplateController constructor.
     *
     * @param App $app The Slim Application for which to configure routing.
     * @param string $path The absolute path to the templates directory.
     * @param string $twigContainerKey An optional container key, if the default key "view" is not used.
     */
    public function __construct(App $app, string $path, string $twigContainerKey = "view")
    {
        parent::__construct($app);
        $this->path = $path;
        $this->twigContainerKey = $twigContainerKey;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(App $app): RouteGroupInterface
    {
        // Mapped, in cases where a DI Container replaces the $this context in Closures.
        $self = $this;

        return $this->group("", function(RouteCollectorProxyInterface $group) use ($self)
        {
            $group->map([ "GET" ], "/{file:.+}.{ext:twig}",
                function (Request $request, Response $response, array $args) use ($self)
                {
                    /** @var Container $this */
                    $container = $this;

                    // Get the file and extension from the matched route.
                    list($file, $ext) = array_values($args);

                    // Interpolate the absolute path to the Twig template.
                    $template = rtrim($self->path, "/") . "/$file.$ext";

                    // Get local references to the Twig Environment and Loader.
                    /** @var Twig $twig */
                    $twig = $container->get($self->twigContainerKey);

                    /** @var FilesystemLoader $loader */
                    $loader = $twig->getLoader();

                    // IF the TemplateController's path is not already in the Loader's list of paths, THEN add it!
                    if(!in_array(realpath($self->path), $loader->getPaths()))
                        $loader->addPath(realpath($self->path));

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
            )->setName(TemplateController::class);

        });

    }

}
