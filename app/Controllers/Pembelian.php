<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Detail_pembelianModel;
use App\Models\PembelianModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pembelian extends BaseController
{
    protected $model;
    protected $detail;

     public function __construct()
    {
        $this->model = new PembelianModel();
        $this->detail = new Detail_pembelianModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll(); //Ambil semua data pembelian
        foreach ($data['model'] as $key => $value) {
            $value->detail = $this->detail->getById($value->id_pembelian);
        } //Ambil detail pembelian per baris
        return view('pembelian/index', $data); //Kirim data ke view
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah user menekan tombol SIMPAN
            $this->model->save($this->request->getPost()); //Simpan data pembelian ke database
            return redirect()->to(base_url('pembelian')); //Setelah simpan, kembali ke daftar pembelian
        }
        return view('pembelian/tambah'); //Jika belum menekan SIMPAN → tampilkan form tambah
    }

    public function ubah($id = null) //Fungsi untuk edit data, dan $id adalah ID data yang mau diedit
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah user menekan tombol SIMPAN
            $this->model->save($this->request->getPost()); //Cek apakah user menekan tombol SIMPAN
            return redirect()->to(base_url('pembelian')); //Setelah update, kembali ke halaman daftar pembelian
        }
        $data['model'] =  $this->model->where('id_pembelian', $id)->first(); //Jika belum menekan simpan → ambil data pembelian lama
        return view('pembelian/ubah', $data); //Tampilkan halaman form ubah pembelian
    }

    public function hapus($id = null)
    {
        $this->model->delete($id); //Hapus data pembelian berdasarkan ID
        return redirect()->to(base_url('pembelian')); //Setelah hapus, kembali ke daftar pembelian
    }
}
