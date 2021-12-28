<?php

namespace App\Library;

use Hyperf\ModelCache\Handler\RedisHandler as BaseRedisHandler;

class RedisHandler extends BaseRedisHandler
{

    public function keys()
    {
        return $this->redis->keys('*');
    }
}