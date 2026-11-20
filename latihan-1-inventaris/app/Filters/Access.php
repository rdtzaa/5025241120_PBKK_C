<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Access implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('user');
        if (! $user) {
            return redirect()->to(site_url('login'));
        }
        if (isset($arguments[0]) && $user['role'] !== $arguments[0]) {
            return service('response')->setStatusCode(403)->setBody('Aksi ini hanya dapat dilakukan admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
