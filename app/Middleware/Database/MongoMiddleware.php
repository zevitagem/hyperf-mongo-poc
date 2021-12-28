<?php
declare(strict_types=1);

namespace App\Middleware\Database;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Library\MongoManager;
use Hyperf\Logger\LoggerFactory;

class MongoMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container,
                                HttpResponse $response,
                                MongoManager $databaseManager,
                                LoggerFactory $loggerFactory)
    {
        $this->container = $container;
        $this->response = $response;
        $this->database  = $databaseManager;
        $this->logger    = $loggerFactory->get('log', 'default');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $this->database::connect();
            return $handler->handle($request);
        } catch (\Throwable $exc) {
            $message = $exc->getMessage();
            $this->logger->info('joseph: '.$message);
        }

        return $this->response->json(
                [
                    'code' => -1,
                    'data' => [
                        'error' => $message,
                    ],
                ]
        );
    }
}