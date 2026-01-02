<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ordersModel;
use CodeIgniter\HTTP\ResponseInterface;

class orders extends BaseController
{
     protected $model;

    public function __construct()
    {
        $this->model = new ordersModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll(); //Ambil data dari database
        return view('orders/index', $data); //Kirim data ke view
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah form dikirim
            $this->model->save($this->request->getPost()); //Ambil data form
            return redirect()->to(base_url('orders')); //Setelah simpan → kembali ke daftar orders
        }
        return view('orders/tambah'); //Jika belum simpan, tampilkan halaman tambah
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah form dikirim
            $this->model->save($this->request->getPost()); //Ambil data form
            return redirect()->to(base_url('orders')); //Setelah simpan → kembali ke daftar orders
        }
        $data['model'] =  $this->model->where('id_orders', $id)->first(); //Jika belum simpan, ambil data lama
        return view('orders/ubah', $data); //Tampilkan form edit
    }

    public function hapus($id = null)
    {
        $this->model->delete($id); //Hapus data berdasarkan ID
        return redirect()->to(base_url('orders')); //Setelah data dihapus, kembali ke halaman daftar order
    }
}
