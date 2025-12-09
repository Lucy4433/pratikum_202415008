<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Detail_pembelianModel;
use CodeIgniter\HTTP\ResponseInterface;

class DetailPembelian extends BaseController //kelas controller detailpembelian
{
   protected $model;

    public function __construct()
    {
        $this->model = new Detail_pembelianModel();
    } //meyiapkan detail_pembelian untuk semua fungsi

    public function index()
    {
        $data['model'] =  $this->model->findAll(); //ambil data dari database melalui model Detail_pembelianModel
        return view('detail_pembelian/index', $data); //tampil halaman detail_pembelian/index dan kirim data yang,diambil 
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') { //cek post tambah
            $this->model->save($this->request->getPost()); //ambil data dari form ,simpan ke detail_pembelian
            return redirect()->to(base_url('detail_pembelian')); //tersimpan kembali ke daftar detail_pembelian
        }
        return view('detail_pembelian/tambah'); //tampilan form tambah
    }

    public function ubah($id = null) //megubah id tertentu
    {
        if ($this->request->getMethod() == 'POST') { //cek form ubah
            $this->model->save($this->request->getPost()); //ambil data ubah dan simpan tabel detail pembelian
            return redirect()->to(base_url('detail_pembelian')); //simpan pindah ke halaman daftar
        }
        $data['model'] =  $this->model->where('id_detail_pembelian', $id)->first(); //ambil data detail pembelian berdasarkan ID
        return view('detail_pembelian/ubah', $data); //tampila ke halaman ubah
    }

    public function hapus($id = null) //data hapus, dengan id tertentu
    {
        $this->model->delete($id); //hapus dataa dengan id sama 
        return redirect()->to(base_url('detail_pembelian')); //kembali ke haalaman daftar
    }
}

