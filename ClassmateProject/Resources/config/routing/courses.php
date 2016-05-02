<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('courses', new Route('/', array(
    '_controller' => 'ClassMateBundle:Courses:index',
)));

$collection->add('courses_show', new Route('/{id}/show', array(
    '_controller' => 'ClassMateBundle:Courses:show',
)));

$collection->add('courses_new', new Route('/new', array(
    '_controller' => 'ClassMateBundle:Courses:new',
)));

$collection->add('courses_create', new Route(
    '/create',
    array('_controller' => 'ClassMateBundle:Courses:create'),
    array('_method' => 'post')
));

$collection->add('courses_edit', new Route('/{id}/edit', array(
    '_controller' => 'ClassMateBundle:Courses:edit',
)));

$collection->add('courses_update', new Route(
    '/{id}/update',
    array('_controller' => 'ClassMateBundle:Courses:update'),
    array('_method' => 'post|put')
));

$collection->add('courses_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'ClassMateBundle:Courses:delete'),
    array('_method' => 'post|delete')
));

return $collection;
