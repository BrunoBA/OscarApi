<?php

use OscarApi\Env;
use OscarApi\CorsMiddleware;
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

$app->add(CorsMiddleware::class);

// The RoutingMiddleware should be added after our CORS middleware 
// so routing is performed first
$app->addRoutingMiddleware();

$app->get('/oscar/{hash}', 'OscarApi\Controller\OscarController');
$app->get('/oscar', 'OscarApi\Controller\OscarController');
$app->post('/oscar', 'OscarApi\Controller\OscarController');

$app->add(
    function (
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) {
        try {
            return $handler->handle($request);
        } catch (Exception $exception) {
            $response = (new Response())->withStatus(404);
            $message = $exception->getMessage();
            $response->getBody()->write(sprintf('{"message":"%s", "code": %u}', $message, 404));

            return $response;
        }
    }
);

$app->run();
