---
title: \SpaethTech\Twig\Extensions\QueryStringRouterExtension
footer: false
---

# QueryStringRouterExtension

Class QueryStringRouterExtension



* Full name: `\SpaethTech\Twig\Extensions\QueryStringRouterExtension`
* Parent class: [AbstractExtension](../../../../docs.md)
* This class implements: \Twig\Extension\GlobalsInterface



## Methods

### __construct

QueryStringRouterExtension constructor.

```php
public QueryStringRouterExtension::__construct(\SpaethTech\Slim\TwigApplication $app, string $controller = &quot;/index.php&quot;, array $globals = [], bool $debug = false): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `app` | **\SpaethTech\Slim\TwigApplication** | The {@see \SpaethTech\Twig\Extensions\Application} on which this Twig Extension operates. |
| `controller` | **string** | The front-controller script as an URL prefix, defaults to &quot;/index.php&quot;. |
| `globals` | **array** | An optional array of global values to be made available to all Twig templates. |
| `debug` | **bool** | Determines whether or not to display additional debug messages, defaults to FALSE. |


**Return Value:**





---
### getName

Gets the name of the extension.

```php
public QueryStringRouterExtension::getName(): string
```









**Return Value:**

The name of the extension.



---
### getTokenParsers

Gets all token parsers, provided by this extension.

```php
public QueryStringRouterExtension::getTokenParsers(): \Twig\TokenParser\TokenParserInterface[]
```









**Return Value:**

An array of {@see \Twig\TokenParser\TokenParserInterface} objects.



---
### getFilters

Gets all filters, provided by this extension.

```php
public QueryStringRouterExtension::getFilters(): \Twig\TwigFilter[]
```









**Return Value:**

An array of {@see \Twig\TwigFilter} objects.



---
### uncached



```php
public QueryStringRouterExtension::uncached(string $path): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | **string** |  |


**Return Value:**





---
### getFunctions



```php
public QueryStringRouterExtension::getFunctions(): array
```









**Return Value:**





---
### link



```php
public QueryStringRouterExtension::link(string $path): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | **string** |  |


**Return Value:**





---
### route

Gets the url for a named route.

```php
public QueryStringRouterExtension::route(string $name, array $data = [], array $params = []): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | **string** | The name of the route to find. |
| `data` | **array** | Optional Route placeholders. |
| `params` | **array** | Optional Query parameters. |


**Return Value:**





---
### getGlobals



```php
public QueryStringRouterExtension::getGlobals(): array
```









**Return Value:**





---
### addGlobal



```php
public static QueryStringRouterExtension::addGlobal(string $name, mixed $value, string $namespace = &quot;app&quot;): mixed
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | **string** |  |
| `value` | **mixed** |  |
| `namespace` | **string** |  |


**Return Value:**





---


---
> Automatically generated from source code comments on 2023-03-01 using
> [phpDocumentor](http://www.phpdoc.org/) for [spaethtech/php-monorepo](https://github.com/spaethtech/php-monorepo)
