<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailOrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class DetailOrder extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DetailOrderModel();
    } //menyiapkan model DetailOrderModel supaya bisa dipakai di semua fungsi

    public function index()
    {
        $data['model'] =  $this->model->findAll(); //ambil semua data tabel detail_order,simpan ke variabel model untuk dikirim ke view.
        return view('detail_order/index', $data); //tampil halaman detail_order/index, sambil bawa data yang diambil.
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') { //megecek form tambah
            $this->model->save($this->request->getPost()); //ambil semua data dari form, simpan ke tabel detail_order
            return redirect()->to(base_url('detail_order')); //simpan kembali ke halaaman daftar detail order
        }
        return view('detail_order/tambah'); //tampil halaman detail_order/tambah
    }

    public function ubah($id = null) //megubah data detail order, id tertentu
    {
        if ($this->request->getMethod() == 'POST') { //cek data masuk form edit
            $this->model->save($this->request->getPost()); //“ambil data ubah dari form, simpan ke tabel detail_order
            return redirect()->to(base_url('detail_order')); //simpan, pindah ke halaman daftar
        }
        $data['model'] =  $this->model->where('id_detail_order', $id)->first(); //data id yang sesuai, tampilan di form edit
        return view('detail_order/ubah', $data); // kembali halaman ubah, berserta data yang diubah
    }

    public function hapus($id = null) //data hapus, dengan id tertentu
    {
        $this->model->delete($id); //hapus data dengan id yang dipilih
        return redirect()->to(base_url('detail_order')); //kembali halaman daftar
    }
}
