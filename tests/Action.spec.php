<?php

use Ellipse\Handlers\Action;

describe('Action', function () {

    describe('->getClass()', function () {

        it('should return the class from the action string without attributes', function () {

            $action = new Action('Controller@index');

            $test = $action->getClass();

            expect($test)->to->be->equal('Controller');

        });

        it('should return the class from the action string with attributes', function () {

            $action = new Action('Controller@index:attribute');

            $test = $action->getClass();

            expect($test)->to->be->equal('Controller');

        });

    });

    describe('->getMethod()', function () {

        it('should return the method from the action string without attributes', function () {

            $action = new Action('Controller@index');

            $test = $action->getMethod();

            expect($test)->to->be->equal('index');

        });

        it('should return the method from the action string with attributes', function () {

            $action = new Action('Controller@index:attribute');

            $test = $action->getMethod();

            expect($test)->to->be->equal('index');

        });

    });

    describe('->getAttributes()', function () {

        it('should return an empty array when there is no attributes', function () {

            $action = new Action('Controller@index');

            $test = $action->getAttributes();

            expect($test)->to->be->equal([]);

        });

        it('should return the attributes from the action string with attributes', function () {

            $action = new Action('Controller@index:a1,a2');

            $test = $action->getAttributes();

            expect($test)->to->be->equal(['a1', 'a2']);

        });

        it('should return the attributes from the action string with attributes and spaces', function () {

            $action = new Action('Controller@index:a1 ,a2,  a3');

            $test = $action->getAttributes();

            expect($test)->to->be->equal(['a1', 'a2', 'a3']);

        });

    });

});
