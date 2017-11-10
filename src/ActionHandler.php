<?php declare(strict_types=1);

namespace Ellipse\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Container\ReflectionContainer;

class ActionHandler implements RequestHandlerInterface
{
    /**
     * The reflection container wrapped around the application container.
     *
     * @var \Ellipse\Container\ReflectionContainer
     */
    private $container;

    /**
     * The action to execute.
     *
     * @var \Ellipse\Resolvers\Action
     */
    private $action;

    /**
     * The controllers namespace.
     *
     * @var string
     */
    private $namespace;

    /**
     * Set up an action request handler with the reflection container wrapped
     * around the application container, the given action and the given
     * controllers namespace.
     *
     * @param \Ellipse\Container\ReflectionContainer    $container
     * @param \Ellipse\Router\Action                    $action
     * @param string                                    $namespace
     */
    public function __construct(ReflectionContainer $container, Action $action, string $namespace = '')
    {
        $this->container = $container;
        $this->action = $action;
        $this->namespace = $namespace;
    }

    /**
     * Execute the action and return its response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // get the container and the namespace of the controller class.
        $container = $this->container;
        $namespace = $this->namespace;

        // get the class, the method and the attributes from the action.
        $class = $this->action->getClass();
        $method = $this->action->getMethod();
        $attributes = $this->action->getAttributes();

        // get the fully qualified class name of the controller class.
        $fqcn = $namespace == '' ? $class : $namespace . '\\' . $class;

        // get the attributes values.
        $attributes = array_map([$request, 'getAttribute'], $attributes);

        // make an instance of the controller. Eventually inject the current
        // request and the attributes.
        $overrides = [ServerRequestInterface::class => $request];

        $instance = $container->make($fqcn, $overrides, $attributes);

        // call the action's method with the current request and attributes.
        return $container->call([$instance, $method], $overrides, $attributes);
    }
}
