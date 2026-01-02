<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pembayaran extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new PembayaranModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll(); //Ambil semua data pembayaran
        return view('pembayaran/index', $data); //Tampilkan halaman pembayaran/index
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah user menekan tombol SIMPAN
            $this->model->save($this->request->getPost()); //Ambil data form dan simpan
            return redirect()->to(base_url('pembayaran')); //Setelah simpan → kembali ke daftar
        }
        return view('pembayaran/tambah'); //Jika belum submit → tampilkan form tambah
    }

    public function ubah($id = null) 
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah user menekan tombol SIMPAN
            $this->model->save($this->request->getPost()); //Ambil data form dan simpan
            return redirect()->to(base_url('pembayaran')); //Setelah simpan → kembali ke daftar
        }
        $data['model'] =  $this->model->where('id_pembayaran', $id)->first(); //Jika belum simpan → ambil data lama
        return view('pembayaran/ubah', $data); //Tampilkan form edit
    }

    public function hapus($id = null)
    {
        $this->model->delete($id); //Hapus data berdasarkan ID
        return redirect()->to(base_url('pembayaran')); //Kembali ke daftar pembayaran
    }
}
