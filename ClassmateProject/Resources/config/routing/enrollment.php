<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('enrollment', new Route('/', array(
    '_controller' => 'ClassMateBundle:Enrollment:index',
)));

$collection->add('enrollment_show', new Route('/{id}/show', array(
    '_controller' => 'ClassMateBundle:Enrollment:show',
)));

$collection->add('enrollment_new', new Route('/new', array(
    '_controller' => 'ClassMateBundle:Enrollment:new',
)));

$collection->add('enrollment_create', new Route(
    '/create',
    array('_controller' => 'ClassMateBundle:Enrollment:create'),
    array('_method' => 'post')
));

$collection->add('enrollment_edit', new Route('/{id}/edit', array(
    '_controller' => 'ClassMateBundle:Enrollment:edit',
)));

$collection->add('enrollment_update', new Route(
    '/{id}/update',
    array('_controller' => 'ClassMateBundle:Enrollment:update'),
    array('_method' => 'post|put')
));

$collection->add('enrollment_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'ClassMateBundle:Enrollment:delete'),
    array('_method' => 'post|delete')
));

return $collection;
