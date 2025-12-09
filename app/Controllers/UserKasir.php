<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserKasir extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form']);
    }

    // ========== HALAMAN PROFIL KASIR ==========
    public function index()
    {
        $idUser   = session('id_user');
        $username = session('username');
        $role     = session('role');

        // pastikan yang akses betul-betul kasir
        if ($role !== 'kasir') {
            return redirect()->to(base_url('/'))
                ->with('error', 'Anda tidak punya akses ke halaman profil kasir.');
        }

        if ($idUser) {
            $kasir = $this->userModel->find($idUser);
        } else {
            $kasir = $this->userModel->where('username', $username)->first();
        }

        if (!$kasir) {
            return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
        }

        return view('UserKasir/index', [
            'kasir' => $kasir,
        ]);
    }

   // ========== UPDATE PROFIL (NAMA SAJA) ==========
public function updateProfil()
{
    $idUser = session('id_user');
    if (!$idUser) {
        return redirect()->to(base_url('/login'));
    }

    $kasir = $this->userModel->find($idUser);
    if (!$kasir) {
        return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
    }

    // validasi nama saja
    $rules = [
        'nama' => 'required|min_length[3]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->with('error', 'Nama tidak valid.')
            ->with('validation', $this->validator)
            ->withInput();
    }

    $namaBaru = $this->request->getPost('nama');

    // update ke DB
    $this->userModel->update($idUser, [
        'nama' => $namaBaru,
    ]);

    // update session
    session()->set('nama', $namaBaru);

    return redirect()->to(base_url('UserKasir'))
        ->with('success', 'Profil kasir berhasil diperbarui.');
}

// ========== UPDATE PASSWORD ==========
public function updatePassword()
{
    $idUser = session('id_user');
    if (!$idUser) {
        return redirect()->to(base_url('/login'));
    }

    $kasir = $this->userModel->find($idUser);
    if (!$kasir) {
        return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
    }

    $rules = [
        'password_lama'       => 'required',
        'password_baru'       => 'required|min_length[6]',
        'password_konfirmasi' => 'required|matches[password_baru]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->with('error', 'Data password tidak valid.')
            ->with('validation', $this->validator)
            ->withInput();
    }

    $passLama = $this->request->getPost('password_lama');
    $passBaru = $this->request->getPost('password_baru');

    if (!password_verify($passLama, $kasir->password)) {
        return redirect()->back()
            ->with('error', 'Password lama tidak sesuai.');
    }

    $hashBaru = password_hash($passBaru, PASSWORD_DEFAULT);

    $this->userModel->update($idUser, [
        'password' => $hashBaru,
    ]);

    return redirect()->to(base_url('UserKasir'))
        ->with('success', 'Password berhasil diubah.');
}

// ========== UPDATE FOTO ==========
public function updateFoto()
{
    $idUser = session('id_user');
    if (!$idUser) {
        return redirect()->to(base_url('/login'));
    }

    $kasir = $this->userModel->find($idUser);
    if (!$kasir) {
        return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
    }

    // validasi foto
    $rules = [
        'foto' => 'uploaded[foto]|is_image[foto]|max_size[foto,2048]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->with('error', 'Foto tidak valid. Pastikan JPG/PNG, maks 2MB.')
            ->with('validation', $this->validator);
    }

    $fileFoto = $this->request->getFile('foto');
    $namaLama = $kasir->foto ?? null;
    $namaBaru = $namaLama;

    if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
        $newName = $fileFoto->getRandomName();
        $fileFoto->move('assets/images/foto_user', $newName);

        if (!empty($namaLama)
            && $namaLama !== 'default.png'
            && file_exists(FCPATH . 'assets/images/foto_user/' . $namaLama)) {
            @unlink(FCPATH . 'assets/images/foto_user/' . $namaLama);
        }

        $namaBaru = $newName;
    }

    // simpan di DB
    $this->userModel->update($idUser, [
        'foto' => $namaBaru,
    ]);

    // update session (buat header)
    session()->set('foto', $namaBaru ?? 'default.png');

    return redirect()->to(base_url('UserKasir'))
        ->with('success', 'Foto profil kasir berhasil diperbarui.');
}

}
