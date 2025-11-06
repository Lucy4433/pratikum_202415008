<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use CodeIgniter\HTTP\ResponseInterface;

class Discount extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DiscountModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll();
        return view('discount/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('discount'));
        }
        return view('discount/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('discount'));
        }
        $data['model'] =  $this->model->where('id_discount', $id)->first();
        return view('discount/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('discount'));
    }
}

