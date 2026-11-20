<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ShopAccess implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $account = session('shop_account');
        if (! $account) {
            return redirect()->to(site_url('login'));
        }

        if (isset($arguments[0]) && $account['role'] !== $arguments[0]) {
            return service('response')->setStatusCode(403)->setBody('Akses ini tidak tersedia untuk akun Anda.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
