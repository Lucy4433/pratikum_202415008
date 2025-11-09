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
        $data['model'] =  $this->model->findAll();
        foreach ($data['model'] as $key => $value) {
            $value->detail = $this->detail->getById($value->id_pembelian);
        }
        return view('pembelian/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('pembelian'));
        }
        return view('pembelian/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('pembelian'));
        }
        $data['model'] =  $this->model->where('id_pembelian', $id)->first();
        return view('pembelian/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('pembelian'));
    }
}
