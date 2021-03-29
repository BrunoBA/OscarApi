<?php

namespace OscarApi\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class OscarController extends AbstractController
{
    public function get(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write("GET");

        return $response;
    }

    function post(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write("Hello world! from controller");

        return $response;
    }
}
