<?php
/** @noinspection PhpUnused */
declare(strict_types=1);

namespace MVQN\Twig\Extensions;

use DateTime;
use Exception;
use MVQN\Slim\Middleware\Routing\QueryStringRouter;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class QueryStringRouterExtension
 *
 * @package MVQN\Twig
 * @author Ryan Spaeth <rspaeth@mvqn.net>
 */
class QueryStringRouterExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var array An array of extension-wide global values.
     */
    protected static $globals = [
        "app" => [
            // NOTE: Add any desired fixed globals here...
        ]
    ];

    /**
     * QueryStringRouterExtension constructor.
     *
     * @param string $controller The front-controller script as an URL prefix, defaults to "/index.php".
     * @param array $globals An optional array of global values to be made available to all Twig templates.
     * @param bool $debug Determines whether or not to display additional debug messages, defaults to FALSE.
     */
    public function __construct(string $controller = "/index.php", array $globals = [], bool $debug = false)
    {
        self::$globals["app"]["baseScript"] = $controller;
        self::$globals["app"]["debug"] = $debug;

        foreach($globals as $key => $value)
            self::$globals["app"][$key] = $value;
    }

    /**
     * Gets the name of the extension.
     *
     * @return string The name of the extension.
     */
    public function getName(): string
    {
        return "QueryStringRouterExtension";
    }

    /**
     * Gets all token parsers, provided by this extension.
     *
     * @return TokenParserInterface[] An array of {@see TokenParserInterface TokenParser} objects.
     */
    public function getTokenParsers(): array
    {
        return [];
    }

    #region FILTERS

    /**
     * Gets all filters, provided by this extension.
     *
     * @return TwigFilter[] An array of {@see TwigFilter} objects.
     */
    public function getFilters(): array
    {

        return [
            new TwigFilter("uncached", [$this, "uncached"]),
        ];
    }

    /**
     * @param string $path
     *
     * @return string
     * @throws Exception
     * @noinspection PhpUnusedLocalVariableInspection
     */
    public function uncached(string $path)
    {

        $uncachedPath = "";

        //if(Strings::contains($path, "?"))
        if (strpos($path, "?") !== false)
        {
            $parts = explode("?", $path);

            $route = QueryStringRouter::extractRouteFromQueryString($parts[1]);

            parse_str($parts[1], $query);

            $query["v"] = (new DateTime())->getTimestamp();
            $queryParts = [];

            foreach($query as $key => $value)
                $queryParts[] = "$key=$value";

            $uncachedPath = $parts[0]."?".($route ? $route.($queryParts ? "&" : "") : "").implode("&", $queryParts);
        }
        else
        {
            $uncachedPath = $path."?v=".(new DateTime())->getTimestamp();
        }

        return $uncachedPath;
    }

    #endregion

    #region FUNCTIONS

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction("link", [$this, "link"]),
            new TwigFunction("dump", function($data) { if(self::$globals["app"]["debug"]) var_dump($data); }),
        ];
    }

    /**
     * @param string $path
     * @return string
     * @throws Exception
     * @noinspection PhpUnusedLocalVariableInspection
     */
    public function link(string $path /*, bool $relative = true */): string
    {
        // Temporarily remove any URL fragment...
        $fragment = "";
        if(strpos($path, "#") !== false)
        {
            $fragment = substr($path, strpos($path, "#"));
            $path = str_replace($fragment, "", $path);
        }

        // Split the provided path into path and query string (if provided).
        list($path, $query) = $path !== "" ? explode("?", strpos("?", $path) !== false ? $path : "$path?") : ["", ""];

        $baseUrl = self::$globals["app"]["baseUrl"] ?? "";
        $baseScript = self::$globals["app"]["baseScript"] ?? "";

        $path = ($path === "/" && $baseScript !== "") || $path === "" ? "" : ($baseScript !== "" ? "?" : "")."$path";

        $link = /* $relative ? */ $baseScript.$path /* : $baseUrl.$baseScript.$path */;
        $link .= $query !== "" ? ($baseScript !== "" && $path !== "" ? "&" : "?")."$query" : "";

        return $link.$fragment ?: $path;
    }

    #endregion



    public function getGlobals(): array
    {
        return self::$globals;
    }

    public static function addGlobal(string $name, $value, string $namespace = "app")
    {


        self::$globals[$namespace][$name] = $value;
    }

}
