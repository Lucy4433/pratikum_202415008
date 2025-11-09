<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use CodeIgniter\HTTP\ResponseInterface;

class Produk extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ProdukModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll();
        return view('produk/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('produk'));
        }
        return view('produk/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('produk'));
        }
        $data['model'] =  $this->model->where('id_produk', $id)->first();
        return view('produk/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('produk'));
    }
}
