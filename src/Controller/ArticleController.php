<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;

class AtricleController
{

    public function homepage()
    {
        return new Response('First page!');
    }

}