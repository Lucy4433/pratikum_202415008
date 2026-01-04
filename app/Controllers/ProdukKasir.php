<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class ProdukKasir extends BaseController
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }
    
    public function index()
    {
        $produk = $this->produkModel->getProdukReadOnly();

        return view('ProdukKasir/index', [
            'title'  => 'Daftar Produk (Kasir)',
            'produk' => $produk,
        ]);
    }
}
