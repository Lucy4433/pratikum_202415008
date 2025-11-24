<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailOrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class Detail_order extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DetailOrderModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll();
        return view('detail_order/index', $data);
    }
    
    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('detail_order'));
        }
        return view('detail_order/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('detail_order'));
        }
        $data['model'] =  $this->model->where('id_detail_order', $id)->first();
        return view('detail_order/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('detail_order'));
    }
}
