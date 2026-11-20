<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return session()->has('user') ? redirect()->to(site_url('/')) : view('login', ['title' => 'Masuk']);
    }

    public function authenticate()
    {
        if (! $this->validate(['username' => 'required|max_length[40]', 'password' => 'required|max_length[72]'])) {
            return redirect()->back()->with('error', 'Isi username dan kata sandi dengan benar.');
        }

        $user = model(UserModel::class)->where('username', $this->request->getPost('username'))->first();
        if (! $user || ! password_verify($this->request->getPost('password'), $user['password_hash'])) {
            return redirect()->back()->with('error', 'Username atau kata sandi salah.');
        }

        session()->regenerate(true);
        session()->set('user', ['username' => $user['username'], 'role' => $user['role']]);

        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
