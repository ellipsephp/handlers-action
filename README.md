# Action request handler factory

This package provides an **action request handler factory**  which can be used to produce Psr-15 request handler from an action string.

**Require** php >= 7.1

**Installation** `composer require ellipse/handlers-action`

**Run tests** `./vendor/bin/peridot tests`

- [Using the action request handler factory](#using-the-action-request-handler-factory)

## Using the action request handler factory

Action strings are string containing a class name and method name spaced by `@`. A comma separated list of request attributes to inject can be added, spaced by `:`.

Example: `'App\Controller@index'` or `'App\Controller@show:category_id,post_id'`.

Many things to note:

- The action's controller instance is constructed using [ellipse/container](https://github.com/ellipsephp/container) auto-wiring feature. See [auto-wiring documentation](https://github.com/ellipsephp/container#auto-wiring) for more details.
- The action's method is called using [ellipse/container](https://github.com/ellipsephp/container) callable dependency injection feature. See [callable dependency injection documentation](https://github.com/ellipsephp/container#callable-dependency-injection) for more details.
- When one parameter of the action's controller constructor or action's method is type hinted as `Psr\Http\Message\ServerRequestInterface`, the request currently processed by the request handler is injected
- The non type hinted parameters of the action's controller constructor or action's method will be the specified request attributes, in the order they are listed
- When a `'router.controllers.namespace'` alias is registered in the container, its value will be prepended to all controller class names

```php
<?php

namespace App;

class SomeController
{
    private $response;

    public function __construct(ResponseFactory $response)
    {
        // Dependencies are automatically injected.

        $this->response = $response;
    }

    public function index(ServerRequestInterface $request, $request_attribute)
    {
        // $request is the request available at the time the middleware is being processed.
        // $request_attribute is the first url attribute passed to the Action instance.

        // some processing ...

        // returns a response.
        return $this->response->create();
    }
}
```

```php
<?php

namespace App;

use Simplex\Container;

use Ellipse\Handlers\ActionFactory;

// Get a Psr-11 container.
$container = new Container;

// Register a base controllers namespace.
$container->set('router.controllers.namespace', 'App');

// Get an action factory using this container.
$factory = new ActionFactory($container);

// Create a request handler based on SomeController's index method.
$handler = $factory('SomeController@index:request_attribute');
```
