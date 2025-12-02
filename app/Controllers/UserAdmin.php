<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserAdmin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ========== HALAMAN PROFIL ADMIN ==========
    public function index()
    {
        // Ambil ID user dari session (sesuaikan dengan login kamu)
        $idUser     = session('id_user');
        $username   = session('username');

        // Cari data admin
        if ($idUser) {
            $admin = $this->userModel->find($idUser);
        } else {
            // fallback kalau id_user belum disimpan di session
            $admin = $this->userModel->where('username', $username)->first();
        }

        if (!$admin) {
            return redirect()->back()->with('error', 'Data admin tidak ditemukan.');
        }

        return view('UserAdmin/index', [
            'admin' => $admin,
        ]);
    }

    // ========== PROSES UPDATE PROFIL ADMIN ==========
    public function updateProfil()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('UserAdmin'));
        }

        $idUser   = session('id_user');
        $usernameSession = session('username');

        // Ambil data admin saat ini
        if ($idUser) {
            $admin = $this->userModel->find($idUser);
        } else {
            $admin = $this->userModel->where('username', $usernameSession)->first();
        }

        if (!$admin) {
            return redirect()->back()->with('error', 'Data admin tidak ditemukan.');
        }

        // Ambil data dari form
        $nama               = $this->request->getPost('nama');
        $username           = $this->request->getPost('username');
        $passwordLama       = $this->request->getPost('password_lama');
        $passwordBaru       = $this->request->getPost('password_baru');
        $passwordKonfirmasi = $this->request->getPost('password_konfirmasi');

        // Cek username unik (tidak boleh sama dengan user lain)
        $cekUsername = $this->userModel
            ->where('username', $username)
            ->where('id_user !=', $admin->id_user)
            ->first();

        if ($cekUsername) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username sudah dipakai user lain.');
        }

        // Data dasar yang akan diupdate
        $dataUpdate = [
            'nama'     => $nama,
            'username' => $username,
        ];

        // Jika ada percobaan ganti password
        if ($passwordLama || $passwordBaru || $passwordKonfirmasi) {

            // Cek konfirmasi password baru
            if ($passwordBaru !== $passwordKonfirmasi) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Password baru dan konfirmasi tidak sama.');
            }

            // Cek password lama (sekarang masih plain text sesuai DB-mu)
            if ($admin->password !== $passwordLama) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Password lama tidak sesuai.');
            }

            // Kalau nanti mau pakai hash:
            // $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
            $dataUpdate['password'] = $passwordBaru;
        }

        // Simpan ke database
        $this->userModel->update($admin->id_user, $dataUpdate);

        // Update username di session supaya langsung ikut berubah
        session()->set('username', $username);

        return redirect()->to(base_url('UserAdmin'))
            ->with('success', 'Profil admin berhasil diperbarui.');
    }
}
