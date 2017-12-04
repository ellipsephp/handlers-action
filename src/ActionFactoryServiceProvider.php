<?php declare(strict_types=1);

namespace Ellipse\Handlers;

use Interop\Container\ServiceProviderInterface;

use Ellipse\Container\ReflectionContainer;

class ActionFactoryServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            'router.controllers.namespace' => function () {

                return '';

            },
        ];
    }

    public function getExtensions()
    {
        return [
            HandlerResolver::class => function ($container, HandlerResolver $resolver) {

                $reflection = new ReflectionContainer($container);

                $namespace = $container->get('router.controllers.namespace');

                $factory = new ActionFactory($reflection, $namespace);

                return $resolver->withFactory([$factory, 'canHandle'], $factory);

            },
        ];
    }
}
