<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AcademicAccess implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $account = session('academic_account');
        if (! $account) {
            return redirect()->to(site_url('login'));
        }
        if (isset($arguments[0]) && $account['role'] !== $arguments[0]) {
            return service('response')->setStatusCode(403)->setBody('Halaman ini tidak tersedia untuk peran Anda.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
