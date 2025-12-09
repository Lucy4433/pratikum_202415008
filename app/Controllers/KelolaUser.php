<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class KelolaUser extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // HALAMAN DAFTAR KASIR
    public function index()
    {
        $kasir = $this->userModel
            ->where('role', 'kasir')
            ->orderBy('username', 'ASC')
            ->findAll();

        return view('KelolaUser/index', [
            'kasir' => $kasir,
        ]);
    }

    // TAMBAH KASIR BARU
    public function tambah()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('KelolaUser'));
        }

        $nama       = $this->request->getPost('nama');
        $username   = $this->request->getPost('username');
        $password   = $this->request->getPost('password');
        $konfirmasi = $this->request->getPost('konfirmasi');

        if ($password !== $konfirmasi) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password dan konfirmasi password tidak sama.');
        }

        $cekUsername = $this->userModel->where('username', $username)->first();
        if ($cekUsername) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username sudah digunakan.');
        }

        $this->userModel->insert([
            'nama'     => $nama,
            'username' => $username,
            'password' => $password, 
            'role'     => 'kasir',
            'status'   => 'aktif',
        ]);

        return redirect()->to(base_url('KelolaUser'))
            ->with('success', 'User kasir berhasil ditambahkan.');
    }

        public function formTambah()
        {
            // hanya untuk menampilkan form tambah kasir
            return view('KelolaUser/tambah');
        }


    // UBAH DATA KASIR
    public function ubah($id = null)
    {
        if ($this->request->getMethod() !== 'POST' || !$id) {
            return redirect()->to(base_url('KelolaUser'));
        }

        $kasir = $this->userModel->find($id);
        if (!$kasir || $kasir->role !== 'kasir') {
            return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
        }

        $nama     = $this->request->getPost('nama');
        $username = $this->request->getPost('username');
        $status   = $this->request->getPost('status');
        $password = $this->request->getPost('password');

        $cekUsername = $this->userModel
            ->where('username', $username)
            ->where('id_user !=', $id)
            ->first();

        if ($cekUsername) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username sudah digunakan user lain.');
        }

        $dataUpdate = [
            'nama'     => $nama,
            'username' => $username,
            'status'   => $status,
        ];

        if (!empty($password)) {
            $dataUpdate['password'] = $password;
        }

        $this->userModel->update($id, $dataUpdate);

        return redirect()->to(base_url('KelolaUser'))
            ->with('success', 'Data kasir berhasil diubah.');
    }

    // NONAKTIFKAN KASIR
    public function nonaktif($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('KelolaUser'));
        }

        $kasir = $this->userModel->find($id);
        if (!$kasir || $kasir->role !== 'kasir') {
            return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
        }

        $this->userModel->update($id, ['status' => 'nonaktif']);

        return redirect()->to(base_url('KelolaUser'))
            ->with('success', 'Kasir berhasil dinonaktifkan.');
    }

    // AKTIFKAN KASIR
    public function aktif($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('KelolaUser'));
        }

        $kasir = $this->userModel->find($id);
        if (!$kasir || $kasir->role !== 'kasir') {
            return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
        }

        $this->userModel->update($id, ['status' => 'aktif']);

        return redirect()->to(base_url('KelolaUser'))
            ->with('success', 'Kasir berhasil diaktifkan.');
    }

    // HAPUS KASIR
    public function hapus($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('KelolaUser'));
        }

        $kasir = $this->userModel->find($id);
        if (!$kasir || $kasir->role !== 'kasir') {
            return redirect()->back()->with('error', 'Data kasir tidak ditemukan.');
        }

        $this->userModel->delete($id);

        return redirect()->to(base_url('KelolaUser'))
            ->with('success', 'User kasir berhasil dihapus.');
    }
}
