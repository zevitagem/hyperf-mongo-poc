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

namespace App\Controller;

use App\Library\MongoManager;
use Hyperf\Logger\LoggerFactory;
use App\Library\Fees;
use Hyperf\HttpServer\Contract\RequestInterface;

class ApiController extends AbstractController
{
    private $database;
    private $error = null;

    public function __construct(MongoManager $databaseManager, LoggerFactory $loggerFactory)
    {
        $this->database = $databaseManager;
        $this->logger   = $loggerFactory->get('log', 'default');

        try {
            $this->database::connect();
        } catch (\Throwable $exc) {
            $message     = $exc->getMessage();
            $this->error = $message;
            $this->logger->info('joseph: '.$message);
        }
    }

    private function canContinue()
    {
        return ($this->error === null);
    }

    private function stop()
    {
        return $this->error;
    }

    public function index()
    {
        if (!$this->canContinue()) {
            return $this->stop();
        }

        $api = new Fees($this->database);
        return $api->get();
    }

    public function add()
    {
        if (!$this->canContinue()) {
            return $this->stop();
        }

        $api = new Fees($this->database);
        return $api->add();
    }

    public function destroy(RequestInterface $request)
    {
        if (!$this->canContinue()) {
            return $this->stop();
        }

        $api = new Fees($this->database);

        return [
            'status' => $api->destroy($request->getQueryParams()['id'])
        ];
    }
}