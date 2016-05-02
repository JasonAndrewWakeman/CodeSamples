<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('user', new Route('/', array(
    '_controller' => 'ClassMateBundle:User:index',
)));

$collection->add('user_show', new Route('/{id}/show', array(
    '_controller' => 'ClassMateBundle:User:show',
)));

$collection->add('user_new', new Route('/new', array(
    '_controller' => 'ClassMateBundle:User:new',
)));

$collection->add('user_create', new Route(
    '/create',
    array('_controller' => 'ClassMateBundle:User:create'),
    array('_method' => 'post')
));

$collection->add('user_edit', new Route('/{id}/edit', array(
    '_controller' => 'ClassMateBundle:User:edit',
)));

$collection->add('user_update', new Route(
    '/{id}/update',
    array('_controller' => 'ClassMateBundle:User:update'),
    array('_method' => 'post|put')
));

$collection->add('user_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'ClassMateBundle:User:delete'),
    array('_method' => 'post|delete')
));

return $collection;
