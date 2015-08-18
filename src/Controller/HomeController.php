<?php
/**
 * Created by PhpStorm.
 * User: wayne 
 * Date: 2015/7/23
 * Time: 11:12
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function indexAction()
    {
        return new Response('Hello world!');
    }
}
