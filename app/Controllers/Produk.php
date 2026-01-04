<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class Produk extends BaseController
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    /**
     * ==========================================
     * PRODUK (READ ONLY)
     * Data diambil dari supplier_produk
     * Stok = SUM dari semua supplier
     * ==========================================
     */
    public function index()
    {
        $data = [
            'produk' => $this->produkModel->getProdukReadOnly()
        ];

        return view('produk/index', $data);
    }
}
