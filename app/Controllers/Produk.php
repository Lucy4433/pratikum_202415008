<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\MerekModel;

class Produk extends BaseController
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    /**
     * ==========================================
     * LIST PRODUK
     * ==========================================
     */
    public function index()
    {
        $data = [
            'produk' => $this->produkModel->getAll()
        ];

        return view('produk/index', $data);
    }
    


    /**
     * ==========================================
     * FORM TAMBAH PRODUK
     * (HANYA NAMA & MEREK)
     * ==========================================
     */
    public function create()
    {
        $merekModel = new MerekModel();

        $data = [
            'merek' => $merekModel->findAll()
        ];

        return view('produk/tambah', $data);
    }

    /**
     * ==========================================
     * SIMPAN PRODUK (INSERT)
     * harga & stok NULL / 0
     * ==========================================
     */
    public function store()
    {
        $this->produkModel->insert([
            'id_merek'    => $this->request->getPost('id_merek'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga_beli'  => null,
            'harga_jual'  => null,
            'stok'        => 0
        ]);

        return redirect()->to('/produk')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * ==========================================
     * FORM EDIT PRODUK
     * (TANPA HARGA & STOK)
     * ==========================================
     */
    public function edit($id)
    {
        $merekModel = new MerekModel();

        $data = [
            'produk' => $this->produkModel->getById($id),
            'merek'  => $merekModel->findAll()
        ];

        return view('produk/edit', $data);
    }

    /**
     * ==========================================
     * UPDATE PRODUK
     * ==========================================
     */
    public function update($id)
    {
        $this->produkModel->update($id, [
            'id_merek'    => $this->request->getPost('id_merek'),
            'nama_produk' => $this->request->getPost('nama_produk')
        ]);

        return redirect()->to('/produk')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * ==========================================
     * DELETE PRODUK
     * (AMAN JIKA BELUM ADA BARANG MASUK)
     * ==========================================
     */
    public function delete($id)
    {
        $this->produkModel->delete($id);

        return redirect()->to('/produk')
            ->with('success', 'Produk berhasil dihapus');
    }
}
