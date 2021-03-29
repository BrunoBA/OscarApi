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
        if ($request->getMethod() === self::METHOD_GET && count($args) === 0) {
            return $this->index($request, $response, $args);
        }

        if ($request->getMethod() === self::METHOD_GET) {
            return $this->show($request, $response, $args);
        }

        return $this->store($request, $response, $args);
    }

    abstract function index(Request $request, Response $response, $args): Response;

    abstract function show(Request $request, Response $response, $args): Response;

    abstract function store(Request $request, Response $response, $args): Response;

}
