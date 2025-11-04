<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MerekModel;
use CodeIgniter\HTTP\ResponseInterface;

class Merek extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new MerekModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll();
        return view('merek/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('merek'));
        }
        return view('merek/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('merek'));
        }
        $data['model'] =  $this->model->where('id_merek', $id)->first();
        return view('merek/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('merek'));
    }
}
