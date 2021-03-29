<?php


namespace OscarApi\Controller;


use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractController
{
    const METHOD_GET = "GET";
    const METHOD_POST = "POST";

    public function __invoke(Request $request, Response $response, $args): Response
    {
        if ($request->getMethod() === self::METHOD_GET) {
            return $this->get($request, $response, $args);
        }

        return $this->post($request, $response, $args);
    }

    abstract function get(Request $request, Response $response, $args): Response;

    abstract function post(Request $request, Response $response, $args): Response;

}
