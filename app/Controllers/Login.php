<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('form');
    }

    // HALAMAN LOGIN
    public function index()
    {
        return view('login/index');
    }

    // PROSES LOGIN
   public function proses()
    {
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    // CARI USER BERDASARKAN USERNAME
    $user = $this->userModel->where('username', $username)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Username tidak ditemukan.');
    }

    // CEK PASSWORD (masih plain text sesuai DB-mu)
    if ($user->password !== $password) {
        return redirect()->back()->with('error', 'Password salah.');
    }

    // ⛔ CEK STATUS AKUN
    if (isset($user->status) && $user->status === 'nonaktif') {
        return redirect()->back()->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
    }

    // SET SESSION
    session()->set([
        'login'    => true,
        'id_user'  => $user->id_user,
        'username' => $user->username,
        'nama'     => $user->nama,
        'role'     => $user->role,
        'foto'     => $user->foto, //unutk header profil
    ]);

    // REDIRECT BERDASARKAN ROLE
    if ($user->role === 'admin') {
        return redirect()->to('/dashboard');
    } else {
        return redirect()->to('/kasir');
    }
    }
    public function logout()
{
    session()->destroy();
    return redirect()->to('/login');
}

}