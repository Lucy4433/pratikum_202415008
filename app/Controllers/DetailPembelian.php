<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Detail_pembelianModel;
use CodeIgniter\HTTP\ResponseInterface;

class DetailPembelian extends BaseController
{
   protected $model;

    public function __construct()
    {
        $this->model = new Detail_pembelianModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll();
        return view('detail_pembelian/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('detail_pembelian'));
        }
        return view('detail_pembelian/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('detail_pembelian'));
        }
        $data['model'] =  $this->model->where('id_detail_pembelian', $id)->first();
        return view('detail_pembelian/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('detail_pembelian'));
    }
}

