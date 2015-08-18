<?php
/**
 * Created by PhpStorm.
 * User: wayne 
 * Date: 2015/7/20
 * Time: 20:41
 */

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('/', new Routing\Route('/',
    array(
        '_controller' => 'App\\Controller\\HomeController::indexAction'
    )
));
return $routes;
