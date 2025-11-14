<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SuplierModel;

class Supplier extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new SuplierModel();
        helper('form');
    }

    public function index()
    {
        $data['model'] = $this->model->findAll();
        return view('supplier/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() === 'post') {
            $post = $this->request->getPost(['nama', 'alamat', 'telepon', 'email']);
            $this->model->insert($post);
            return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil disimpan.');
        }

        return view('supplier/tambah');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $post = $this->request->getPost(['nama', 'alamat', 'telepon', 'email']);
            $this->model->update($id, $post);
            return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil diperbarui.');
        }

        $data['model'] = $this->model->find($id);
        return view('supplier/ubah', $data);
    }

    public function hapus($id = null)
    {
        if ($id) {
            $this->model->delete($id);
        }
        return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil dihapus.');
    }
}
