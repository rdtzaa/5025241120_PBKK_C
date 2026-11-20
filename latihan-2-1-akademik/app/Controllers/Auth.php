<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function home()
    {
        return redirect()->to(site_url(session('academic_account')['role'] === 'dosen' ? 'perwalian' : 'frs'));
    }

    public function login()
    {
        return session('academic_account') ? $this->home() : view('login', ['title' => 'Masuk']);
    }

    public function authenticate()
    {
        if (! $this->validate(['username' => 'required|max_length[40]', 'password' => 'required|max_length[72]'])) {
            return redirect()->to(site_url('login'))->with('error', 'Isi username dan kata sandi dengan benar.');
        }

        $account = db_connect()->table('academic_users')
            ->where('username', trim($this->request->getPost('username')))->get()->getRowArray();

        if (! $account || ! password_verify($this->request->getPost('password'), $account['password_hash'])) {
            return redirect()->to(site_url('login'))->with('error', 'Username atau kata sandi salah.');
        }

        session()->regenerate(true);
        unset($account['password_hash']);
        session()->set('academic_account', $account);

        return $this->home();
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
