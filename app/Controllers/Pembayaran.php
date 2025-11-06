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
        $data['model'] =  $this->model->findAll();
        return view('pembayaran/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('pembayaran'));
        }
        return view('pembayaran/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('pembayaran'));
        }
        $data['model'] =  $this->model->where('id_pembayaran', $id)->first();
        return view('pembayaran/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('pembayaran'));
    }
}
