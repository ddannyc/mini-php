<?php
/**
 * Created by PhpStorm.
 * User: wayne 
 * Date: 2015/7/20
 * Time: 19:43
 */

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/default.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$sc = include __DIR__ . '/../src/container.php';
$sc->setParameter('routes', include __DIR__ . '/../src/routes.php');
$sc->setParameter('charset', 'UTF-8');

/*$sc->register('listener.string_response', 'Simplex\StringResponseListener');
$sc->getDefinition('dispatcher')
    ->addMethodCall('addSubscriber', array(new \Symfony\Component\DependencyInjection\Reference('listener.string_response')))
;
$sc->register('listener.ga', 'Simplex\GoogleListener');
$sc->getDefinition('dispatcher')
    ->addMethodCall('addSubscriber', array(new \Symfony\Component\DependencyInjection\Reference('listener.ga')))
;*/

$dispatcher = $sc->get('dispatcher');
$dispatcher->addSubscriber(new \App\Core\StringResponseListener());
//$dispatcher->addSubscriber(new \App\Core\GoogleListener());

$response = $sc->get('framework')->handle($request);

//$dispatcher->dispatch('response', new \App\Core\ResponseEvent($response, $request));

$response->send();
