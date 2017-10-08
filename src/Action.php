<?php declare(strict_types=1);

namespace Ellipse\Handlers;

class Action
{
    /**
     * The action class.
     *
     * @var string
     */
    private $class;

    /**
     * The action method.
     *
     * @var string
     */
    private $method;

    /**
     * The attributes to use when executing the action.
     *
     * @var array
     */
    private $attributes;

    /**
     * Set up an action from the given action string.
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        $parts = explode(':', $action);

        [$class, $method] = explode('@', trim($parts[0]));

        $attributes = preg_split('/\s*,\s*/', trim($parts[1] ?? ''));

        $this->class = $class;
        $this->method = $method;
        $this->attributes = array_filter($attributes);
    }

    /**
     * Return the action's class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Return the action's method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Return the action's attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
