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

class IndexController extends AbstractController
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        ob_start();
phpinfo();
$phpinfoAsString = ob_get_contents();
ob_get_clean();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
                'content' => $phpinfoAsString
         ];
    }
}
