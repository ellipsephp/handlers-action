<?php declare(strict_types=1);

namespace Ellipse\Handlers;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Container\ReflectionContainer;

class ActionFactory
{
    /**
     * The reflection container wrapped around the application container.
     *
     * @var \Ellipse\Container\ReflectionContainer
    */
    private $container;

    /**
     * The base namespace to prepend to all controller class names.
     *
     * @var string
    */
    private $namespace;

    /**
     * Set up the action handler resolver with the reflection container wrapped
     * around the application container and the controller namespace.
     *
     * @param \Ellipse\Container\ReflectionContainer    $container
     * @param string                                    $namespace
     */
    public function __construct(ReflectionContainer $container, $namespace = '')
    {
        $this->container = $container;
        $this->namespace = $namespace;
    }

    /**
     * Return whether the element is an action string.
     *
     * @param mixed $element
     * @return bool
     */
    public function canHandle($element): bool
    {
        return is_string($element) && strpos($element, '@') !== false;
    }

    /**
     * Create an action request handler from the action string.
     *
     * @param string $element
     * @return \Interop\Http\Server\RequestHandlerInterface
     */
    public function __invoke(string $element): RequestHandlerInterface
    {
        return new ActionHandler($this->container, new Action($element), $this->namespace);
    }
}
