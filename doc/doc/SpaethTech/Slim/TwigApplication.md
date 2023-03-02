---
title: \SpaethTech\Slim\TwigApplication
footer: false
---

# TwigApplication

Class DefaultApp



* Full name: `\SpaethTech\Slim\TwigApplication`
* Parent class: [Application](../../../doc.md)



## Methods

### __construct

DefaultApp constructor.

```php
public TwigApplication::__construct(\Psr\Http\Message\ResponseFactoryInterface $responseFactory, \Psr\Container\ContainerInterface|null $container, \Slim\Interfaces\CallableResolverInterface|null $callableResolver = null, \Slim\Interfaces\RouteCollectorInterface|null $routeCollector = null, \Slim\Interfaces\RouteResolverInterface|null $routeResolver = null, \Slim\Interfaces\MiddlewareDispatcherInterface|null $middlewareDispatcher = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `responseFactory` | **\Psr\Http\Message\ResponseFactoryInterface** |  |
| `container` | **\Psr\Container\ContainerInterface|null** |  |
| `callableResolver` | **\Slim\Interfaces\CallableResolverInterface|null** |  |
| `routeCollector` | **\Slim\Interfaces\RouteCollectorInterface|null** |  |
| `routeResolver` | **\Slim\Interfaces\RouteResolverInterface|null** |  |
| `middlewareDispatcher` | **\Slim\Interfaces\MiddlewareDispatcherInterface|null** |  |


**Return Value:**





---
### useTwigTemplateEngine

Adds and configures the Twig middleware.

```php
public TwigApplication::useTwigTemplateEngine(array $paths = [&quot;./views/&quot;], array $options = [], bool $debug = false): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `paths` | **array** |  |
| `options` | **array** |  |
| `debug` | **bool** |  |


**Return Value:**





---
### addDefaultErrorHandlers



```php
public TwigApplication::addDefaultErrorHandlers(bool $displayErrorDetails, bool $logErrors = true, bool $logErrorDetails = true): \Slim\Middleware\ErrorMiddleware
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `displayErrorDetails` | **bool** |  |
| `logErrors` | **bool** |  |
| `logErrorDetails` | **bool** |  |


**Return Value:**





---


---
> Automatically generated from source code comments on 2023-03-01 using [phpDocumentor](http://www.phpdoc.org/) and [dmarkic/phpdoc3-template-md](https://github.com/dmarkic/phpdoc3-template-md)
