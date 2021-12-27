<?php

namespace App\Service;

use App\Model\User;
use Hyperf\ModelCache\Config;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Config\ConfigFactory;

class UserService
{

    public function __construct()
    {
        $config      = new ConfigFactory();
        $model       = new User();
        
        $prefix      = $model->getConnectionName();
        $cacheConfig = $config(ApplicationContext::getContainer())->get('databases')[$prefix]['cache'];

        $this->handler = new $cacheConfig['handler'](
            ApplicationContext::getContainer(),
            new Config($cacheConfig, $prefix)
        );
    }

    public function store($id, $value)
    {
        return $this->handler->set($id, ['teste' => $value]);
    }

    public function find($id)
    {
        return $this->handler->get($id);
    }

    public function list()
    {
        return $this->handler->keys();
    }
}