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
    } //Tampilkan halaman login

    // PROSES LOGIN
   public function proses()
    {
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    //Ambil input username & password

    // CARI USER BERDASARKAN USERNAME
    $user = $this->userModel->where('username', $username)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Username tidak ditemukan.');
    }

    // Cek apakah password benar
    if ($user->password !== $password) {
        return redirect()->back()->with('error', 'Password salah.');
    }

    // Cek status akun aktif / nonaktif
    if (isset($user->status) && $user->status === 'nonaktif') {
        return redirect()->back()->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
    }

    // Simpat data pengguna ke dalam session supaya sistem tahu siapa yang login
    session()->set([
        'isLoggedIn'    => true,
        'id_user'  => $user->id_user,
        'username' => $user->username,
        'nama'     => $user->nama,
        'role'     => $user->role,
        'foto'     => $user->foto, 
    ]);

    // Arahkan user berdasarkan role
    if ($user->role === 'admin') {
        return redirect()->to('/dashboard'); //aadmin → masuk ke dashboard admin
    } else {
        return redirect()->to('/kasir'); //kasir → masuk ke halaman kasir
    }
    }
    public function logout()
{
    session()->destroy();
    return redirect()->to('/login');
} //Hapus semua session, lalu kembali ke halaman login

}