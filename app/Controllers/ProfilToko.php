<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProfilTokoModel;

class ProfilToko extends BaseController
{
    protected $profilModel;

    public function __construct()
    {
        $this->profilModel = new ProfilTokoModel();
        helper('form');
    }

    public function simpan()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $id = $this->request->getPost('id_profil');

        $data = [
            'nama_toko' => $this->request->getPost('nama_toko'),
            'alamat'    => $this->request->getPost('alamat'),
            'no_telp'   => $this->request->getPost('no_telp'),
        ];

        if ($id) {
            // update baris yang sudah ada
            $this->profilModel->update($id, $data);
        } else {
            // kalau belum ada, insert baru
            $this->profilModel->insert($data);
        }

        return redirect()->back()->with('success', 'Profil toko berhasil disimpan.');
    }
}
