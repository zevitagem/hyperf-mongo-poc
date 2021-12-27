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

namespace App\Model;

use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

class User extends Model implements CacheableInterface
{
    use Cacheable;

    protected $table = 'user';

    protected $fillable = ['id', 'value', 'created_at', 'updated_at'];

}