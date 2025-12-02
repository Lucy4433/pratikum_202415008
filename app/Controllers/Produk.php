<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\MerekModel;
use App\Models\ProdukModel;

class Produk extends BaseController
{
    protected $model;
    protected $discount;
    protected $merek;

    public function __construct()
    {
        $this->model = new ProdukModel();
        $this->discount = new DiscountModel();
        $this->merek = new MerekModel();
    }

    public function index()
    {
        $data['produk'] = $this->model->getProdukWithMerek();
        $data['merek'] = $this->merek->findAll();
        foreach ($data['produk'] as $key => $value) {
            $value->discount = $this->discount->getByProductId($value->id_produk);
        }
        // dd($data);
        return view('produk/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') {
            $post = $this->request->getPost(['nama_produk', 'id_merek', 'harga', 'stok']);
            $post['harga'] = preg_replace('/\D/', '', $post['harga']);
            $this->model->save($post);
            return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil ditambahkan.');
        }

        $data['merek'] = $this->merek->findAll();
        return view('produk/tambah', $data);
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $post = $this->request->getPost(['id_produk', 'id_merek', 'nama_produk', 'harga', 'stok']);
            $post['harga'] = preg_replace('/\D/', '', $post['harga']);
            $this->model->update($post['id_produk'], $post);
            return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil diperbarui.');
        }

        $data['produk'] = $this->model->find($id);
        $data['merek']  = $this->merek->findAll();
        return view('produk/ubah', $data);
    }

    public function hapus($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil dihapus.');
    }
}
