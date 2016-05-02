<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('archive', new Route('/', array(
    '_controller' => 'ClassMateBundle:Archive:index',
)));

$collection->add('archive_show', new Route('/{id}/show', array(
    '_controller' => 'ClassMateBundle:Archive:show',
)));

$collection->add('archive_new', new Route('/new', array(
    '_controller' => 'ClassMateBundle:Archive:new',
)));

$collection->add('archive_create', new Route(
    '/create/{courseName}/{userRole}/{courseID}',
    array('_controller' => 'ClassMateBundle:Archive:create'),
    array('_method' => 'post')
));

$collection->add('archive_edit', new Route('/{id}/edit', array(
    '_controller' => 'ClassMateBundle:Archive:edit',
)));

$collection->add('archive_update', new Route(
    '/{id}/update',
    array('_controller' => 'ClassMateBundle:Archive:update'),
    array('_method' => 'post|put')
));

$collection->add('archive_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'ClassMateBundle:Archive:delete'),
    array('_method' => 'post|delete')
));

return $collection;
