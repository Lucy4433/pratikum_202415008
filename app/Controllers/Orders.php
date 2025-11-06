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
        $data['model'] =  $this->model->findAll();
        return view('orders/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('orders'));
        }
        return view('orders/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('orders'));
        }
        $data['model'] =  $this->model->where('id_orders', $id)->first();
        return view('orders/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('orders'));
    }
}
