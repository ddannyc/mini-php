<?php
/**
 * Created by PhpStorm.
 * User: wayne 
 * Date: 2015/7/22
 * Time: 16:05
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class ErrorController
{
    public function exceptionAction(FlattenException $exception)
    {
        $msg = 'Something went wrong! ('. $exception->getMessage(). ')';

        return new Response($msg, $exception->getStatusCode());
    }
}
