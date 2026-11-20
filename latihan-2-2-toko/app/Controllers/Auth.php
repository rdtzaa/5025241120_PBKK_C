<?php

namespace App\Controllers;

use App\Models\ShopAccountModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Auth extends BaseController
{
    public function login()
    {
        return session('shop_account') ? $this->home() : view('auth', ['title' => 'Masuk', 'register' => false]);
    }

    public function authenticate()
    {
        $username = strtolower($this->field('username'));
        $password = $this->request->getPost('password', FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR);
        $account = model(ShopAccountModel::class)->where('username', $username)->first();
        if (! $account || ! is_string($password) || ! password_verify($password, $account['password_hash'])) {
            return redirect()->to(site_url('login'))->with('error', 'Username atau kata sandi salah.');
        }

        return $this->start($account);
    }

    public function registerForm()
    {
        return session('shop_account') ? $this->home() : view('auth', ['title' => 'Daftar pelanggan', 'register' => true]);
    }

    public function register()
    {
        if (session('shop_account')) {
            return $this->home();
        }

        $data = [
            'username' => strtolower($this->field('username')),
            'name' => $this->field('name'),
            'password' => $this->request->getPost('password', FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR),
        ];
        if (! $this->validateData($data, [
            'username' => 'required|alpha_dash|min_length[3]|max_length[30]',
            'name' => 'required|max_length[100]',
            'password' => 'required|min_length[8]',
        ])) {
            return redirect()->to(site_url('daftar'))->with('errors', $this->validator->getErrors());
        }

        if (strlen($data['password']) > 72 || str_contains($data['password'], "\0")) {
            return redirect()->to(site_url('daftar'))->with('error', 'Kata sandi tidak dapat digunakan.');
        }

        $accounts = model(ShopAccountModel::class);
        try {
            $id = $accounts->insert([
                'username' => $data['username'], 'name' => $data['name'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT), 'role' => 'pelanggan',
            ]);
        } catch (DatabaseException $error) {
            log_message('error', $error->getMessage());
            $id = false;
        }

        if (! $id) {
            return redirect()->to(site_url('daftar'))->with('error', 'Akun gagal dibuat. Coba username lain.');
        }

        return $this->start($accounts->find($id));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('/'));
    }

    private function field(string $name): string
    {
        return trim((string) $this->request->getPost($name, FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR));
    }

    private function start(array $account)
    {
        unset($account['password_hash']);
        session()->regenerate(true);
        session()->remove('cart');
        session()->set('shop_account', $account);

        return $this->home();
    }

    private function home()
    {
        return redirect()->to(site_url(session('shop_account')['role'] === 'admin' ? 'admin' : '/'));
    }
}
