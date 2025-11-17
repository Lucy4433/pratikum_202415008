<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Model\ProfiltokoModel;
use App\Models\Profil_tokoModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfilToko extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ProfiltokoModel();
    }

    public function index()
    {
        $data['model'] =  $this->model->findAll();
        return view('profil_toko/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('profil_toko'));
        }
        return view('profil_toko/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->model->save($this->request->getPost());
            return redirect()->to(base_url('profil_toko'));
        }
        $data['model'] =  $this->model->where('id_profil_toko', $id)->first();
        return view('profil_toko/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('profil_toko'));
    }
}

