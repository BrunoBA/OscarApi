<?php

namespace OscarApi\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class OscarController extends AbstractController
{

    public function show(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write("SHOW");

        return $response;
    }

    public function index(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write("INDEX");

        return $response;
    }

    function store(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write("Hello world! from controller");

        return $response;
    }
}
