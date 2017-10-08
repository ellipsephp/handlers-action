<?php

use Ellipse\Container\ReflectionContainer;

use Ellipse\Handlers\ActionFactory;
use Ellipse\Handlers\ActionHandler;

describe('ActionFactory', function () {

    beforeEach(function () {

        $this->container = Mockery::mock(ReflectionContainer::class);

        $this->factory = new ActionFactory($this->container);

    });

    afterEach(function () {

        Mockery::close();

    });

    describe('->canHandle()', function () {

        it('should return true for strings containing @', function () {

            $test = $this->factory->canHandle('Controller@index');

            expect($test)->to->be->true();

        });

        it('should return false for anything else', function () {

            $test = $this->factory->canHandle('test');

            expect($test)->to->be->false();

        });

    });

    describe('->__invoke()', function () {

        it('should return an instance of ActionHandler', function () {

            $test = ($this->factory)('Controller@index');

            expect($test)->to->be->an->instanceof(ActionHandler::class);

        });

    });

});
