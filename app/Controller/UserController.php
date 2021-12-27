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

use App\Service\UserService;

class UserController extends AbstractController
{

    public function store()
    {
        $id = str_shuffle('joseph');

        $service = new UserService();
        $result  = $service->store($id, 'value: '.$id);

        return $result;
    }

    public function list()
    {
        $service = new UserService();
        return $service->list();
    }

    public function find(string $key)
    {
        $service = new UserService();
        $result  = $service->find($key);

        return $result;
    }
}