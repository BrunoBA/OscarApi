<?php

use OscarApi\Env;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$isDev = new Env('IS_DEV', false);
if ((bool)$isDev) {
    #$app->addErrorMiddleware(false,false,false);
    $app->add(new WhoopsMiddleware(['enable' => true]));
}

$app->add(
    function (
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) {
        try {
            return $handler->handle($request);
        } catch (Exception) {
            $response = (new Response())->withStatus(404);
            $response->getBody()->write('404 Not found');

            return $response;
        }
    }
);
$app->add(
    function ($request, $handler) {
        $response = $handler->handle($request);
        $host = new Env('HOST', "*");

        return $response
            ->withHeader('Access-Control-Allow-Origin', (string)$host)
            ->withHeader(
                'Access-Control-Allow-Headers',
                'X-Requested-With, Content-Type, Accept, Origin, Authorization'
            )
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }
);

$app->get('/oscar/{hash}', 'OscarApi\Controller\OscarController');
$app->get('/oscar', 'OscarApi\Controller\OscarController');
$app->post('/oscar', 'OscarApi\Controller\OscarController');


$app->run();
