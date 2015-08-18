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

list(, $path) = $argv;
$request = Request::create($path);
$sc = include __DIR__ . '/../src/container.php';
$sc->setParameter('routes', include __DIR__ . '/../src/routes.php');
$sc->setParameter('charset', 'UTF-8');

$dispatcher = $sc->get('dispatcher');
$dispatcher->addSubscriber(new \App\Core\StringResponseListener());

$response = $sc->get('framework')->handle($request);

$response->send();
