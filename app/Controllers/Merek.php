<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MerekModel;
use CodeIgniter\HTTP\ResponseInterface;

class Merek extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new MerekModel();
    }

    public function index()
{
    $data['model'] =  $this->model->findAll(); //mengambil seluruh data merek dari database

    if ($this->request->getMethod() == 'POST') { //Cek apakah form dikirim
        $rules = [
            'nama_merek' => [
                'label' => 'Nama Merek',
                'rules' => 'required|is_unique[merek.nama_merek]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'is_unique' => '{field} sudah ada di database.'
                ]//aturan validasi
            ]
        ]; 

        // Jalankan validasi
        if (!$this->validate($rules)) {
            $data['validation'] = $this->validator; // Simpan error validasi
        } else {
            $this->model->save($this->request->getPost()); //Simpan data jika validasi berhasil
            return redirect()->to(base_url('merek')); //Kembali ke daftar merek setelah data berhasil disimpan
        }
    }

    return view('merek/index', $data); //Data dikirim ke view merek/index untuk ditampilkan dalam tabel
}


    // public function tambah()
    // {
    //     if ($this->request->getMethod() == 'POST') { //Cek apakah form dikirim
    //         $rules = [
    //             'nama_merek' => [
    //                 'label' => 'Nama Merek',
    //                 'rules' => 'required|is_unique[merek.nama_merek]',
    //                 'errors' => [
    //                     'required' => '{field} harus diisi.',
    //                     'is_unique' => '{field} sudah ada di database.'
    //                 ]//aturan validasi
    //             ]
    //         ]; 

    //         // Jalankan validasi
    //         if (!$this->validate($rules)) {
    //             return view('merek', [
    //                 'validation' => $this->validator
    //             ]); //// Jika validasi gagal, kirim kembali view dengan error
    //         }
    //         $this->model->save($this->request->getPost()); //Simpan data jika validasi berhasil
    //         return redirect()->to(base_url('merek')); //Kembali ke daftar merek setelah data berhasil disimpan
    //     }
    //     return view('merek/index'); //Menampilkan form tambah merek
    // }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah form edit dikirim
            $this->model->save($this->request->getPost()); //update data merek berdasarkan id
            return redirect()->to(base_url('merek')); //Kembali ke daftar merek
        }
        $data['model'] =  $this->model->where('id_merek', $id)->first(); //Jika belum simpan → ambil data lama
        return view('merek/ubah', $data); //Tampilkan form edit
    }

    public function hapus($id = null)
    {
        $this->model->delete($id); //Hapus data berdasarkan ID
        return redirect()->to(base_url('merek')); //Setelah data terhapus, user diarahkan kembali ke halaman daftar merek
    }
}
