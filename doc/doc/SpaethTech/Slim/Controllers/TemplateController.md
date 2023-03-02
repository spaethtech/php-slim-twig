---
title: \SpaethTech\Slim\Controllers\TemplateController
footer: false
---

# TemplateController

Class TemplateController

Handles routing and subsequent rendering of Twig templates.

* Full name: `\SpaethTech\Slim\Controllers\TemplateController`
* Parent class: [Controller](../../../../doc.md)
* This class is marked as **final** and can't be subclassed



## Methods

### __construct

TemplateController constructor.

```php
public TemplateController::__construct(\SpaethTech\Slim\Application $app, string $path, string $twigContainerKey = &quot;view&quot;): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `app` | **\SpaethTech\Slim\Application** | The Slim Application for which to configure routing. |
| `path` | **string** | The absolute path to the templates directory. |
| `twigContainerKey` | **string** | An optional container key, if the default key &quot;view&quot; is not used. |


**Return Value:**





---
### __invoke



```php
public TemplateController::__invoke(\SpaethTech\Slim\Application $app): \Slim\Interfaces\RouteGroupInterface
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `app` | **\SpaethTech\Slim\Application** |  |


**Return Value:**





---


---
> Automatically generated from source code comments on 2023-03-01 using [phpDocumentor](http://www.phpdoc.org/) and [dmarkic/phpdoc3-template-md](https://github.com/dmarkic/phpdoc3-template-md)
