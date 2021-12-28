<?php
declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/',
    'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/api', function () {

    Router::addGroup('/mongo', function () {
        Router::post('/store', [\App\Controller\ApiController::class, 'store']);
        Router::get('/list', [\App\Controller\ApiController::class, 'list']);
        Router::get('', [\App\Controller\ApiController::class, 'list']);
        Router::delete('/destroy', [\App\Controller\ApiController::class, 'destroy']);
    }, ['middleware' => [\App\Middleware\Database\MongoMiddleware::class]]);

    Router::addGroup('/cache', function () {
        Router::get('/user_list', [\App\Controller\UserController::class, 'list']);
        Router::get('', [\App\Controller\UserController::class, 'list']);
        Router::get('/user_find/{key}', [\App\Controller\UserController::class, 'find']);
        Router::post('/user_store', [\App\Controller\UserController::class, 'store']);
    });
});
