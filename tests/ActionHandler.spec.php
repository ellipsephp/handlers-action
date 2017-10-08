<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Container\ReflectionContainer;

use Ellipse\Handlers\Action;
use Ellipse\Handlers\ActionHandler;

describe('ActionHandler', function () {

    beforeEach(function () {

        $this->container = Mockery::mock(ReflectionContainer::class);
        $this->action = Mockery::mock(Action::class);
        $this->namespace = '\Test\Controllers';

        $this->handler = new ActionHandler($this->container, $this->action, $this->namespace);

    });

    afterEach(function () {

        Mockery::close();

    });

    it('should implements RequestHandlerInterface', function () {

        expect($this->handler)->to->be->an->instanceof(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        it('should return the value returned by the action', function () {

            $request = Mockery::mock(ServerRequestInterface::class);
            $response = Mockery::mock(ResponseInterface::class);

            $class = 'DummyController';
            $fqcn = $this->namespace . '\\' . $class;
            $method = 'index';
            $instance = new class { public function index() {} };

            $cb = [$instance, $method];
            $attributes = ['a1', 'a2'];
            $overrides = [ServerRequestInterface::class => $request];

            $this->action->shouldReceive('getClass')->once()
                ->andReturn($class);

            $this->action->shouldReceive('getMethod')->once()
                ->andReturn($method);

            $this->action->shouldReceive('getAttributes')->once()
                ->andReturn($attributes);

            $request->shouldReceive('getAttribute')->once()
                ->with('a1')
                ->andReturn('v1');

            $request->shouldReceive('getAttribute')->once()
                ->with('a2')
                ->andReturn('v2');

            $this->container->shouldReceive('make')->once()
                ->with($fqcn, $overrides, ['v1', 'v2'])
                ->andReturn($instance);

            $this->container->shouldReceive('call')->once()
                ->with($cb, $overrides, ['v1', 'v2'])
                ->andReturn($response);

            $test = $this->handler->handle($request);

            expect($test)->to->be->equal($response);

        });

    });

});
