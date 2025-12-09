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
    if ($this->request->getMethod() !== 'POST') {
        return redirect()->to(base_url('UserAdmin'));
    }

    // Ambil id_user dari form (hidden input)
    $idUserForm = $this->request->getPost('id_user');

    // Ambil data admin berdasar id_user dari form
    $admin = $this->userModel->find($idUserForm);

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

        // Cek password lama (masih plain text)
        if ($admin->password !== $passwordLama) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password lama tidak sesuai.');
        }

        // Simpan password baru (kalau mau, nanti di-hash)
        $dataUpdate['password'] = $passwordBaru;
    }

    // Simpan ke database
    $this->userModel->update($admin->id_user, $dataUpdate);

    // Update username di session supaya ikut berubah
    session()->set('username', $username);

    return redirect()->to(base_url('UserAdmin'))
        ->with('success', 'Profil admin berhasil diperbarui.');
}
// ========== PROSES UPDATE FOTO PROFIL ADMIN ==========
public function updateFoto()
{
    if ($this->request->getMethod() !== 'POST') {
        return redirect()->to(base_url('UserAdmin'));
    }

    // Ambil id_user dari session
    $idUser = session('id_user');
    $admin  = $this->userModel->find($idUser);

    if (!$admin) {
        return redirect()->back()->with('error', 'Data admin tidak ditemukan.');
    }

    // Ambil file foto dari input
    $fileFoto = $this->request->getFile('foto');

    if (!$fileFoto || !$fileFoto->isValid()) {
        return redirect()->back()->with('error', 'Tidak ada file foto yang diupload.');
    }

    // Validasi tipe file
    if (! in_array($fileFoto->getMimeType(), ['image/jpeg', 'image/png'])) {
        return redirect()->back()->with('error', 'Format foto harus JPG atau PNG.');
    }

    // Buat nama unik
    $newName = $fileFoto->getRandomName();

    // Simpan file
    $fileFoto->move(FCPATH . 'uploads/user', $newName);

    // Hapus foto lama jika ada
    if (!empty($admin->foto) && file_exists(FCPATH . 'uploads/user/' . $admin->foto)) {
        @unlink(FCPATH . 'uploads/user/' . $admin->foto);
    }

    // Update database
    $this->userModel->update($admin->id_user, [
        'foto' => $newName,
    ]);

    // UPDATE SESSION FOTO agar navbar ikut berubah
    session()->set('foto', $newName);

    return redirect()->to(base_url('UserAdmin'))
        ->with('success', 'Foto profil berhasil diperbarui.');
}


}
