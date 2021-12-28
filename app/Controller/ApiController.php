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
use App\Service\UserService;

class ApiController extends AbstractController
{
    private $database;
    private $error = null;

    public function __construct(MongoManager $databaseManager)
    {
        $this->database = $databaseManager;
    }

    private function canContinue()
    {
        return $this->database::isConnected();
    }

    private function stop()
    {
        return 'Você não pode prosseguir devido não possuir configurações básicas válidas';
    }

    public function list()
    {
        if (!$this->canContinue()) {
            return $this->stop();
        }

        $api = new Fees($this->database);
        return $api->get();
    }

    public function store()
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